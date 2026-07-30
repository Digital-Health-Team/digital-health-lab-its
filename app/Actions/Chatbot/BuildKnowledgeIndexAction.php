<?php

namespace App\Actions\Chatbot;

use App\Models\Event;
use App\Models\KnowledgeChunk;
use App\Models\LabTeamPerson;
use App\Models\OpenSourceProject;
use App\Models\PageSection;
use App\Models\Product;
use App\Models\Publication;
use App\Models\Service;
use App\Models\Training;
use App\Services\GeminiClient;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * Builds `knowledge_chunks` from two sources: markdown in knowledge/{id,en}/ and the
 * Eloquent models behind the public pages.
 *
 * The contract for both is docs/rag-chatbot/FORMAT-KNOWLEDGE.md. Public knowledge only —
 * nothing user-owned is ever embedded here.
 *
 * Documents whose source_hash is unchanged are skipped without touching the embedding
 * API. That is what makes a daily reindex affordable: a run where nothing changed costs
 * zero quota.
 */
class BuildKnowledgeIndexAction
{
    /** Values accepted by `chatbot:index --only=`. */
    public const SOURCES = [
        'md', 'service', 'product', 'event', 'training',
        'publication', 'project', 'page', 'team',
    ];

    /** One source of truth with RetrieveKnowledgeAction::flushCache(). */
    private const LOCALES = KnowledgeChunk::LOCALES;

    /**
     * Hard ceiling per chunk. The embedding model truncates silently at 2,048 tokens, so
     * an over-long section would lose its tail with no warning at all.
     */
    private const MAX_SECTION_CHARS = 1500;

    /** @var array<string, string> `--only` value => source_key prefix, used when pruning. */
    private const PREFIXES = [
        'md' => 'md:',
        'service' => 'db:service:',
        'product' => 'db:product:',
        'event' => 'db:event:',
        'training' => 'db:training:',
        'publication' => 'db:publication:',
        'project' => 'db:project:',
        'page' => 'db:page:',
        'team' => 'db:team:',
    ];

    public function __construct(private readonly GeminiClient $gemini) {}

    /**
     * @param  array<int, string>  $only  Empty means every source.
     * @return array{indexed: int, skipped: int, pruned: int, chunks: int}
     */
    public function execute(array $only = [], bool $fresh = false): array
    {
        $sources = $only === [] ? self::SOURCES : array_values(array_intersect(self::SOURCES, $only));

        if ($sources === []) {
            throw new RuntimeException(
                'No known source selected. Available: '.implode(', ', self::SOURCES)
            );
        }

        if ($fresh) {
            KnowledgeChunk::query()
                ->where(function ($query) use ($sources) {
                    foreach ($sources as $source) {
                        $query->orWhere('source_key', 'like', self::PREFIXES[$source].'%');
                    }
                })
                ->delete();
        }

        $stats = ['indexed' => 0, 'skipped' => 0, 'pruned' => 0, 'chunks' => 0];
        $delayMicroseconds = max(0, (int) config('gemini.ingest_delay_ms')) * 1000;
        $dimensions = (int) config('gemini.embedding_dimensions');

        foreach ($sources as $source) {
            $seen = [];

            foreach ($this->documents($source) as $document) {
                $seen[] = $document['source_key'];

                if ($this->isUnchanged($document)) {
                    $stats['skipped']++;

                    continue;
                }

                // Embed the whole document BEFORE touching the table.
                //
                // Writing chunk by chunk looks cheaper but corrupts the index the first
                // time a 429 lands mid-document: the chunks already written carry the new
                // source_hash, so the next run sees the document as unchanged and skips it
                // forever, permanently short of its remaining sections. Failing before any
                // write costs one document's re-embedding on retry and cannot lose data.
                $rows = [];

                foreach ($this->chunk($document) as $chunk) {
                    $vector = $this->gemini->embed($chunk['embed_text'], GeminiClient::TASK_DOCUMENT);

                    $rows[] = [
                        'source_key' => $document['source_key'],
                        'source_hash' => $document['source_hash'],
                        'locale' => $document['locale'],
                        'audience' => $document['audience'],
                        'category' => $document['category'],
                        'title' => $document['title'],
                        'heading' => $chunk['heading'],
                        'content' => $chunk['content'],
                        'url' => $document['url'],
                        'embedding' => pack('g*', ...$vector),
                        'dimensions' => $dimensions,
                    ];

                    if ($delayMicroseconds > 0) {
                        usleep($delayMicroseconds);
                    }
                }

                // Swap old for new atomically, with no network call inside the transaction.
                DB::transaction(function () use ($document, $rows) {
                    KnowledgeChunk::query()->where('source_key', $document['source_key'])->delete();

                    foreach ($rows as $row) {
                        KnowledgeChunk::create($row);
                    }
                });

                $stats['chunks'] += count($rows);
                $stats['indexed']++;
            }

            // A product that was deactivated, or a markdown file that was deleted, stops
            // producing documents but its chunks would otherwise answer questions forever.
            $stats['pruned'] += KnowledgeChunk::query()
                ->where('source_key', 'like', self::PREFIXES[$source].'%')
                ->when($seen !== [], fn ($query) => $query->whereNotIn('source_key', $seen))
                ->delete();
        }

        RetrieveKnowledgeAction::flushCache();

        return $stats;
    }

