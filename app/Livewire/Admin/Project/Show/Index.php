<?php

namespace App\Livewire\Admin\Project\Show;

use App\Actions\Jobdesk\CreateJobdeskAction;
use App\Actions\Project\UpdateProjectAction;
use App\DTOs\Jobdesk\JobdeskData;
use App\DTOs\Project\ProjectData;
use App\Models\Attendance;
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
use Illuminate\Database\Eloquent\Builder;

#[Layout('layouts.app')]
#[Title('Project Detail')]
class Index extends Component
{
    use WithPagination, Toast;

    public Project $project;

    // --- TABS STATE ---
    public string $selectedTab = 'tasks';

    // --- FILTERS ---
    // Task Filters
    public string $taskSearch = '';
    public string $taskStatus = '';
    public string $taskDateStart = '';

    // Staff Filters
    public string $staffSearch = '';

    // Log Filters
    public string $logSearch = '';
    public string $logStatus = '';
    public string $logDate = '';

    // --- MODALS & DRAWERS STATE ---
    public bool $taskModalOpen = false;
    public bool $projectEditModalOpen = false;
    public bool $staffDetailModalOpen = false;

    // --- FORMS ---
    // Create Jobdesk
    public array $title = ['id' => '', 'en' => ''];
    public array $description = ['id' => '', 'en' => ''];
    public ?int $assigned_to = null;
    public string $deadline_task = '';

    // Edit Project
    public array $pName = ['id' => '', 'en' => ''];
    public array $pDesc = ['id' => '', 'en' => ''];
    public string $pDeadline = '';
    public string $pStatus = '';

    // --- SELECTED DATA ---
    public ?User $selectedStaff = null;

    public function mount(Project $project)
    {
        $this->project = $project->load('creator');
    }

    // --- RESET PAGINATION WHEN FILTER CHANGES ---
    public function updatedTaskSearch()
    {
        $this->resetPage('tasksPage');
    }
    public function updatedTaskStatus()
    {
        $this->resetPage('tasksPage');
    }
    public function updatedLogSearch()
    {
        $this->resetPage('logsPage');
    }

    // --- COMPUTED PROPERTIES ---

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
        $staffIds = Jobdesk::where('project_id', $this->project->id)
            ->distinct()
            ->pluck('assigned_to');

