<?php

namespace App\Livewire\Admin\Project;

use App\Actions\Project\CreateProjectAction;
use App\Actions\Project\UpdateProjectAction;
use App\DTOs\Project\ProjectData;
use App\Models\Project;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;
use Illuminate\Support\Carbon;

#[Layout('layouts.app')]
#[Title('Project Management')]
class Index extends Component
{
    use WithPagination, Toast;

    public string $search = '';
    public bool $modalOpen = false;
    public bool $deleteModalOpen = false;
    public ?int $editingProjectId = null;
    public ?int $projectToDeleteId = null;

    // Form Data
    public array $name = ['id' => '', 'en' => '']; // Initialize as array
    public array $description = ['id' => '', 'en' => ''];
    public string $deadline_global = '';
    public string $status = 'active';

    // Reset pagination when search changes
    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function rules()
    {
        return [
            'name.id' => 'required_without:name.en|string|max:255',
            'name.en' => 'required_without:name.id|string|max:255',
            'deadline_global' => 'nullable|date',
            'status' => 'required|in:active,on_hold,completed',
        ];
    }

    public function create()
    {
        $this->reset(['editingProjectId', 'name', 'description', 'deadline_global', 'status']);

        // Reset array structure explicitly to avoid "Cannot access offset on string" error
        $this->name = ['id' => '', 'en' => ''];
        $this->description = ['id' => '', 'en' => ''];
        $this->status = 'active';

        $this->modalOpen = true;
    }

    public function edit(Project $project)
    {
        $this->editingProjectId = $project->id;

        // FIX: Ambil data JSON dan pastikan formatnya array
        // Jika menggunakan Spatie Translatable, getTranslations() mengembalikan array ['en' => '...', 'id' => '...']
        // Jika manual JSON cast, akses properti langsung.

        // Versi Aman (Manual Check):
        $n = $project->name;
        $this->name = [
            'id' => is_array($n) ? ($n['id'] ?? '') : $n,
            'en' => is_array($n) ? ($n['en'] ?? '') : '',
        ];

        $d = $project->description;
        $this->description = [
            'id' => is_array($d) ? ($d['id'] ?? '') : $d,
            'en' => is_array($d) ? ($d['en'] ?? '') : '',
        ];

        $this->deadline_global = $project->deadline_global
            ? Carbon::parse($project->deadline_global)->format('Y-m-d\TH:i')
            : '';

        $this->status = $project->status;
        $this->modalOpen = true;
    }

    public function save()
    {
        $this->validate();

        $data = new ProjectData(
            name: $this->name,
            description: $this->description,
            deadline_global: $this->deadline_global,
            status: $this->status,
        );

        if ($this->editingProjectId) {
            $project = Project::findOrFail($this->editingProjectId);
            app(UpdateProjectAction::class)->execute($project, $data);
            $this->success('Project updated successfully.');
        } else {
            app(CreateProjectAction::class)->execute($data);
            $this->success('Project created successfully.');
        }

        $this->modalOpen = false;
    }

    public function confirmDelete($id)
    {
        $this->projectToDeleteId = $id;
        $this->deleteModalOpen = true;
    }

    public function delete()
    {
        if ($this->projectToDeleteId) {
            Project::destroy($this->projectToDeleteId);
            $this->success('Project deleted successfully.');
        }
        $this->deleteModalOpen = false;
    }

    public function render()
    {
        $projects = Project::query()
            ->with('creator')
            ->when($this->search, function ($q) {
                // Search inside JSON column
                $q->where('name->id', 'like', "%{$this->search}%")
                    ->orWhere('name->en', 'like', "%{$this->search}%");
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.project.index', [
            'projects' => $projects
        ]);
    }
}