    /**
     * @return array<int, array{source_key: string, source_hash: string, locale: string, audience: string, category: string, title: string, url: ?string, keywords: array<int, string>, body: string}>
     */
    private function documents(string $source): array
    {
        if ($source === 'md') {
            return $this->markdownDocuments();
        }

        $documents = [];

        foreach (self::LOCALES as $locale) {
            $previous = App::getLocale();
            App::setLocale($locale);

            try {
                // localized() reads app()->getLocale() globally rather than taking a
                // locale argument, so the locale has to be swapped around the read.
                $documents = array_merge($documents, match ($source) {
                    'service' => $this->serviceDocuments($locale),
                    'product' => $this->productDocuments($locale),
                    'event' => $this->eventDocuments($locale),
                    'training' => $this->trainingDocuments($locale),
                    'publication' => $this->publicationDocuments($locale),
                    'project' => $this->projectDocuments($locale),
                    'page' => $this->pageSectionDocuments($locale),
                    'team' => $this->teamDocuments($locale),
                });
            } finally {
                App::setLocale($previous);
            }
        }

        return $documents;
    }

    // ---------------------------------------------------------------------
    // Source A — markdown
    // ---------------------------------------------------------------------

    /** @return array<int, array<string, mixed>> */
    private function markdownDocuments(): array
    {
        $documents = [];

        $root = rtrim((string) config('gemini.knowledge_path'), '/');

        foreach (self::LOCALES as $locale) {
            $directory = "{$root}/{$locale}";

            if (! File::isDirectory($directory)) {
                continue;
            }

            foreach (File::glob("{$directory}/*.md") as $path) {
                [$meta, $body] = $this->parseFrontmatter(File::get($path), basename($path));

                $documents[] = $this->document(
                    sourceKey: "md:{$locale}:{$meta['id']}",
                    locale: $locale,
                    audience: $meta['audience'],
                    category: $meta['kategori'],
                    title: $meta['judul'],
                    url: $meta['url'] ?? null,
                    keywords: $meta['kata_kunci'] ?? [],
                    body: $body,
                );
            }
        }

        return $documents;
    }

    /**
     * Minimal front-matter reader for the seven keys FORMAT-KNOWLEDGE.md contracts.
     *
     * Hand-rolled on purpose: symfony/yaml is present in vendor/ but only as a
     * require-dev transitive of laravel/sail and laravel/roster, so it disappears under
     * `composer install --no-dev` and would take the production ingest down with it.
     *
     * Handles scalars (`judul: Prosedur ...`) and flow sequences that may wrap across
     * lines (`kata_kunci: [cetak 3d,\n  print 3d]`), which is how the existing files
     * are formatted.
     *
     * @return array{0: array<string, mixed>, 1: string}
     */
    private function parseFrontmatter(string $raw, string $filename): array
    {
        $raw = ltrim(str_replace("\r\n", "\n", $raw));

        if (! str_starts_with($raw, "---\n")) {
            throw new RuntimeException("knowledge/{$filename}: missing YAML front matter.");
        }

        $end = strpos($raw, "\n---", 3);

        if ($end === false) {
            throw new RuntimeException("knowledge/{$filename}: front matter is never closed.");
        }

        $meta = [];
        $key = null;
        $buffer = '';

        foreach (explode("\n", substr($raw, 4, $end - 4)) as $line) {
            $startsNewKey = preg_match('/^([A-Za-z_][A-Za-z0-9_]*):\s*(.*)$/', $line, $matches) === 1;

            // A wrapped flow sequence is still open, so this line belongs to it even if
            // it happens to look like `key: value`.
            if ($startsNewKey && ! $this->isOpenSequence($buffer)) {
                if ($key !== null) {
                    $meta[$key] = $this->castFrontmatterValue($buffer);
                }

                $key = $matches[1];
                $buffer = trim($matches[2]);

                continue;
            }

            if ($key !== null) {
                $buffer = trim($buffer.' '.trim($line));
            }
        }

        if ($key !== null) {
            $meta[$key] = $this->castFrontmatterValue($buffer);
        }

        foreach (['id', 'judul', 'kategori', 'audience'] as $required) {
            if (($meta[$required] ?? '') === '') {
                throw new RuntimeException("knowledge/{$filename}: front matter is missing `{$required}`.");
            }
        }

        return [$meta, trim(substr($raw, $end + 4))];
    }

