<?php

namespace App\Http\Controllers\User;

use App\Actions\Project\CreateOpenSourceProjectAction;
use App\Actions\Project\DeleteOpenSourceProjectAction;
use App\Actions\Project\UpdateOpenSourceProjectAction;
use App\Actions\Publication\CreatePublicationAction;
use App\Actions\Publication\DeletePublicationAction;
use App\Actions\Publication\UpdatePublicationAction;
use App\DTOs\Project\OpenSourceProjectData;
use App\DTOs\Publication\PublicationData;
use App\Http\Controllers\Controller;
use App\Models\OpenSourceProject;
use App\Models\Publication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Inertia\Inertia;
use Inertia\Response;

/**
 * The user's publishing hub: everything they have submitted, of either kind,
 * with its approval status. Both kinds share one set of ownership guards.
 */
class PublishController extends Controller
{
    /** What the feature would like to allow, in kilobytes, before PHP has its say. */
    private const DESIGN_LIMITS_KB = [
        'cover' => 4096,
        'files' => 20480,
        'pdf' => 51200,
    ];

    /**
     * PHP enforces upload_max_filesize / post_max_size in the SAPI, before any Laravel
     * code runs — a rule of `max:20480` against a 2M ini is a promise the server cannot
     * keep, and the user gets a raw 413 instead of a validation message. Cap every limit
     * at what PHP will actually accept, and advertise that same number in the UI.
     */
    public static function maxKb(string $key): int
    {
        return (int) min(
            self::DESIGN_LIMITS_KB[$key],
            floor(UploadedFile::getMaxFilesize() / 1024),
        );
    }

