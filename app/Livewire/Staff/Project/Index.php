<?php

namespace App\Livewire\Staff\Project;

use App\Models\Project;
use App\Models\Jobdesk;
use App\Models\Attendance;
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
        // 1. Validasi Keamanan: Cek akses staff ke project ini
        $hasAccess = Jobdesk::where('project_id', $project->id)
            ->where('assigned_to', auth()->id())
            ->exists();

        if (!$hasAccess) {
            abort(403, 'Anda tidak memiliki akses ke proyek ini.');
        }

        $this->project = $project->load('creator'); // Eager load PM
    }

    public function updatedTaskSearch()
    {
        $this->resetPage();
    }
    public function updatedTaskStatus()
    {
        $this->resetPage();
    }

    // --- COMPUTED PROPERTIES: STATISTICS ---
    public function getStatsProperty()
    {
        $userId = auth()->id();
        $baseQuery = Jobdesk::where('project_id', $this->project->id)->where('assigned_to', $userId);

        $total = (clone $baseQuery)->count();
        $done = (clone $baseQuery)->where('status', 'approved')->count();
        $late = (clone $baseQuery)->where('lateness_minutes', '>', 0)->count();
        $progress = $total > 0 ? round(($done / $total) * 100) : 0;

        // Hitung estimasi jam kerja berdasarkan attendance terkait project ini
        $totalMinutes = 0;
        $attendances = Attendance::where('user_id', $userId)
            ->whereHas('reports.jobdesk', fn($q) => $q->where('project_id', $this->project->id))
            ->get();

        foreach ($attendances as $att) {
            if ($att->check_out) {
                $totalMinutes += $att->check_in->diffInMinutes($att->check_out);
            }
        }
        $hours = floor($totalMinutes / 60);
        $mins = $totalMinutes % 60;

        return [
            'total' => $total,
            'done' => $done,
            'late' => $late,
            'progress' => $progress,
            'time_spent' => "{$hours}h {$mins}m"
        ];
    }

    public function render()
    {
        // 2. Ambil Tugas Staff dalam Project ini
        $tasks = Jobdesk::where('project_id', $this->project->id)
            ->where('assigned_to', auth()->id())
            // Filter Search
            ->when($this->taskSearch, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('title->id', 'like', "%{$this->taskSearch}%")
                        ->orWhere('title->en', 'like', "%{$this->taskSearch}%");
                });
            })
            // Filter Status
            ->when($this->taskStatus, fn($q) => $q->where('status', $this->taskStatus))
            // Sort Default: Deadline terdekat, lalu status priority
            ->orderByRaw("FIELD(status, 'revision', 'on_progress', 'pending', 'review', 'approved')")
            ->orderBy('deadline_task', 'asc')
            ->paginate(10);

        return view('livewire.staff.project.index', [
            'tasks' => $tasks,
            'stats' => $this->getStatsProperty()
        ]);
    }
}
