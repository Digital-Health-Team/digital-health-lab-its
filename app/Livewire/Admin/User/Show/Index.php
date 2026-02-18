<?php

namespace App\Livewire\Admin\User\Show;

use App\Models\Attendance;
use App\Models\Jobdesk;
use App\Models\Project;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

#[Layout('layouts.app')]
#[Title('Staff Detail')]
class Index extends Component
{
    use WithPagination;

    public User $user;
    public string $selectedTab = 'tasks';

    // --- FILTERS ---
    public string $taskSearch = '';
    public string $taskStatus = '';
    public ?int $taskProject = null;
    public string $taskDateStart = '';
    public string $taskDateEnd = '';
    public string $attMonth = '';

    // --- DRAWER STATE [BARU] ---
    public bool $attendanceDrawerOpen = false;
    public ?Attendance $selectedAttendance = null;
    // --- DRAWER STATE: PROJECT PERFORMANCE [BARU] ---
    public bool $projectPerformanceDrawer = false;
    public ?Project $selectedProjectPerformance = null;
    public array $projectPerformanceData = [];

    public function mount(User $user)
    {
        if ($user->role !== 'staff')
            abort(404);
        $this->user = $user;
        $this->attMonth = now()->format('Y-m');
    }

    // --- ACTION: OPEN PROJECT PERFORMANCE ---
    public function openProjectPerformance($projectId)
    {
        $this->selectedProjectPerformance = Project::find($projectId);

        if (!$this->selectedProjectPerformance)
            return;

        // 1. Ambil Tasks Staff di Project Ini
        $tasks = Jobdesk::where('project_id', $projectId)
            ->where('assigned_to', $this->user->id)
            ->orderByRaw("FIELD(status, 'review', 'revision', 'on_progress', 'pending', 'approved')")
            ->get();

        // 2. Hitung Statistik
        $total = $tasks->count();
        $done = $tasks->where('status', 'approved')->count();
        $late = $tasks->where('lateness_minutes', '>', 0)->count();
        $progress = $total > 0 ? round(($done / $total) * 100) : 0;

        // 3. Ambil Attendance terkait Project ini
        // (Logic: Attendance dimana user membuat report untuk task di project ini)
        $attendances = Attendance::where('user_id', $this->user->id)
            ->whereHas('reports.jobdesk', fn($q) => $q->where('project_id', $projectId))
            ->with(['reports' => fn($q) => $q->whereHas('jobdesk', fn($sq) => $sq->where('project_id', $projectId))])
            ->orderBy('check_in', 'desc')
            ->get();

        // 4. Hitung Total Jam Kerja di Project ini (Estimasi kasar berdasarkan durasi attendance)
        $totalMinutes = 0;
        foreach ($attendances as $att) {
            if ($att->check_out) {
                $totalMinutes += $att->check_in->diffInMinutes($att->check_out);
            }
        }
        $hours = floor($totalMinutes / 60);
        $mins = $totalMinutes % 60;

        $this->projectPerformanceData = [
            'tasks' => $tasks,
            'attendances' => $attendances,
            'stats' => [
                'total' => $total,
                'done' => $done,
                'late' => $late,
                'progress' => $progress,
                'time_spent' => "{$hours}h {$mins}m"
            ]
        ];

        $this->projectPerformanceDrawer = true;
    }

    // Reset pagination
    public function updatedTaskSearch()
    {
        $this->resetPage('tasksPage');
    }
    public function updatedTaskStatus()
    {
        $this->resetPage('tasksPage');
    }
    public function updatedAttMonth()
    {
        $this->resetPage('attPage');
    }

    // --- ACTIONS [BARU] ---
    public function openAttendanceDetail($id)
    {
        // Load Attendance beserta Reports & Jobdesk terkait
        $this->selectedAttendance = Attendance::with(['reports.jobdesk', 'reports.details', 'reports.attachments'])
            ->find($id);
        $this->attendanceDrawerOpen = true;
    }

    // --- COMPUTED PROPERTIES ---
    public function getStatsProperty()
    {
        $totalTasks = Jobdesk::where('assigned_to', $this->user->id)->count();
        $completedTasks = Jobdesk::where('assigned_to', $this->user->id)->where('status', 'approved')->count();
        $lateTasks = Jobdesk::where('assigned_to', $this->user->id)->where('lateness_minutes', '>', 0)->count();
        $performance = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;

        return [
            'total' => $totalTasks,
            'completed' => $completedTasks,
            'late' => $lateTasks,
            'performance' => $performance,
        ];
    }

    public function getProjectsProperty()
    {
        return Project::whereHas('jobdesks', fn($q) => $q->where('assigned_to', $this->user->id))
            ->withCount(['jobdesks as staff_tasks_count' => fn($q) => $q->where('assigned_to', $this->user->id)])
            ->get()
            ->map(function ($p) {
                $n = $p->name;
                $p->display_name = is_array($n) ? ($n['id'] ?? $n['en'] ?? '-') : $n;
                return $p;
            });
    }

    public function getTasksProperty()
    {
        return Jobdesk::with(['project', 'creator'])
            ->where('assigned_to', $this->user->id)
            ->when($this->taskSearch, function ($q) {
                $q->where(function ($sq) {
                    $sq->where('title->id', 'like', "%{$this->taskSearch}%")
                        ->orWhere('title->en', 'like', "%{$this->taskSearch}%");
                });
            })
            ->when($this->taskStatus, fn($q) => $q->where('status', $this->taskStatus))
            ->when($this->taskProject, fn($q) => $q->where('project_id', $this->taskProject))
            ->when($this->taskDateStart, fn($q) => $q->whereDate('deadline_task', '>=', $this->taskDateStart))
            ->when($this->taskDateEnd, fn($q) => $q->whereDate('deadline_task', '<=', $this->taskDateEnd))
            ->latest()
            ->paginate(10, ['*'], 'tasksPage');
    }

    public function getAttendancesProperty()
    {
        return Attendance::where('user_id', $this->user->id)
            ->when($this->attMonth, function ($q) {
                $date = Carbon::createFromFormat('Y-m', $this->attMonth);
                $q->whereYear('check_in', $date->year)
                    ->whereMonth('check_in', $date->month);
            })
            ->withCount('reports')
            ->latest('check_in')
            ->paginate(10, ['*'], 'attPage');
    }

    public function render()
    {
        return view('livewire.admin.user.show.index', [
            'stats' => $this->getStatsProperty(),
            'projectsList' => $this->getProjectsProperty(),
            'tasks' => $this->getTasksProperty(),
            'attendances' => $this->getAttendancesProperty(),
        ]);
    }
}