    /** @return array<string, string> */
    private static function projectRules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'category' => 'required|string|in:3d_model,iot_system,medical_device,software',
            'listing_type' => 'nullable|string|in:journals,products,powerpoint,downloadable,read_only',
            'caption' => 'nullable|string|max:500',
            'description' => 'nullable|array',
            'description.*' => 'nullable|string',
            'highlights' => 'nullable|array',
            'highlights.*' => 'nullable|string',
            'includes' => 'nullable|array',
            'includes.*' => 'nullable|string',
            'license' => 'nullable|string|in:MIT,Apache 2.0,CC BY 4.0,GPL-3.0',
            'version' => 'nullable|string|max:255',
            'format' => 'nullable|string|max:255',
            // Becomes the primary attachment, i.e. the card cover. Without it the cover fell
            // to whatever file happened to be first — often a ZIP, which renders as nothing.
            'cover_file' => 'nullable|image|max:'.self::maxKb('cover'),
            'files' => 'nullable|array',
            'files.*' => 'file|max:'.self::maxKb('files'),
        ];
    }

    /** @return array<string, string> */
    private static function publicationRules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'category' => 'required|string|in:Journals,Papers',
            'abstract' => 'nullable|string',
            'description' => 'nullable|array',
            'description.*' => 'nullable|string',
            'keywords' => 'nullable|array',
            'keywords.*' => 'nullable|string',
            'doi' => 'nullable|string|max:255',
            'journal' => 'nullable|string|max:255',
            'pmid' => 'nullable|string|max:255',
            'published_at' => 'nullable|date',
            'thumbnail_file' => 'nullable|image|max:'.self::maxKb('cover'),
            'pdf_file' => 'nullable|mimes:pdf|max:'.self::maxKb('pdf'),
        ];
    }

    private static function rulesFor(string $kind): array
    {
        return $kind === 'project' ? self::projectRules() : self::publicationRules();
    }

    /** Kilobyte ceilings the form pages use for their hints and their pre-submit check. */
    private static function uploadLimits(): array
    {
        return [
            'coverKb' => self::maxKb('cover'),
            'filesKb' => self::maxKb('files'),
            'pdfKb' => self::maxKb('pdf'),
        ];
    }

    public function index(): Response
    {
        $userId = auth()->id();

        $items = OpenSourceProject::where('user_id', $userId)
            ->with(['attachments' => fn ($q) => $q->where('is_primary', true)])
            ->get()
            ->map(fn (OpenSourceProject $p) => $this->projectRow($p))
            ->concat(
                Publication::where('user_id', $userId)
                    ->get()
                    ->map(fn (Publication $p) => $this->publicationRow($p))
            )
            // ISO date strings sort lexicographically; values() keeps it a JSON array.
            ->sortByDesc('date')
            ->values();

        return Inertia::render('Features/Publish/Pages/PublishPage', compact('items'));
    }

    public function create(): Response
    {
        // kind: null tells the page to show the picker first.
        return Inertia::render('Features/Publish/Pages/PublishFormPage', [
            'kind' => null,
            'item' => null,
            'limits' => self::uploadLimits(),
        ]);
    }

    public function edit(string $kind, int $id): Response
    {
        $model = $this->model($kind, $id);
        $this->guard($model);

        return Inertia::render('Features/Publish/Pages/PublishFormPage', [
            'kind' => $kind,
            'item' => $kind === 'project'
                ? $this->projectFormData($model)
                : $this->publicationFormData($model),
            'limits' => self::uploadLimits(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $kind = $request->validate(['kind' => 'required|in:project,publication'])['kind'];
        $validated = $request->validate(self::rulesFor($kind));

        if ($kind === 'project') {
            app(CreateOpenSourceProjectAction::class)->execute(new OpenSourceProjectData(
                user_id: auth()->id(),
                title: $validated['title'],
                category: $validated['category'],
                new_files: $this->projectFiles($request),
                status: 'pending',
                caption: $validated['caption'] ?? null,
                listing_type: $validated['listing_type'] ?? null,
                description: array_filter($validated['description'] ?? []),
                highlights: array_filter($validated['highlights'] ?? []),
                includes: array_filter($validated['includes'] ?? []),
                license: $validated['license'] ?? 'MIT',
                version: $validated['version'] ?? null,
                format: $validated['format'] ?? null,
            ));
        } else {
            app(CreatePublicationAction::class)->execute(new PublicationData(
                title: $validated['title'],
                author: $validated['author'],
                category: $validated['category'],
                abstract: $validated['abstract'] ?? null,
                description: array_filter($validated['description'] ?? []),
                keywords: array_filter($validated['keywords'] ?? []),
                doi: $validated['doi'] ?? null,
                journal: $validated['journal'] ?? null,
                pmid: $validated['pmid'] ?? null,
                published_at: $validated['published_at'] ?? null,
                thumbnail_file: $request->file('thumbnail_file'),
                pdf_file: $request->file('pdf_file'),
                user_id: auth()->id(),
                status: 'pending',
            ));
        }

        return to_route('publish.index')->with('success', __('Submitted for review.'));
    }

    public function update(Request $request, string $kind, int $id): RedirectResponse
    {
        $model = $this->model($kind, $id);
        $this->guard($model);

        $validated = $request->validate(self::rulesFor($kind));

        $wasRejected = $model->status === 'rejected';

        if ($kind === 'project') {
            app(UpdateOpenSourceProjectAction::class)->execute($model, new OpenSourceProjectData(
                user_id: auth()->id(),
                title: $validated['title'],
                category: $validated['category'],
                new_files: $this->projectFiles($request),
                status: $model->status,
                caption: $validated['caption'] ?? null,
                listing_type: $validated['listing_type'] ?? null,
                description: array_filter($validated['description'] ?? []),
                highlights: array_filter($validated['highlights'] ?? []),
                includes: array_filter($validated['includes'] ?? []),
                license: $validated['license'] ?? 'MIT',
                version: $validated['version'] ?? null,
                format: $validated['format'] ?? null,
            ));
        } else {
            app(UpdatePublicationAction::class)->execute($model, new PublicationData(
                title: $validated['title'],
                author: $validated['author'],
                category: $validated['category'],
                // Keep the original slug: the action does not de-duplicate, and a rename
                // must not break inbound /publications/{slug} links.
                slug: $model->slug,
                abstract: $validated['abstract'] ?? null,
                description: array_filter($validated['description'] ?? []),
                keywords: array_filter($validated['keywords'] ?? []),
                doi: $validated['doi'] ?? null,
                journal: $validated['journal'] ?? null,
                pmid: $validated['pmid'] ?? null,
                // Admin-owned flags — a user edit must not clear them.
                is_free_access: $model->is_free_access,
                is_featured: $model->is_featured,
                published_at: $validated['published_at'] ?? null,
                thumbnail_file: $request->file('thumbnail_file'),
                pdf_file: $request->file('pdf_file'),
            ));
        }

        // Editing a rejected submission puts it back in the queue.
        if ($wasRejected) {
            $model->update(['status' => 'pending', 'validated_by' => null]);
        }

        return to_route('publish.index')->with('success', __('Submission updated.'));
    }

    /**
     * Deletes outright while the work is still pending or rejected. Once an admin has
     * approved it, it is live on the public site, so the same button records a removal
     * request for the moderation queue instead of pulling it unilaterally.
     */
    public function destroy(string $kind, int $id): RedirectResponse
    {
        $model = $this->model($kind, $id);
        abort_if($model->user_id !== auth()->id(), 403);

        if ($model->status === 'approved') {
            $model->update(['withdrawal_requested_at' => now()]);

            return to_route('publish.index')
                ->with('success', __('Removal requested. An admin will review it shortly.'));
        }

        $kind === 'project'
            ? app(DeleteOpenSourceProjectAction::class)->execute($model)
            : app(DeletePublicationAction::class)->execute($model);

        return to_route('publish.index')->with('success', __('Submission deleted.'));
    }

    // ── Internals ─────────────────────────────────────────────

    /**
     * The cover goes first so CreateOpenSourceProjectAction flags it is_primary (it marks
     * index 0), which is what every card and listing reads as the cover image.
     *
     * @return array<int, UploadedFile>
     */
    private function projectFiles(Request $request): array
    {
        $cover = $request->file('cover_file');
        $files = $request->file('files', []);

        return $cover ? array_merge([$cover], $files) : $files;
    }

    private function model(string $kind, int $id): OpenSourceProject|Publication
    {
        return $kind === 'project'
            ? OpenSourceProject::findOrFail($id)
            : Publication::findOrFail($id);
    }

    /** Owner-only, and frozen once an admin has approved it. */
    private function guard(OpenSourceProject|Publication $model): void
    {
        abort_if($model->user_id !== auth()->id(), 403);
        abort_if($model->status === 'approved', 403);
    }

    /** @return array<string, mixed> */
    private function projectRow(OpenSourceProject $p): array
    {
        return [
            'id' => $p->id,
            'kind' => 'project',
            'category' => 'Projects',
            'status' => $p->status,
            'title' => $p->localized('title'),
            'coverUrl' => $p->attachments->first()?->public_url,
            'date' => $p->created_at->toDateString(),
            'detailHref' => $p->status === 'approved' ? route('projects.show', $p->id) : null,
            'editHref' => route('publish.edit', ['kind' => 'project', 'id' => $p->id]),
            'deleteUrl' => route('publish.destroy', ['kind' => 'project', 'id' => $p->id]),
            'canEdit' => $p->status !== 'approved',
            // Always offered: on approved work the button files a removal request
            // rather than deleting. See destroy().
            'canDelete' => true,
            'withdrawalRequested' => $p->withdrawal_requested_at !== null,
        ];
    }

    /** @return array<string, mixed> */
    private function publicationRow(Publication $p): array
    {
        return [
            'id' => $p->id,
            'kind' => 'publication',
            'category' => $p->category,
            'status' => $p->status,
            'title' => $p->localized('title'),
            'coverUrl' => $p->thumbnail_url,
            'date' => ($p->published_at ?? $p->created_at)->toDateString(),
            'detailHref' => $p->status === 'approved' ? route('publications.show', $p->slug) : null,
            'editHref' => route('publish.edit', ['kind' => 'publication', 'id' => $p->id]),
            'deleteUrl' => route('publish.destroy', ['kind' => 'publication', 'id' => $p->id]),
            'canEdit' => $p->status !== 'approved',
            'canDelete' => true,
            'withdrawalRequested' => $p->withdrawal_requested_at !== null,
        ];
    }

    /**
     * Raw columns, never localized() — a form reads a value and writes it straight back,
     * so serving the English overlay would overwrite the Indonesian original on save.
     * Same reasoning as Admin\Product\Index::edit().
     *
     * @return array<string, mixed>
     */
    private function projectFormData(OpenSourceProject $p): array
    {
        return [
            'id' => $p->id,
            'title' => $p->title,
            'category' => $p->category,
            'listing_type' => $p->listing_type,
            'caption' => $p->caption,
            'description' => $p->description ?: [],
            'highlights' => $p->highlights ?: [],
            'includes' => $p->includes ?: [],
            'license' => $p->license ?? 'MIT',
            'version' => $p->version,
            'format' => $p->format,
            // Feeds the upload zone's preview so an edit shows what is already attached.
            'coverUrl' => $p->attachments->firstWhere('is_primary', true)?->public_url,
            'existingFiles' => $p->attachments
                ->where('is_primary', false)
                ->map(fn ($a) => [
                    'name' => $a->file_name ?: basename((string) $a->file_url),
                    'size' => $a->file_size,
                    'url' => $a->public_url,
                ])
                ->values(),
        ];
    }

    /** @return array<string, mixed> */
    private function publicationFormData(Publication $p): array
    {
        return [
            'id' => $p->id,
            'title' => $p->title,
            'author' => $p->author,
            'category' => $p->category,
            'abstract' => $p->abstract,
            'description' => $p->description ?: [],
            'keywords' => $p->keywords ?: [],
            'doi' => $p->doi,
            'journal' => $p->journal,
            'pmid' => $p->pmid,
            'published_at' => $p->published_at?->format('Y-m-d'),
            'coverUrl' => $p->thumbnail_url,
            'pdfUrl' => $p->pdf_url,
            'pdfName' => $p->pdf_path ? basename($p->pdf_path) : null,
            'pdfSize' => $p->pdf_file_size,
        ];
    }
}
