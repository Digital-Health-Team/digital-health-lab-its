<?php

namespace App\Livewire\Admin\OpenSourceProject;

use App\Actions\Project\CreateOpenSourceProjectAction;
use App\Actions\Project\DeleteOpenSourceProjectAction;
use App\Actions\Project\DeleteOpenSourceProjectAttachmentAction;
use App\Actions\Project\UpdateOpenSourceProjectAction;
use App\Actions\Project\UpdateOpenSourceProjectStatusAction;
use App\DTOs\Project\OpenSourceProjectData;
use App\Models\Attachment;
use App\Models\OpenSourceProject;
use App\Models\User;
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
    public string $filterStatus = '';

    #[Url(history: true)]
    public string $filterCategory = '';

    #[Url(history: true)]
    public string $filterListingType = '';

    #[Url(history: true)]
    public string $sortBy = 'latest';

    public bool $drawerOpen = false;

    public bool $deleteModalOpen = false;

    public ?int $editingId = null;

    public ?int $deleteId = null;

    // --- FORM DATA ---
    public ?int $user_id = null;

    public string $title = '';

    public string $slug = '';

    public string $caption = '';

    public string $category = '';

    public string $listing_type = '';

    public string $cover_color = '';

    public bool $is_featured = false;

    public array $description = [''];

    public array $highlights = [''];

    public string $license = 'MIT';

    public string $version = '';

    public string $format = '';

    public array $includes = [''];

    public array $new_files = [];

    public $existing_files = [];

    protected function rules()
    {
        $slugRule = $this->editingId
            ? 'nullable|string|max:255|unique:open_source_projects,slug,'.$this->editingId
            : 'nullable|string|max:255|unique:open_source_projects,slug';

        return [
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'slug' => $slugRule,
            'caption' => 'nullable|string|max:500',
            'category' => 'required|string|max:255',
            'listing_type' => 'required|string|max:255',
            'cover_color' => 'nullable|string|max:255',
            'is_featured' => 'boolean',
            'description.*' => 'nullable|string',
            'highlights.*' => 'nullable|string',
            'license' => 'nullable|string|max:50',
            'version' => 'nullable|string|max:50',
            'format' => 'nullable|string|max:255',
            'includes.*' => 'nullable|string',
            'new_files.*' => 'file|max:20480',
        ];
    }

    public function updated($property)
    {
        if (in_array($property, ['search', 'filterStatus', 'filterCategory', 'filterListingType', 'sortBy'])) {
            $this->resetPage();
        }
    }

    public function updatedTitle($value)
    {
        if (! $this->slug) {
            $this->slug = \Illuminate\Support\Str::slug($value);
        }
    }

    public function clearFilters()
    {
        $this->reset(['search', 'filterStatus', 'filterCategory', 'filterListingType', 'sortBy']);
        $this->resetPage();
    }

    public function create()
    {
        $this->reset([
            'user_id', 'title', 'slug', 'caption', 'category', 'listing_type',
            'cover_color', 'is_featured', 'license', 'version', 'format',
            'new_files', 'existing_files', 'editingId',
        ]);
        $this->description = [''];
        $this->highlights = [''];
        $this->includes = [''];
        $this->license = 'MIT';
        $this->drawerOpen = true;
    }

    public function edit(OpenSourceProject $project)
    {
        $this->editingId = $project->id;
        $this->user_id = $project->user_id;
        $this->title = $project->title;
        $this->slug = $project->slug ?? '';
        $this->caption = $project->caption ?? '';
        $this->category = $project->category;
        $this->listing_type = $project->listing_type ?? '';
        $this->cover_color = $project->cover_color ?? '';
        $this->is_featured = (bool) $project->is_featured;
        $this->description = $project->description ?: [''];
        $this->highlights = $project->highlights ?: [''];
        $this->license = $project->license ?? 'MIT';
        $this->version = $project->version ?? '';
        $this->format = $project->format ?? '';
        $this->includes = $project->includes ?: [''];
        $this->new_files = [];
        $this->existing_files = $project->attachments()->orderBy('sort_order')->get();
        $this->drawerOpen = true;
    }

    // --- Dynamic list helpers ---

    public function addDescriptionItem()
    {
        $this->description[] = '';
    }

    public function removeDescriptionItem(int $index)
    {
        unset($this->description[$index]);
        $this->description = array_values($this->description);
        if (empty($this->description)) {
            $this->description = [''];
        }
    }

    public function addHighlightItem()
    {
        $this->highlights[] = '';
    }

    public function removeHighlightItem(int $index)
    {
        unset($this->highlights[$index]);
        $this->highlights = array_values($this->highlights);
        if (empty($this->highlights)) {
            $this->highlights = [''];
        }
    }

    public function addIncludesItem()
    {
        $this->includes[] = '';
    }

    public function removeIncludesItem(int $index)
    {
        unset($this->includes[$index]);
        $this->includes = array_values($this->includes);
        if (empty($this->includes)) {
            $this->includes = [''];
        }
    }

    public function removeNewFile($index)
    {
        unset($this->new_files[$index]);
        $this->new_files = array_values($this->new_files);
    }

    public function removeExistingFile($attachmentId)
    {
        $attachment = Attachment::find($attachmentId);
        if ($attachment && $attachment->attachable_type === OpenSourceProject::class) {
            app(DeleteOpenSourceProjectAttachmentAction::class)->execute($attachment);
            $this->existing_files = OpenSourceProject::find($this->editingId)->attachments()->orderBy('sort_order')->get();
            $this->success(__('File deleted successfully.'));
        }
    }

    public function save()
    {
        $this->validate();

        $description = array_values(array_filter($this->description, fn ($v) => trim($v) !== ''));
        $highlights = array_values(array_filter($this->highlights, fn ($v) => trim($v) !== ''));
        $includes = array_values(array_filter($this->includes, fn ($v) => trim($v) !== ''));

        $dto = new OpenSourceProjectData(
            user_id: (int) $this->user_id,
            title: $this->title,
            category: $this->category,
            new_files: $this->new_files,
            status: $this->editingId ? OpenSourceProject::find($this->editingId)->status : 'approved',
            slug: $this->slug ?: null,
            caption: $this->caption ?: null,
            listing_type: $this->listing_type ?: null,
            description: $description,
            highlights: $highlights,
            cover_color: $this->cover_color ?: null,
            is_featured: $this->is_featured,
            license: $this->license,
            version: $this->version ?: null,
            format: $this->format ?: null,
            includes: $includes,
        );

        if ($this->editingId) {
            app(UpdateOpenSourceProjectAction::class)->execute(OpenSourceProject::find($this->editingId), $dto);
            $this->success(__('Project updated successfully.'));
        } else {
            app(CreateOpenSourceProjectAction::class)->execute($dto);
            $this->success(__('Project created successfully.'));
        }

        $this->drawerOpen = false;
        $this->reset(['new_files']);
    }

    public function updateStatus(int $id, string $status)
    {
        app(UpdateOpenSourceProjectStatusAction::class)->execute(OpenSourceProject::find($id), $status);
        $this->success(__('Project status updated to :status', ['status' => strtoupper($status)]));
    }

    public function confirmDelete(int $id)
    {
        $this->deleteId = $id;
        $this->deleteModalOpen = true;
    }

    public function deleteRecord()
    {
        try {
            app(DeleteOpenSourceProjectAction::class)->execute(OpenSourceProject::find($this->deleteId));
            $this->success(__('Project deleted successfully.'));
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
        $this->deleteModalOpen = false;
    }

    public function render()
    {
        $query = OpenSourceProject::with(['user.profile', 'validator', 'attachments']);

        if ($this->search) {
            $query->where('title', 'like', "%{$this->search}%")
                ->orWhereHas('user.profile', fn ($q) => $q->where('full_name', 'like', "%{$this->search}%"));
        }
        if ($this->filterStatus !== '') {
            $query->where('status', $this->filterStatus);
        }
        if ($this->filterCategory !== '') {
            $query->where('category', $this->filterCategory);
        }
        if ($this->filterListingType !== '') {
            $query->where('listing_type', $this->filterListingType);
        }

        match ($this->sortBy) {
            'oldest' => $query->oldest('id'),
            default => $query->latest('id'),
        };

        $availableUsers = User::with('profile')
            ->whereHas('role', fn ($q) => $q->whereIn('name', ['mahasiswa', 'user_publik']))
            ->get()
            ->map(fn ($u) => ['id' => $u->id, 'name' => ($u->profile?->full_name ?? $u->email)]);

        $categories = [
            ['id' => '3d_model', 'name' => '3D Model'],
            ['id' => 'iot_system', 'name' => 'IoT System'],
            ['id' => 'medical_device', 'name' => 'Medical Device'],
            ['id' => 'software', 'name' => 'Software / App'],
        ];

        $listingTypes = [
            ['id' => 'journals', 'name' => 'Journals'],
            ['id' => 'products', 'name' => 'Products'],
            ['id' => 'powerpoint', 'name' => 'Powerpoint Presentations'],
            ['id' => 'downloadable', 'name' => 'Downloadable'],
            ['id' => 'read_only', 'name' => 'Read-Only'],
        ];

        $licenses = [
            ['id' => 'MIT', 'name' => 'MIT'],
            ['id' => 'Apache 2.0', 'name' => 'Apache 2.0'],
            ['id' => 'CC BY 4.0', 'name' => 'CC BY 4.0'],
            ['id' => 'GPL-3.0', 'name' => 'GPL-3.0'],
        ];

        return view('livewire.admin.open-source-project.index', [
            'projects' => $query->paginate(10),
            'availableUsers' => $availableUsers,
            'categories' => $categories,
            'listingTypes' => $listingTypes,
            'licenses' => $licenses,
        ]);
    }
}
