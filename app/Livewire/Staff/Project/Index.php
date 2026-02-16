<?php

namespace App\Livewire\Staff\Project;

use App\Models\Project;
use App\Models\Jobdesk;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Project Details')]
class Index extends Component
{
    use WithPagination;

    public Project $project;

    // --- FILTERS ---
    public string $taskSearch = '';
    public string $taskStatus = '';

    public function mount(Project $project)
    {
        // 1. Validasi Keamanan:
        // Cek apakah staff ini memiliki setidaknya satu tugas di project ini.
        // Jika tidak, tolak akses (403 Forbidden).
        $hasAccess = Jobdesk::where('project_id', $project->id)
            ->where('assigned_to', auth()->id())
            ->exists();

        if (!$hasAccess) {
            abort(403, 'Anda tidak memiliki akses ke proyek ini.');
        }

        $this->project = $project;
    }

    // Reset pagination saat search berubah
    public function updatedTaskSearch()
    {
        $this->resetPage();
    }
    public function updatedTaskStatus()
    {
        $this->resetPage();
    }

    public function render()
    {
        // 2. Ambil Tugas Staff dalam Project ini
        $tasks = Jobdesk::where('project_id', $this->project->id)
            ->where('assigned_to', auth()->id())
            // Filter Search (Support JSON Title)
            ->when($this->taskSearch, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('title->id', 'like', "%{$this->taskSearch}%")
                        ->orWhere('title->en', 'like', "%{$this->taskSearch}%");
                });
            })
            // Filter Status
            ->when($this->taskStatus, fn($q) => $q->where('status', $this->taskStatus))
            // Sort Default
            ->orderBy('deadline_task', 'asc')
            ->paginate(10);

        return view('livewire.staff.project.index', [
            'tasks' => $tasks
        ]);
    }
}