        return User::whereIn('id', $staffIds)
            ->when($this->staffSearch, fn($q) => $q->where('name', 'like', "%{$this->staffSearch}%"))
            ->get()
            ->map(function ($user) {
                $baseQuery = Jobdesk::where('project_id', $this->project->id)->where('assigned_to', $user->id);

                $total = (clone $baseQuery)->count();
                $done = (clone $baseQuery)->where('status', 'approved')->count();
                $late = (clone $baseQuery)->where('lateness_minutes', '>', 0)->count();

                $user->project_total = $total;
                $user->project_done = $done;
                $user->project_late = $late;
                $user->performance = $total > 0 ? round(($done / $total) * 100) : 0;

                return $user;
            });
    }

    public function getSelectedStaffDataProperty()
    {
        if (!$this->selectedStaff)
            return null;

        $tasks = Jobdesk::where('project_id', $this->project->id)
            ->where('assigned_to', $this->selectedStaff->id)
            ->orderByRaw("FIELD(status, 'review', 'revision', 'on_progress', 'pending', 'approved')")
            ->get();

        $total = $tasks->count();
        $done = $tasks->where('status', 'approved')->count();
        $late = $tasks->where('lateness_minutes', '>', 0)->count();
        $progress = $total > 0 ? round(($done / $total) * 100) : 0;

        $attendances = Attendance::where('user_id', $this->selectedStaff->id)
            ->whereHas('reports.jobdesk', fn($q) => $q->where('project_id', $this->project->id))
            ->with(['reports' => fn($q) => $q->whereHas('jobdesk', fn($sq) => $sq->where('project_id', $this->project->id))])
            ->orderBy('check_in', 'desc')
            ->limit(15)
            ->get();

        return [
            'tasks' => $tasks,
            'attendances' => $attendances,
            'stats' => [
                'total' => $total,
                'done' => $done,
                'late' => $late,
                'progress' => $progress
            ]
        ];
    }

    // --- ACTIONS: MODALS & DRAWERS ---

    public function openEditProjectModal()
    {
        $n = $this->project->name;
        $this->pName = ['id' => is_array($n) ? ($n['id'] ?? '') : $n, 'en' => is_array($n) ? ($n['en'] ?? '') : ''];

        $d = $this->project->description;
        $this->pDesc = ['id' => is_array($d) ? ($d['id'] ?? '') : $d, 'en' => is_array($d) ? ($d['en'] ?? '') : ''];

        $this->pDeadline = $this->project->deadline_global ? Carbon::parse($this->project->deadline_global)->format('Y-m-d\TH:i') : '';
        $this->pStatus = $this->project->status;

        $this->projectEditModalOpen = true;
    }

    public function openCreateTaskModal()
    {
        $this->reset(['title', 'description', 'assigned_to', 'deadline_task']);
        $this->title = ['id' => '', 'en' => ''];
        $this->description = ['id' => '', 'en' => ''];
        $this->taskModalOpen = true;
    }

    public function openStaffDetail($userId)
    {
        $this->selectedStaff = User::find($userId);
        $this->staffDetailModalOpen = true;
    }

    // --- ACTIONS: SAVE & UPDATE ---

    public function updateProject()
    {
        $this->validate(['pName.id' => 'required_without:pName.en', 'pStatus' => 'required']);

        $data = new ProjectData(
            name: $this->pName,
            description: $this->pDesc,
            deadline_global: $this->pDeadline,
            status: $this->pStatus,
        );

        app(UpdateProjectAction::class)->execute($this->project, $data);
        $this->projectEditModalOpen = false;
        $this->success('Project updated successfully.');
    }

    public function saveTask()
    {
        $this->validate([
            'title.id' => 'required_without:title.en',
            'assigned_to' => 'required|exists:users,id',
            'deadline_task' => 'required|date',
        ]);

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
        $this->success('New task added.');
    }

    public function render()
    {
        $staffList = User::where('role', 'staff')->orderBy('name')->get();

        $tasks = Jobdesk::with(['assignee', 'creator'])
            ->where('project_id', $this->project->id)
            ->when($this->taskSearch, function ($q) {
                $q->where(function ($sq) {
                    $sq->where('title->id', 'like', "%{$this->taskSearch}%")
                        ->orWhere('title->en', 'like', "%{$this->taskSearch}%")
                        ->orWhereHas('assignee', fn($u) => $u->where('name', 'like', "%{$this->taskSearch}%"));
                });
            })
            ->when($this->taskStatus, fn($q) => $q->where('status', $this->taskStatus))
            ->when($this->taskDateStart, fn($q) => $q->whereDate('deadline_task', '>=', $this->taskDateStart))
            ->orderByRaw("FIELD(status, 'review', 'revision', 'on_progress', 'pending', 'approved')")
            ->paginate(10, ['*'], 'tasksPage');

        $workLogs = JobdeskReport::with(['jobdesk', 'attendance.user', 'details'])
            ->whereHas('jobdesk', fn($q) => $q->where('project_id', $this->project->id))
            ->when($this->logSearch, function ($q) {
                $q->whereHas('jobdesk', function ($sq) {
                    $sq->where('title->id', 'like', "%{$this->logSearch}%")
                        ->orWhere('title->en', 'like', "%{$this->logSearch}%");
                })->orWhereHas('attendance.user', fn($u) => $u->where('name', 'like', "%{$this->logSearch}%"));
            })
            ->when($this->logStatus, fn($q) => $q->where('status_at_report', $this->logStatus))
            ->when($this->logDate, fn($q) => $q->whereDate('created_at', $this->logDate))
            ->latest()
            ->paginate(5, ['*'], 'logsPage');

        return view('livewire.admin.project.show.index', [
            'staffList' => $staffList,
            'tasks' => $tasks,
            'workLogs' => $workLogs,
            'stats' => $this->getProjectStatsProperty(),
            'selectedStaffData' => $this->getSelectedStaffDataProperty()
        ]);
    }
}