    private function isOpenSequence(string $buffer): bool
    {
        return substr_count($buffer, '[') > substr_count($buffer, ']');
    }

    /** @return string|array<int, string> */
    private function castFrontmatterValue(string $value): string|array
    {
        if (str_starts_with($value, '[')) {
            return array_values(array_filter(array_map(
                fn (string $item): string => trim($item, " \t\"'"),
                explode(',', trim($value, '[] ')),
            ), fn (string $item): bool => $item !== ''));
        }

        return trim($value, " \"'");
    }

    // ---------------------------------------------------------------------
    // Source B — Eloquent models
    // ---------------------------------------------------------------------

    /** @return array<int, array<string, mixed>> */
    private function serviceDocuments(string $locale): array
    {
        // No is_active column on services — every row is live.
        return Service::query()->orderBy('id')->get()->map(function (Service $service) use ($locale) {
            $name = (string) $service->localized('name');

            $body = $this->section($this->phrase('service.about', $locale, [':name' => $name]), [
                (string) $service->localized('description'),
            ]);

            $body .= $this->section($this->phrase('service.cost', $locale, [':name' => $name]), [
                $this->phrase('service.cost_body', $locale, [
                    ':name' => $name,
                    ':price' => $this->rupiah((int) $service->base_price),
                ]),
            ]);

            $body .= $this->section($this->phrase('service.order', $locale, [':name' => $name]), [
                $this->phrase('service.order_body', $locale, [':name' => $name]),
                $service->whatsapp_number
                    ? $this->phrase('service.whatsapp', $locale, [':number' => (string) $service->whatsapp_number])
                    : '',
            ]);

            return $this->document(
                sourceKey: "db:service:{$service->id}:{$locale}",
                locale: $locale,
                audience: 'public',
                category: 'layanan',
                title: $name,
                url: $this->path('services.show', $service->id),
                keywords: array_filter([$service->service_type]),
                body: $body,
            );
        })->all();
    }

    /** @return array<int, array<string, mixed>> */
    private function productDocuments(string $locale): array
    {
        return Product::query()
            ->where('is_active', true)
            ->orderBy('id')
            ->get()
            ->map(function (Product $product) use ($locale) {
                $name = (string) $product->localized('name');
                $min = (int) $product->price_min;
                $max = (int) $product->price_max;

                $body = $this->section($this->phrase('product.about', $locale, [':name' => $name]), [
                    (string) $product->localized('description'),
                ]);

                $body .= $this->section($this->phrase('product.price', $locale, [':name' => $name]), [
                    $min === $max
                        ? $this->phrase('product.price_flat', $locale, [
                            ':name' => $name,
                            ':price' => $this->rupiah($min),
                        ])
                        : $this->phrase('product.price_range', $locale, [
                            ':name' => $name,
                            ':min' => $this->rupiah($min),
                            ':max' => $this->rupiah($max),
                        ]),
                ]);

                return $this->document(
                    sourceKey: "db:product:{$product->id}:{$locale}",
                    locale: $locale,
                    audience: 'public',
                    category: 'produk',
                    title: $name,
                    url: $this->path('products.show', $product->id),
                    keywords: [],
                    body: $body,
                );
            })->all();
    }

    /** @return array<int, array<string, mixed>> */
    private function eventDocuments(string $locale): array
    {
        return Event::query()
            ->where('is_active', true)
            // A finished event stays answerable for a month — "when was the exhibition?"
            // is a fair question right after it ends — then drops out of the index.
            ->where(fn ($query) => $query->whereNull('ends_at')->orWhere('ends_at', '>=', now()->subMonth()))
            ->orderBy('id')
            ->get()
            ->map(function (Event $event) use ($locale) {
                $name = (string) $event->localized('name');

                $body = $this->section(
                    $this->phrase('event.about', $locale, [
                        ':category' => (string) ($event->category ?? ''),
                        ':name' => $name,
                    ]),
                    [
                        (string) $event->localized('theme_title'),
                        (string) $event->localized('subtitle'),
                        (string) $event->localized('description'),
                    ],
                );

                $body .= $this->section($this->phrase('event.schedule', $locale, [':name' => $name]), [
                    $this->phrase('event.schedule_body', $locale, [
                        ':name' => $name,
                        ':start' => $this->longDate($event->starts_at, $locale),
                        ':end' => $this->longDate($event->ends_at, $locale),
                        ':location' => (string) $event->localized('location'),
                    ]),
                    $event->registration_url
                        ? $this->phrase('event.registration', $locale, [':url' => (string) $event->registration_url])
                        : '',
                ]);

                return $this->document(
                    sourceKey: "db:event:{$event->id}:{$locale}",
                    locale: $locale,
                    audience: 'public',
                    category: 'agenda',
                    title: $name,
                    url: $this->path('events.show', $event->slug),
                    keywords: array_filter([$event->category, (string) $event->year]),
                    body: $body,
                );
            })->all();
    }

