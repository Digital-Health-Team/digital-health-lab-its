<?php

namespace App\Livewire\Admin\Publication;

use App\Actions\Publication\CreatePublicationAction;
use App\Actions\Publication\DeletePublicationAction;
use App\Actions\Publication\UpdatePublicationAction;
use App\DTOs\Publication\PublicationData;
use App\Models\Publication;
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
            $this->slug = \Illuminate\Support\Str::slug($value);
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
        ]);
        $this->description = [''];
        $this->keywords = [''];
        $this->drawerOpen = true;
    }

    public function edit(Publication $publication): void
    {
        $this->editingId = $publication->id;
        $this->title = $publication->title;
        $this->slug = $publication->slug;
        $this->author = $publication->author;
        $this->category = $publication->category;
        $this->abstract = $publication->abstract ?? '';
        $this->description = $publication->description ?: [''];
        $this->keywords = $publication->keywords ?: [''];
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

    public function save(): void
    {
        $this->validate();

        $description = array_values(array_filter($this->description, fn ($v) => trim($v) !== ''));
        $keywords = array_values(array_filter($this->keywords, fn ($v) => trim($v) !== ''));

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
