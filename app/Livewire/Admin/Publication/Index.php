<?php

namespace App\Livewire\Admin\Publication;

use App\Actions\Publication\CreatePublicationAction;
use App\Actions\Publication\DeletePublicationAction;
use App\Actions\Publication\UpdatePublicationAction;
use App\DTOs\Publication\PublicationData;
use App\Models\Publication;
use Illuminate\Support\Str;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class Index extends Component
{
    use Toast, WithFileUploads, WithPagination;

    #[Url(history: true)]
    public string $search = '';

    #[Url(history: true)]
    public string $filterCategory = '';

    #[Url(history: true)]
    public string $sortBy = 'published_at_desc';

    public bool $drawerOpen = false;

    public bool $deleteModalOpen = false;

    public ?int $editingId = null;

    public ?int $deleteId = null;

    // ── Form fields ──────────────────────────────────────────
    public string $title = '';

    public string $slug = '';

    public string $author = '';

    public string $category = '';

    public string $abstract = '';

    // ── English overlay ───────────────────────────────────────
    public string $title_en = '';

    public string $abstract_en = '';

    public array $description_en = [''];

    public array $keywords_en = [''];

    public array $description = [''];

    public array $keywords = [''];

    public string $doi = '';

    public string $journal = '';

    public string $pmid = '';

    public bool $is_free_access = false;

    public bool $is_featured = false;

    public string $published_at = '';

    public $thumbnail_file = null;

    public $pdf_file = null;

    public ?string $existingThumbnailUrl = null;

    public ?string $existingPdfPath = null;

    protected function rules(): array
    {
        $slugRule = $this->editingId
            ? 'nullable|string|max:255|unique:publications,slug,'.$this->editingId
            : 'nullable|string|max:255|unique:publications,slug';

        return [
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'category' => 'required|in:Journals,Papers,Research',
            'slug' => $slugRule,
            'abstract' => 'nullable|string',
            'description.*' => 'nullable|string',
            'keywords.*' => 'nullable|string',
            'title_en' => 'nullable|string|max:255',
            'abstract_en' => 'nullable|string',
            'description_en.*' => 'nullable|string',
            'keywords_en.*' => 'nullable|string',
            'doi' => 'nullable|string|max:255',
            'journal' => 'nullable|string|max:255',
            'pmid' => 'nullable|string|max:255',
            'published_at' => 'nullable|date',
            'thumbnail_file' => 'nullable|image|max:4096',
            'pdf_file' => 'nullable|mimes:pdf|max:51200',
        ];
    }

    public function updated($property): void
    {
        if (in_array($property, ['search', 'filterCategory', 'sortBy'])) {
            $this->resetPage();
        }
    }

    public function updatedTitle(string $value): void
    {
        if (! $this->slug) {
            $this->slug = Str::slug($value);
        }
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'filterCategory', 'sortBy']);
        $this->sortBy = 'published_at_desc';
        $this->resetPage();
    }

    public function create(): void
    {
        $this->reset([
            'title', 'slug', 'author', 'category', 'abstract',
            'doi', 'journal', 'pmid', 'is_free_access', 'is_featured',
            'published_at', 'thumbnail_file', 'pdf_file',
            'existingThumbnailUrl', 'existingPdfPath', 'editingId',
            'title_en', 'abstract_en',
        ]);
        $this->description = [''];
        $this->keywords = [''];
        $this->description_en = [''];
        $this->keywords_en = [''];
        $this->drawerOpen = true;
    }

    public function edit(Publication $publication): void
    {
        $this->editingId = $publication->id;
        $this->title = $publication->title;
        $this->slug = $publication->slug;
        $this->author = $publication->author;
        $this->category = $publication->category;
        // Raw columns on purpose — see the note in Admin\Product\Index::edit().
        $this->abstract = $publication->abstract ?? '';
        $this->description = $publication->description ?: [''];
        $this->keywords = $publication->keywords ?: [''];
        $this->title_en = $publication->title_en ?? '';
        $this->abstract_en = $publication->abstract_en ?? '';
        $this->description_en = $publication->description_en ?: [''];
        $this->keywords_en = $publication->keywords_en ?: [''];
        $this->doi = $publication->doi ?? '';
        $this->journal = $publication->journal ?? '';
        $this->pmid = $publication->pmid ?? '';
        $this->is_free_access = (bool) $publication->is_free_access;
        $this->is_featured = (bool) $publication->is_featured;
        $this->published_at = $publication->published_at?->format('Y-m-d') ?? '';
        $this->thumbnail_file = null;
        $this->pdf_file = null;
        $this->existingThumbnailUrl = $publication->thumbnail_url;
        $this->existingPdfPath = $publication->pdf_path ? basename($publication->pdf_path) : null;
        $this->drawerOpen = true;
    }

    // ── Dynamic array helpers ─────────────────────────────────

    public function addDescriptionItem(): void
    {
        $this->description[] = '';
    }

    public function removeDescriptionItem(int $index): void
    {
        unset($this->description[$index]);
        $this->description = array_values($this->description);
        if (empty($this->description)) {
            $this->description = [''];
        }
    }

    public function addKeywordItem(): void
    {
        $this->keywords[] = '';
    }

    public function removeKeywordItem(int $index): void
    {
        unset($this->keywords[$index]);
        $this->keywords = array_values($this->keywords);
        if (empty($this->keywords)) {
            $this->keywords = [''];
        }
    }

    /** Repeater rows for the English overlay lists. Whitelisted — the field name comes from the browser. */
    private const EN_LISTS = ['description_en', 'keywords_en'];

    public function addEnItem(string $field): void
    {
        abort_unless(in_array($field, self::EN_LISTS, true), 400);

        $this->{$field}[] = '';
    }

    public function removeEnItem(string $field, int $index): void
    {
        abort_unless(in_array($field, self::EN_LISTS, true), 400);

        unset($this->{$field}[$index]);
        $this->{$field} = array_values($this->{$field}) ?: [''];
    }

    public function save(): void
    {
        $this->validate();

        $trim = fn (array $rows) => array_values(array_filter($rows, fn ($v) => trim((string) $v) !== ''));

        $description = $trim($this->description);
        $keywords = $trim($this->keywords);

        $dto = new PublicationData(
            title: $this->title,
            author: $this->author,
            category: $this->category,
            slug: $this->slug ?: null,
            abstract: $this->abstract ?: null,
            description: $description,
            keywords: $keywords,
            doi: $this->doi ?: null,
            journal: $this->journal ?: null,
            pmid: $this->pmid ?: null,
            is_free_access: $this->is_free_access,
            is_featured: $this->is_featured,
            published_at: $this->published_at ?: null,
            thumbnail_file: $this->thumbnail_file,
            pdf_file: $this->pdf_file,
            title_en: $this->title_en ?: null,
            abstract_en: $this->abstract_en ?: null,
            description_en: $trim($this->description_en),
            keywords_en: $trim($this->keywords_en),
        );

        if ($this->editingId) {
            app(UpdatePublicationAction::class)->execute(Publication::find($this->editingId), $dto);
            $this->success(__('Publication updated successfully.'));
        } else {
            app(CreatePublicationAction::class)->execute($dto);
            $this->success(__('Publication created successfully.'));
        }

        $this->drawerOpen = false;
        $this->reset(['thumbnail_file', 'pdf_file']);
    }

    public function confirmDelete(int $id): void
    {
        $this->deleteId = $id;
        $this->deleteModalOpen = true;
    }

    public function deleteRecord(): void
    {
        try {
            app(DeletePublicationAction::class)->execute(Publication::find($this->deleteId));
            $this->success(__('Publication deleted successfully.'));
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
        $this->deleteModalOpen = false;
    }

    public function render()
    {
        $query = Publication::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', "%{$this->search}%")
                    ->orWhere('author', 'like', "%{$this->search}%")
                    ->orWhere('journal', 'like', "%{$this->search}%");
            });
        }

        if ($this->filterCategory !== '') {
            $query->where('category', $this->filterCategory);
        }

        match ($this->sortBy) {
            'published_at_asc' => $query->oldest('published_at'),
            'views_desc' => $query->orderByDesc('view_count'),
            'title_asc' => $query->orderBy('title'),
            default => $query->latest('published_at'),
        };

        $categories = [
            ['id' => 'Journals',  'name' => 'Journals'],
            ['id' => 'Papers',    'name' => 'Papers'],
            ['id' => 'Research',  'name' => 'Research'],
        ];

        $sortOptions = [
            ['id' => 'published_at_desc', 'name' => __('Newest First')],
            ['id' => 'published_at_asc',  'name' => __('Oldest First')],
            ['id' => 'views_desc',        'name' => __('Most Viewed')],
            ['id' => 'title_asc',         'name' => __('Title A–Z')],
        ];

        return view('livewire.admin.publications.index', [
            'publications' => $query->paginate(10),
            'categories' => $categories,
            'sortOptions' => $sortOptions,
        ]);
    }
}