    /** @return array<int, array<string, mixed>> */
    private function trainingDocuments(string $locale): array
    {
        return Training::query()
            ->where('is_active', true)
            ->orderBy('id')
            ->get()
            ->map(function (Training $training) use ($locale) {
                $title = (string) $training->localized('title');

                $body = $this->section($this->phrase('training.about', $locale, [':title' => $title]), [
                    (string) $training->localized('subtitle'),
                    (string) $training->localized('description'),
                ]);

                $body .= $this->section($this->phrase('training.schedule', $locale, [':title' => $title]), [
                    $this->phrase('training.schedule_body', $locale, [
                        ':title' => $title,
                        ':date' => $this->longDate($training->date, $locale),
                        ':location' => (string) $training->localized('location'),
                        ':level' => (string) ($training->level ?? ''),
                        ':duration' => (string) ($training->duration ?? ''),
                        ':language' => (string) ($training->language ?? ''),
                        ':quota' => (string) ($training->max_participants ?? ''),
                    ]),
                ]);

                $body .= $this->section($this->phrase('training.price', $locale, [':title' => $title]), [
                    $training->is_paid
                        ? $this->phrase('training.price_paid', $locale, [':price' => $this->rupiah((int) $training->price)])
                        : $this->phrase('training.price_free', $locale),
                    $this->phrase('training.price_body', $locale),
                ]);

                $body .= $this->section($this->phrase('training.curriculum', $locale, [':title' => $title]), [
                    $this->flatten($training->localized('what_you_will_learn')),
                ]);

                $body .= $this->section($this->phrase('training.instructor', $locale, [':title' => $title]), [
                    trim(implode(', ', array_filter([
                        (string) ($training->instructor_name ?? ''),
                        (string) $training->localized('instructor_title'),
                    ]))),
                    (string) $training->localized('instructor_bio'),
                ]);

                return $this->document(
                    sourceKey: "db:training:{$training->id}:{$locale}",
                    locale: $locale,
                    audience: 'public',
                    category: 'agenda',
                    title: $title,
                    url: $this->path('training.show', $training->slug),
                    keywords: array_filter([$training->category, $training->level]),
                    body: $body,
                );
            })->all();
    }

    /** @return array<int, array<string, mixed>> */
    private function publicationDocuments(string $locale): array
    {
        return Publication::query()
            ->orderBy('id')
            ->get()
            ->map(function (Publication $publication) use ($locale) {
                $title = (string) $publication->localized('title');

                $body = $this->section($this->phrase('publication.about', $locale, [':title' => $title]), [
                    $this->phrase('publication.about_body', $locale, [
                        ':title' => $title,
                        ':author' => (string) ($publication->author ?? ''),
                        ':year' => (string) ($publication->published_at?->format('Y') ?? ''),
                        ':journal' => (string) ($publication->journal ?? ''),
                    ]),
                    $publication->doi
                        ? $this->phrase('publication.doi', $locale, [':doi' => (string) $publication->doi])
                        : '',
                    $this->phrase('publication.category', $locale, [':category' => (string) ($publication->category ?? '')]),
                    $this->phrase($publication->is_free_access ? 'publication.open' : 'publication.closed', $locale),
                ]);

                $body .= $this->section($this->phrase('publication.abstract', $locale, [':title' => $title]), [
                    Str::limit((string) $publication->localized('abstract'), 1200, ''),
                ]);

                return $this->document(
                    sourceKey: "db:publication:{$publication->id}:{$locale}",
                    locale: $locale,
                    audience: 'public',
                    category: 'publikasi',
                    title: $title,
                    url: $this->path('publications.show', $publication->slug),
                    keywords: array_merge(
                        array_filter([$publication->category, $publication->journal]),
                        (array) ($publication->localized('keywords') ?? []),
                    ),
                    body: $body,
                );
            })->all();
    }

