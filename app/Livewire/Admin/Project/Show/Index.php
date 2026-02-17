<?php

namespace App\Livewire\Admin\Project\Show;

use App\Actions\Jobdesk\CreateJobdeskAction;
use App\DTOs\Jobdesk\JobdeskData;
use App\Models\Jobdesk;
use App\Models\JobdeskReport;
use App\Models\Project;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;
use Illuminate\Support\Carbon;

#[Layout('layouts.app')]
#[Title('Project Detail')]
class Index extends Component
{
    use WithPagination, Toast;

    public Project $project;

    // --- TABS STATE ---
    public string $selectedTab = 'tasks';

    // --- SEARCH ---
    public string $taskSearch = '';

    // --- MODAL JOBDESK ---
    public bool $taskModalOpen = false;

    // Form Jobdesk
    public array $title = ['id' => '', 'en' => ''];
    public array $description = ['id' => '', 'en' => ''];
    public ?int $assigned_to = null;
    public string $deadline_task = '';

    public function mount(Project $project)
    {
        $this->project = $project->load('creator');
    }

    // --- COMPUTED PROPERTIES (MONITORING) ---

    public function getProjectStatsProperty()
    {
        $total = $this->project->jobdesks()->count();
        $completed = $this->project->jobdesks()->where('status', 'approved')->count();
        $progress = $total > 0 ? round(($completed / $total) * 100) : 0;

        $pending = $this->project->jobdesks()->whereIn('status', ['pending', 'on_progress', 'revision'])->count();
        $review = $this->project->jobdesks()->where('status', 'review')->count();

        return [
            'total' => $total,
            'completed' => $completed,
            'progress' => $progress,
            'pending' => $pending,
            'review' => $review
        ];
    }

    public function getStaffInvolvedProperty()
    {
        // Ambil ID staff yang punya tugas di project ini
        $staffIds = Jobdesk::where('project_id', $this->project->id)
            ->distinct()
            ->pluck('assigned_to');

        return User::whereIn('id', $staffIds)->get()->map(function($user) {
            // Hitung performa per user di project ini
            $total = Jobdesk::where('project_id', $this->project->id)->where('assigned_to', $user->id)->count();
            $done = Jobdesk::where('project_id', $this->project->id)->where('assigned_to', $user->id)->where('status', 'approved')->count();
            $late = Jobdesk::where('project_id', $this->project->id)->where('assigned_to', $user->id)->where('lateness_minutes', '>', 0)->count();

            $user->project_total = $total;
            $user->project_done = $done;
            $user->project_late = $late;
            $user->performance = $total > 0 ? round(($done / $total) * 100) : 0;

            return $user;
        });
    }

    // --- ACTIONS ---

    public function openCreateTaskModal()
    {
        $this->reset(['title', 'description', 'assigned_to', 'deadline_task']);
        $this->title = ['id' => '', 'en' => ''];
        $this->description = ['id' => '', 'en' => ''];
        $this->taskModalOpen = true;
    }

    public function saveTask()
    {
        $this->validate([
            'title.id' => 'required_without:title.en',
            'assigned_to' => 'required|exists:users,id',
            'deadline_task' => 'required|date',
        ]);

        // Gunakan Action yang sama dengan modul Jobdesk utama
        // Kita hardcode project_id nya sesuai project yang sedang dilihat
        $data = new JobdeskData(
            project_id: $this->project->id,
            assigned_to: (int) $this->assigned_to,
            created_by: auth()->id(),
            title: $this->title,
            description: $this->description,
            deadline_task: $this->deadline_task,
            status: 'pending'
        );

        app(CreateJobdeskAction::class)->execute($data);

        $this->taskModalOpen = false;
        $this->success('New task added to this project.');
    }

    public function render()
    {
        // 1. Data Staff untuk Dropdown (Create Task)
        $staffList = User::where('role', 'staff')->orderBy('name')->get();

        // 2. List Tasks (Pagination)
        $tasks = Jobdesk::with(['assignee', 'creator'])
            ->where('project_id', $this->project->id)
            ->when($this->taskSearch, function($q) {
                $q->where('title->id', 'like', "%{$this->taskSearch}%")
                  ->orWhere('title->en', 'like', "%{$this->taskSearch}%")
                  ->orWhereHas('assignee', fn($u) => $u->where('name', 'like', "%{$this->taskSearch}%"));
            })
            ->orderByRaw("FIELD(status, 'review', 'revision', 'on_progress', 'pending', 'approved')")
            ->paginate(10, ['*'], 'tasksPage');

        // 3. Work Logs / Reports (Attendance Context)
        // Menampilkan laporan kerja yang TERKAIT dengan project ini (dari Check Out)
        $workLogs = JobdeskReport::with(['jobdesk', 'attendance.user', 'details'])
            ->whereHas('jobdesk', fn($q) => $q->where('project_id', $this->project->id))
            ->latest()
            ->paginate(5, ['*'], 'logsPage');

        return view('livewire.admin.project.show.index', [
            'staffList' => $staffList,
            'tasks' => $tasks,
            'workLogs' => $workLogs,
            'stats' => $this->getProjectStatsProperty()
        ]);
    }
}
