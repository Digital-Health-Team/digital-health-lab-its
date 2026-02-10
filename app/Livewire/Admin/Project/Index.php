<?php

namespace App\Livewire\Admin\Project;

use App\Actions\Project\CreateProjectAction;
use App\Actions\Project\DeleteProjectAction;
use App\Actions\Project\UpdateProjectAction;
use App\DTOs\Project\ProjectData;
use App\Models\Project;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

#[Layout('layouts.app')]
#[Title('Project Management')]
class Index extends Component
{
    use WithPagination, Toast;

    public string $search = '';

    public bool $modalOpen = false;
    public ?int $editingProjectId = null;

    // Properti Form
    public array $name = ['id' => '', 'en' => ''];
    public array $description = ['id' => '', 'en' => ''];

    public string $deadline_global = '';
    public string $status = 'active';
    public bool $deleteModalOpen = false;
    public ?int $projectToDeleteId = null;

    // --- RULES VALIDASI BARU ---
    public function rules()
    {
        return [
            // Kita gunakan 'nullable' untuk keduanya,
            // Nanti kita cek manual di method save() apakah minimal salah satu terisi.
            'name.id' => 'nullable|string',
            'name.en' => 'nullable|string',

            'description.id' => 'nullable|string',
            'description.en' => 'nullable|string',

            'deadline_global' => 'required|date',
            'status' => 'required',
        ];
    }

    public function create()
    {
        $this->reset(['editingProjectId', 'deadline_global', 'status']);
        $this->name = ['id' => '', 'en' => ''];
        $this->description = ['id' => '', 'en' => ''];
        $this->modalOpen = true;
    }

    public function edit(Project $project)
    {
        $this->editingProjectId = $project->id;
        $this->name = $project->getTranslations('name');
        $this->description = $project->getTranslations('description');
        $this->deadline_global = $project->deadline_global->format('Y-m-d\TH:i');
        $this->status = $project->status;
        $this->modalOpen = true;
    }

    public function save(CreateProjectAction $createAction, UpdateProjectAction $updateAction)
    {
        // 1. Validasi Dasar (Format tanggal, status, dll)
        $this->validate();

        // 2. Validasi Manual: Minimal satu bahasa harus diisi
        // Cek Nama
        if (empty($this->name['id']) && empty($this->name['en'])) {
            $this->addError('name.id', __('Please fill in the project name in at least one language.'));
            return; // Stop eksekusi
        }

        // Cek Deskripsi
        if (empty($this->description['id']) && empty($this->description['en'])) {
            $this->addError('description.id', __('Please fill in the description in at least one language.'));
            return; // Stop eksekusi
        }

        $data = new ProjectData(
            name: $this->name,
            description: $this->description,
            deadline_global: $this->deadline_global,
            status: $this->status
        );

        // Logic Create/Update tetap sama,
        // Action Class yang akan mengurus "Translate Otomatis"-nya
        if ($this->editingProjectId) {
            $project = Project::findOrFail($this->editingProjectId);
            // Gunakan app() untuk inject dependensi service di dalam action
            app(UpdateProjectAction::class)->execute($project, $data);
            $message = __('Project updated successfully (Auto-translated)');
        } else {
            app(CreateProjectAction::class)->execute($data);
            $message = __('Project created successfully (Auto-translated)');
        }

        $this->success($message);
        $this->modalOpen = false;
    }

    // 1. TAHAP KONFIRMASI (Dipanggil saat klik tombol sampah)
    public function confirmDelete($id)
    {
        $this->projectToDeleteId = $id;
        $this->deleteModalOpen = true;
    }

    // 2. TAHAP EKSEKUSI (Dipanggil dari dalam Modal)
    public function delete()
    {
        // Pastikan ID ada
        if ($this->projectToDeleteId) {
            $project = Project::find($this->projectToDeleteId);

            if ($project) {
                // Panggil Action Delete
                app(DeleteProjectAction::class)->execute($project);
                $this->success(__('Project deleted successfully'));
            } else {
                $this->error(__('Project not found'));
            }
        }

        // Reset State & Tutup Modal
        $this->deleteModalOpen = false;
        $this->projectToDeleteId = null;
    }

    public function render()
    {
        $projects = Project::query()
            ->where('name->id', 'like', "%{$this->search}%")
            ->orWhere('name->en', 'like', "%{$this->search}%")
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.project.index', [
            'projects' => $projects
        ]);
    }
}