    /** @return array<int, array<string, mixed>> */
    private function projectDocuments(string $locale): array
    {
        // 'approved' is the public gate for student submissions — same filter the site
        // search uses (GlobalSearchController::searchProjects).
        return OpenSourceProject::query()
            ->where('status', 'approved')
            ->orderBy('id')
            ->get()
            ->map(function (OpenSourceProject $project) use ($locale) {
                $title = (string) $project->localized('title');

                $body = $this->section($this->phrase('project.about', $locale, [':title' => $title]), [
                    (string) $project->localized('caption'),
                    $this->flatten($project->localized('description')),
                ]);

                $body .= $this->section($this->phrase('project.details', $locale, [':title' => $title]), [
                    $this->phrase('project.details_body', $locale, [
                        ':title' => $title,
                        ':category' => (string) ($project->category ?? ''),
                        ':license' => (string) ($project->license ?? ''),
                        ':version' => (string) ($project->version ?? ''),
                        ':format' => (string) ($project->format ?? ''),
                    ]),
                ]);

                $body .= $this->section($this->phrase('project.highlights', $locale, [':title' => $title]), [
                    $this->flatten($project->localized('highlights')),
                ]);

                $body .= $this->section($this->phrase('project.includes', $locale, [':title' => $title]), [
                    $this->flatten($project->localized('includes')),
                ]);

                return $this->document(
                    sourceKey: "db:project:{$project->id}:{$locale}",
                    locale: $locale,
                    audience: 'public',
                    category: 'proyek',
                    title: $title,
                    // Not projects.show: that page still renders from the mock
                    // projectDetail.data.ts and falls back to its first entry for an
                    // unknown slug, so it would link to the wrong project. /research is
                    // the real, database-backed listing.
                    url: $this->path('research'),
                    keywords: array_filter([$project->category, $project->listing_type]),
                    body: $body,
                );
            })->all();
    }

    /** @return array<int, array<string, mixed>> */
    private function pageSectionDocuments(string $locale): array
    {
        $documents = [];

        foreach (PageSection::query()->orderBy('page_name')->get()->groupBy('page_name') as $pageName => $rows) {
            $values = $this->resolvePageSectionLocale($rows->pluck('content', 'section_key')->all(), $locale);
            $body = '';

            foreach ($values as $sectionKey => $content) {
                if (! $this->isProse((string) $content)) {
                    continue;
                }

                $body .= $this->section(Str::headline((string) $sectionKey), [(string) $content]);
            }

            if (trim($body) === '') {
                continue;
            }

            $documents[] = $this->document(
                sourceKey: "db:page:{$pageName}:{$locale}",
                locale: $locale,
                audience: 'public',
                category: 'profil',
                title: $this->phrase('page.title', $locale, [':page' => Str::headline((string) $pageName)]),
                url: '/',
                keywords: [],
                body: $body,
            );
        }

        return $documents;
    }

    /**
     * page_sections has no `_en` columns: English lives in sibling rows keyed
     * `<section_key>_en`, and the table is unique on (page_name, section_key). Same
     * overlay rule as LandingPageController::landingContentForLocale — English overlays
     * the base set rather than replacing it, so locale-independent rows survive.
     *
     * @param  array<string, mixed>  $rows
     * @return array<string, mixed>
     */
    private function resolvePageSectionLocale(array $rows, string $locale): array
    {
        $base = array_filter($rows, fn ($value, string $key): bool => ! str_ends_with($key, '_en'), ARRAY_FILTER_USE_BOTH);

        if ($locale !== 'en') {
            return $base;
        }

        foreach ($rows as $key => $value) {
            if (str_ends_with($key, '_en')) {
                $base[substr($key, 0, -3)] = $value;
            }
        }

        return $base;
    }

    /**
     * CMS rows hold image paths, gradient stops, phone numbers and button labels next to
     * real copy. Embedding '#0a3d7a' or '/assets/hero.webp' just adds noise that competes
     * with genuine chunks at retrieval time.
     */
    private function isProse(string $value): bool
    {
        $value = trim($value);

        return mb_strlen($value) >= 40
            && ! str_starts_with($value, 'http')
            && ! str_starts_with($value, '/')
            && preg_match('/^#[0-9a-f]{3,8}$/i', $value) !== 1;
    }

