<?php

namespace App\Livewire\Admin\OpenSourceProject;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Url;
use App\Models\OpenSourceProject;
use App\Models\User;
use App\Models\Attachment;
use App\DTOs\Project\OpenSourceProjectData;
use App\Actions\Project\CreateOpenSourceProjectAction;
use App\Actions\Project\UpdateOpenSourceProjectAction;
use App\Actions\Project\DeleteOpenSourceProjectAction;
use App\Actions\Project\DeleteOpenSourceProjectAttachmentAction;
use App\Actions\Project\UpdateOpenSourceProjectStatusAction;
use Mary\Traits\Toast;

class Index extends Component
{
    use WithPagination, WithFileUploads, Toast;

    #[Url(history: true)] public string $search = '';
    #[Url(history: true)] public string $filterStatus = '';
    #[Url(history: true)] public string $filterCategory = '';
    #[Url(history: true)] public string $sortBy = 'latest';

    public bool $drawerOpen = false;
    public bool $deleteModalOpen = false;

    public ?int $editingId = null;
    public ?int $deleteId = null;

    // --- FORM DATA ---
    public ?int $user_id = null;
    public string $title = '';
    public string $category = '';

    public array $new_files = [];
    public $existing_files = [];

    protected function rules()
    {
        return [
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'new_files.*' => 'file|max:10240', // Max 10MB per file
        ];
    }

    public function updated($property)
    {
        if (in_array($property, ['search', 'filterStatus', 'filterCategory', 'sortBy'])) {
            $this->resetPage();
        }
    }

    public function clearFilters()
    {
        $this->reset(['search', 'filterStatus', 'filterCategory', 'sortBy']);
        $this->resetPage();
    }

    public function create()
    {
        $this->reset(['user_id', 'title', 'category', 'new_files', 'existing_files', 'editingId']);
        $this->drawerOpen = true;
    }

    public function edit(OpenSourceProject $project)
    {
        $this->editingId = $project->id;
        $this->user_id = $project->user_id;
        $this->title = $project->title;
        $this->category = $project->category;

        $this->new_files = [];
        $this->existing_files = $project->attachments()->orderBy('sort_order')->get();

        $this->drawerOpen = true;
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

        $dto = new OpenSourceProjectData(
            user_id: (int) $this->user_id,
            title: $this->title,
            category: $this->category,
            new_files: $this->new_files,
            status: $this->editingId ? OpenSourceProject::find($this->editingId)->status : 'approved' // Jika admin yg buat, otomatis approved
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
                  ->orWhereHas('user.profile', fn($q) => $q->where('full_name', 'like', "%{$this->search}%"));
        }
        if ($this->filterStatus !== '') {
            $query->where('status', $this->filterStatus);
        }
        if ($this->filterCategory !== '') {
            $query->where('category', $this->filterCategory);
        }

        match ($this->sortBy) {
            'oldest' => $query->oldest('id'),
            default => $query->latest('id'),
        };

        // Fetch users for dropdown (Mahasiswa & Publik)
        $availableUsers = User::with('profile')
            ->whereHas('role', fn($q) => $q->whereIn('name', ['mahasiswa', 'user_publik']))
            ->get()
            ->map(fn($u) => ['id' => $u->id, 'name' => ($u->profile?->full_name ?? $u->email)]);

        $categories = [
            ['id' => '3d_model', 'name' => '3D Model'],
            ['id' => 'iot_system', 'name' => 'IoT System'],
            ['id' => 'medical_device', 'name' => 'Medical Device'],
            ['id' => 'software', 'name' => 'Software / App'],
        ];

        return view('livewire.admin.open-source-project.index', [
            'projects' => $query->paginate(10),
            'availableUsers' => $availableUsers,
            'categories' => $categories
        ]);
    }
}
