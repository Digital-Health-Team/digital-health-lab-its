<?php

namespace App\Livewire\Pm;

use App\Actions\Pm\CreateJobdeskAction;
use App\Actions\Pm\ReviewJobdeskAction;
use App\Models\Attendance;
use App\Models\Jobdesk;
use App\Models\Project;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Mary\Traits\Toast;

#[Layout('layouts.app')]
#[Title('PM Dashboard')]
class Dashboard extends Component
{
    use Toast;

    // --- STATES ---
    public bool $createModal = false;
    public bool $reviewModal = false;
    public bool $attendanceDrawer = false; // [BARU] Drawer Absensi

    // --- TABS ---
    public string $selectedTab = 'overview';

    // --- FORMS ---
    public $project_id, $assigned_to, $title, $description, $deadline_task;

    // --- SELECTED DATA ---
    public ?Jobdesk $selectedTask = null;
    public ?Attendance $selectedAttendance = null; // [BARU]
    public $revisionNote;

    // --- COMPUTED PROPERTIES ---

    public function getStatsProperty()
    {
        $myProjectIds = Project::where('created_by', auth()->id())->pluck('id');

        $lateTasks = Jobdesk::whereIn('project_id', $myProjectIds)
            ->whereMonth('submitted_at', now()->month)
            ->where('lateness_minutes', '>', 0)
            ->count();

        return [
            'active_projects' => Project::whereIn('id', $myProjectIds)->where('status', 'active')->count(),
            'pending_review' => Jobdesk::whereIn('project_id', $myProjectIds)->where('status', 'review')->count(),
            'team_lateness' => $lateTasks
        ];
    }

    public function getProjectsProperty()
    {
        return Project::where('created_by', auth()->id())
            ->withCount('jobdesks')
            ->withCount(['jobdesks as completed_tasks' => fn($q) => $q->where('status', 'approved')])
            ->orderBy('deadline_global', 'asc')
            ->get()
            ->map(function ($proj) {
                // Fix Array Name
                $pName = $proj->name;
                if (is_array($pName))
                    $pName = $pName['id'] ?? $pName['en'] ?? '-';
                $proj->display_name = $pName;

                $proj->progress = $proj->jobdesks_count > 0
                    ? round(($proj->completed_tasks / $proj->jobdesks_count) * 100)
                    : 0;
                $proj->is_urgent = $proj->deadline_global && $proj->deadline_global->diffInDays(now()) <= 3;
                return $proj;
            });
    }

    public function getPendingReviewsProperty()
    {
        return Jobdesk::with(['project', 'assignee'])
            ->whereHas('project', fn($q) => $q->where('created_by', auth()->id()))
            ->where('status', 'review')
            ->orderBy('submitted_at', 'asc')
            ->get();
    }

    // [BARU] Ambil Absensi Staff yang ada di Project PM ini hari ini
    public function getStaffAttendanceProperty()
    {
        // 1. Cari Staff ID yang ada di project milik PM ini
        $myProjectIds = Project::where('created_by', auth()->id())->pluck('id');
        $staffIds = Jobdesk::whereIn('project_id', $myProjectIds)
            ->distinct()
            ->pluck('assigned_to');

        // 2. Ambil Absensi Hari Ini
        return Attendance::with('user')
            ->whereIn('user_id', $staffIds)
            ->whereDate('created_at', today())
            ->orderBy('check_in', 'desc')
            ->get();
    }

    public function getStaffsProperty()
    {
        return User::where('role', 'staff')->get()->map(fn($u) => ['id' => $u->id, 'name' => $u->name]);
    }

    // --- ACTIONS ---

    public function openReviewModal($id)
    {
        $this->selectedTask = Jobdesk::with(['project', 'assignee', 'reports.attachments'])->find($id);
        $this->revisionNote = '';
        $this->reviewModal = true;
    }

    // [BARU] Buka Drawer Detail Absensi
    public function openAttendanceDetail($id)
    {
        $this->selectedAttendance = Attendance::with('user')->find($id);
        $this->attendanceDrawer = true;
    }

    public function storeTask(CreateJobdeskAction $action)
    {
        $this->validate([
            'project_id' => 'required',
            'assigned_to' => 'required',
            'title' => 'required|min:5',
            'deadline_task' => 'required|date|after:now',
        ]);

        $action->execute([
            'project_id' => $this->project_id,
            'assigned_to' => $this->assigned_to,
            'title' => $this->title, // Pastikan input string/array sesuai
            'description' => $this->description,
            'deadline_task' => $this->deadline_task
        ]);

        $this->createModal = false;
        $this->reset(['project_id', 'assigned_to', 'title', 'description', 'deadline_task']);
        $this->success('Jobdesk created successfully!');
    }

    public function submitReview(ReviewJobdeskAction $action, $decision)
    {
        if ($decision == 'revision') {
            $this->validate(['revisionNote' => 'required|min:5']);
        }

        $action->execute($this->selectedTask, $decision, $this->revisionNote);

        $this->reviewModal = false;
        $this->success('Task status updated: ' . ucfirst($decision));
    }

    public function render()
    {
        return view('livewire.pm.dashboard', [
            'stats' => $this->getStatsProperty(),
            'projects' => $this->getProjectsProperty(),
            'pendingReviews' => $this->getPendingReviewsProperty(),
            'staffAttendance' => $this->getStaffAttendanceProperty(),
            'staffs' => $this->getStaffsProperty()
        ]);
    }
}