    /** @return array<int, array<string, mixed>> */
    private function teamDocuments(string $locale): array
    {
        // LabTeamPerson does not use HasEnglishOverlay — it predates the trait and keeps
        // role_id/role_en as a pair, with a single-language bio. This mirrors the read
        // already done in LandingPageController.
        return LabTeamPerson::query()
            ->where('is_active', true)
            ->orderBy('id')
            ->get()
            ->map(function (LabTeamPerson $person) use ($locale) {
                $name = (string) $person->name_full;
                $role = $locale === 'en' && $person->role_en ? $person->role_en : $person->role_id;

                $body = $this->section($this->phrase('team.role', $locale, [':name' => $name]), [
                    $this->phrase('team.role_body', $locale, [':name' => $name, ':role' => (string) $role]),
                ]);

                $body .= $this->section($this->phrase('team.profile', $locale, [':name' => $name]), [
                    (string) ($person->bio ?? ''),
                ]);

                $body .= $this->section($this->phrase('team.expertise', $locale, [':name' => $name]), [
                    $person->expertise
                        ? $this->phrase('team.expertise_body', $locale, [
                            ':name' => $name,
                            ':list' => $this->flatten($person->expertise, ', '),
                        ])
                        : '',
                ]);

                return $this->document(
                    sourceKey: "db:team:{$person->id}:{$locale}",
                    locale: $locale,
                    audience: 'public',
                    category: 'profil',
                    title: $name,
                    url: $this->path('team.show', $person->slug),
                    keywords: array_filter([$person->role_id, $person->role_en]),
                    body: $body,
                );
            })->all();
    }

    // ---------------------------------------------------------------------
    // Document assembly
    // ---------------------------------------------------------------------

    /**
     * @param  array<int, string>  $keywords
     * @return array<string, mixed>
     */
    private function document(
        string $sourceKey,
        string $locale,
        string $audience,
        string $category,
        string $title,
        ?string $url,
        array $keywords,
        string $body,
    ): array {
        $keywords = array_values(array_unique(array_filter(array_map('trim', $keywords))));
        $body = trim($body);

        return [
            'source_key' => $sourceKey,
            // Everything that can change the embedded text feeds the hash, so a price
            // edit reindexes and a no-op run costs nothing.
            'source_hash' => hash('sha256', implode("\x1f", [$title, $audience, $category, (string) $url, implode(',', $keywords), $body])),
            'locale' => $locale,
            'audience' => $audience,
            'category' => $category,
            'title' => $title,
            'url' => $url,
            'keywords' => $keywords,
            'body' => $body,
        ];
    }

    /**
     * @param  array<string, mixed>  $document
     */
    private function isUnchanged(array $document): bool
    {
        return KnowledgeChunk::query()
            ->where('source_key', $document['source_key'])
            ->where('source_hash', $document['source_hash'])
            ->exists();
    }

    /**
     * Split a document into embeddable chunks: one per level-two heading, with anything
     * before the first heading kept as a headingless chunk.
     *
     * @param  array<string, mixed>  $document
     * @return array<int, array{heading: ?string, content: string, embed_text: string}>
     */
    private function chunk(array $document): array
    {
        $parts = preg_split('/^##[ \t]+(.+?)[ \t]*$/m', $document['body'], -1, PREG_SPLIT_DELIM_CAPTURE);
        $sections = [];

        $preamble = trim(array_shift($parts) ?? '');

        if ($preamble !== '') {
            $sections[] = ['heading' => null, 'content' => $preamble];
        }

        foreach (array_chunk($parts, 2) as $pair) {
            $content = trim($pair[1] ?? '');

            if ($content === '') {
                continue;
            }

            foreach ($this->splitOversized($content) as $index => $piece) {
                $sections[] = [
                    'heading' => $index === 0 ? $pair[0] : sprintf('%s (%d)', $pair[0], $index + 1),
                    'content' => $piece,
                ];
            }
        }

        return array_map(fn (array $section): array => [
            ...$section,
            'embed_text' => $this->embedText($document, $section['heading'], $section['content']),
        ], $sections);
    }

    /**
     * @return array<int, string>
     */
    private function splitOversized(string $content): array
    {
        if (mb_strlen($content) <= self::MAX_SECTION_CHARS) {
            return [$content];
        }

        $pieces = [];
        $current = '';

        foreach (preg_split('/\n{2,}/', $content) ?: [] as $paragraph) {
            $paragraph = trim($paragraph);

            if ($paragraph === '') {
                continue;
            }

            if ($current !== '' && mb_strlen($current) + mb_strlen($paragraph) + 2 > self::MAX_SECTION_CHARS) {
                $pieces[] = $current;
                $current = '';
            }

            $current = $current === '' ? $paragraph : "{$current}\n\n{$paragraph}";
        }

        if ($current !== '') {
            $pieces[] = $current;
        }

        // A single paragraph longer than the ceiling still has to be cut, or the model
        // would drop its tail without saying so.
        return collect($pieces)
            ->flatMap(fn (string $piece): array => mb_strlen($piece) <= self::MAX_SECTION_CHARS
                ? [$piece]
                : mb_str_split($piece, self::MAX_SECTION_CHARS))
            ->all();
    }

    /**
     * The exact text sent to the embedding model — see FORMAT-KNOWLEDGE.md.
     *
     * The title is included because a chunk loses its subject without it: "cost is
     * calculated per gram" cannot be told apart between the printing and the scanning
     * service until the title travels with it.
     *
     * @param  array<string, mixed>  $document
     */
    private function embedText(array $document, ?string $heading, string $content): string
    {
        $header = $heading === null
            ? $document['title']
            : "{$document['title']} — {$heading}";

        return implode("\n", array_filter([
            $header,
            $document['keywords'] === [] ? '' : implode(', ', $document['keywords']),
            $content,
        ]));
    }

    /**
     * @param  array<int, string>  $paragraphs
     */
    private function section(string $heading, array $paragraphs): string
    {
        $body = trim(implode("\n\n", array_filter(array_map('trim', $paragraphs))));

        return $body === '' ? '' : "## {$heading}\n\n{$body}\n\n";
    }

    // ---------------------------------------------------------------------
    // Formatting helpers
    // ---------------------------------------------------------------------

    /**
     * Relative path for a named route.
     *
     * Never absolute. `route()` builds absolute URLs from APP_URL, and the index is built by
     * an artisan command — so a stale or default APP_URL bakes `http://localhost/services/1`
     * into every chunk and the chatbot then hands that to real visitors. A relative path
     * works on whatever host serves the page, and matches the `url:` front matter the
     * markdown documents already use.
     */
    private function path(string $name, mixed $parameters = []): string
    {
        return route($name, $parameters, absolute: false);
    }

    private function rupiah(int $amount): string
    {
        return 'Rp '.number_format($amount, 0, ',', '.');
    }

    private function longDate(mixed $date, string $locale): string
    {
        return $date ? $date->locale($locale)->translatedFormat('j F Y') : '';
    }

    /** Flatten a JSON-cast column into a sentence the embedding model can read. */
    private function flatten(mixed $value, string $glue = '. '): string
    {
        if (is_array($value)) {
            $value = collect($value)->flatten()->filter(fn ($item): bool => is_scalar($item) && trim((string) $item) !== '');

            return $value->map(fn ($item): string => rtrim(trim((string) $item), '.'))->implode($glue);
        }

        return trim((string) $value);
    }

    /**
     * @param  array<string, string>  $replace
     */
    private function phrase(string $key, string $locale, array $replace = []): string
    {
        $template = self::PHRASES[$key][$locale] ?? self::PHRASES[$key]['id'] ?? $key;

        return trim(str_replace(array_keys($replace), array_values($replace), $template));
    }

    /**
     * Fixed scaffolding copy, per locale.
     *
     * Not __(): the index hash is derived from the rendered text, so sourcing this from
     * lang/*.json would make an unrelated copy edit re-embed the entire catalogue. Not
     * Indonesian-only either — the headings are half of what gets embedded, and an
     * Indonesian heading over English content is exactly the mixed-language context
     * ARSITEKTUR.md warns produces mixed-language answers.
     *
     * @var array<string, array<string, string>>
     */
    private const PHRASES = [
        'service.about' => ['id' => 'Tentang layanan :name', 'en' => 'About the :name service'],
        'service.cost' => ['id' => 'Biaya layanan :name', 'en' => 'Cost of the :name service'],
        'service.cost_body' => [
            'id' => 'Layanan :name memiliki harga dasar :price. Harga akhir ditentukan setelah tim lab meninjau berkas dan kebutuhanmu.',
            'en' => 'The :name service has a base price of :price. The final price is set after the lab team reviews your files and requirements.',
        ],
        'service.order' => ['id' => 'Cara memesan layanan :name', 'en' => 'How to order the :name service'],
        'service.order_body' => [
            'id' => 'Pemesanan layanan :name dilakukan dari halaman Pesanan di dashboard setelah masuk ke akun.',
            'en' => 'Order the :name service from the Orders page in your dashboard after signing in.',
        ],
        'service.whatsapp' => [
            'id' => 'Pertanyaan sebelum memesan bisa disampaikan lewat WhatsApp :number.',
            'en' => 'Questions before ordering can be sent via WhatsApp :number.',
        ],

        'product.about' => ['id' => 'Tentang produk :name', 'en' => 'About the :name product'],
        'product.price' => ['id' => 'Harga produk :name', 'en' => 'Price of the :name product'],
        'product.price_flat' => [
            'id' => 'Produk :name dijual dengan harga :price.',
            'en' => 'The :name product is sold at :price.',
        ],
        'product.price_range' => [
            'id' => 'Produk :name dijual pada rentang harga :min sampai :max.',
            'en' => 'The :name product is sold in a price range from :min to :max.',
        ],

        'event.about' => ['id' => 'Tentang :category :name', 'en' => 'About the :category :name'],
        'event.schedule' => ['id' => 'Jadwal dan lokasi :name', 'en' => 'Schedule and location of :name'],
        'event.schedule_body' => [
            'id' => 'Kegiatan :name berlangsung :start sampai :end di :location.',
            'en' => 'The :name event runs from :start to :end at :location.',
        ],
        'event.registration' => ['id' => 'Pendaftaran dibuka di :url.', 'en' => 'Registration is open at :url.'],

        'training.about' => ['id' => 'Tentang workshop :title', 'en' => 'About the :title workshop'],
        'training.schedule' => [
            'id' => 'Jadwal dan lokasi workshop :title',
            'en' => 'Schedule and location of the :title workshop',
        ],
        'training.schedule_body' => [
            'id' => 'Workshop :title berlangsung pada :date di :location. Tingkat :level, durasi :duration, bahasa pengantar :language. Kuota peserta :quota orang.',
            'en' => 'The :title workshop takes place on :date at :location. Level :level, duration :duration, delivered in :language. Participant quota is :quota people.',
        ],
        'training.price' => [
            'id' => 'Biaya dan pendaftaran workshop :title',
            'en' => 'Cost and registration for the :title workshop',
        ],
        'training.price_paid' => ['id' => 'Biaya pendaftaran :price.', 'en' => 'The registration fee is :price.'],
        'training.price_free' => ['id' => 'Workshop ini gratis.', 'en' => 'This workshop is free of charge.'],
        'training.price_body' => [
            'id' => 'Pendaftaran dilakukan lewat halaman detail workshop setelah masuk ke akun.',
            'en' => 'Register from the workshop detail page after signing in.',
        ],
        'training.curriculum' => [
            'id' => 'Materi yang dipelajari di workshop :title',
            'en' => 'What you will learn in the :title workshop',
        ],
        'training.instructor' => [
            'id' => 'Instruktur workshop :title',
            'en' => 'Instructor of the :title workshop',
        ],

        'publication.about' => ['id' => 'Publikasi :title', 'en' => 'Publication :title'],
        'publication.about_body' => [
            'id' => 'Publikasi berjudul ":title" ditulis oleh :author dan terbit pada :year di :journal.',
            'en' => 'The publication ":title" was written by :author and published in :year in :journal.',
        ],
        'publication.doi' => ['id' => 'DOI :doi.', 'en' => 'DOI :doi.'],
        'publication.category' => ['id' => 'Kategori :category.', 'en' => 'Category :category.'],
        'publication.open' => ['id' => 'Akses terbuka.', 'en' => 'Open access.'],
        'publication.closed' => ['id' => 'Akses terbatas.', 'en' => 'Restricted access.'],
        'publication.abstract' => ['id' => 'Abstrak publikasi :title', 'en' => 'Abstract of :title'],

        'project.about' => ['id' => 'Tentang proyek :title', 'en' => 'About the :title project'],
        'project.details' => ['id' => 'Lisensi dan versi proyek :title', 'en' => 'Licence and version of :title'],
        'project.details_body' => [
            'id' => 'Proyek :title berada di kategori :category. Lisensi :license, versi :version, format :format.',
            'en' => 'The :title project is in the :category category. Licence :license, version :version, format :format.',
        ],
        'project.highlights' => ['id' => 'Keunggulan proyek :title', 'en' => 'Highlights of the :title project'],
        'project.includes' => ['id' => 'Isi paket proyek :title', 'en' => 'What the :title project includes'],

        'page.title' => ['id' => 'Informasi halaman :page', 'en' => ':page page information'],

        'team.role' => [
            'id' => 'Peran :name di Laboratorium Teknologi Kesehatan IDIG',
            'en' => 'Role of :name at the IDIG Health Technology Laboratory',
        ],
        'team.role_body' => [
            'id' => ':name menjabat sebagai :role di Laboratorium Teknologi Kesehatan IDIG ITS.',
            'en' => ':name serves as :role at the IDIG Health Technology Laboratory, ITS.',
        ],
        'team.profile' => ['id' => 'Profil :name', 'en' => 'Profile of :name'],
        'team.expertise' => ['id' => 'Bidang keahlian :name', 'en' => 'Areas of expertise of :name'],
        'team.expertise_body' => [
            'id' => 'Bidang keahlian :name meliputi :list.',
            'en' => 'The areas of expertise of :name include :list.',
        ],
    ];
}
