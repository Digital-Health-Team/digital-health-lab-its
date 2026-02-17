<?php

namespace App\Livewire\Staff;

use App\Actions\Staff\SubmitCheckInAction;
use App\Actions\Staff\SubmitCheckOutAction;
use App\Models\Attendance;
use App\Models\Jobdesk;
use App\Models\Project;
use App\Models\Announcement;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Mary\Traits\Toast;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

#[Layout('layouts.app')]
#[Title('Workspace Dashboard')]
class Dashboard extends Component
{
    use WithFileUploads, Toast;

    // --- DATA STATE ---
    public ?Attendance $currentSession = null;
    public $announcements = [];
    public ?Attendance $selectedAttendance = null;
    public ?Announcement $selectedAnnouncement = null;

    // --- TABS & FILTERS ---
    public string $selectedTab = 'tasks';
    public string $sortDeadline = 'asc';
    public ?int $filterProjectId = null;
    public ?string $filterStatus = null;

    // --- MODAL STATES ---
    public bool $checkInModal = false;
    public bool $checkOutModal = false;
    public bool $detailModal = false;
    public bool $announcementModal = false;
    public bool $sopModal = false;

    // --- WIZARD STATE ---
    public int $checkoutStep = 1;

    // --- GPS STATE ---
    public $latitude = null;
    public $longitude = null;

    // --- FORMS ---
    public $photoIn, $photoOut, $note;
    public $selectedJobdesks = [];
    public $finishedJobdesks = [];
    public $attachments = [];

    public function mount()
    {
        $this->refreshAttendanceSession();
        $this->announcements = Announcement::latest()->take(5)->get();

        // Popup SOP hanya sekali per login session
        if (!session()->has('sop_seen')) {
            $this->sopModal = true;
            session()->put('sop_seen', true);
        }
    }

    public function refreshAttendanceSession()
    {
        $this->currentSession = Attendance::where('user_id', auth()->id())
            ->whereNull('check_out')
            ->latest()
            ->first();
    }

    // --- ACTIONS ---

    public function openCheckoutModal()
    {
        $this->reset(['photoOut', 'note', 'selectedJobdesks', 'finishedJobdesks', 'attachments', 'latitude', 'longitude']);
        $this->checkoutStep = 1;
        $this->checkOutModal = true;
    }

    public function nextCheckOutStep()
    {
        if ($this->checkoutStep == 1) {
            if (!$this->photoOut) {
                $this->error('Wajib ambil selfie dulu!');
                return;
            }
        }
        $this->checkoutStep++;
    }

    public function prevCheckOutStep()
    {
        $this->checkoutStep--;
    }

    public function showAnnouncement($id)
    {
        $this->selectedAnnouncement = Announcement::find($id);
        $this->announcementModal = true;
    }

    public function showAttendanceDetail($id)
    {
        $this->selectedAttendance = Attendance::with([
            'reports.jobdesk.project',
            'reports.details',
            'reports.attachments',
            'attachments'
        ])->find($id);
        $this->detailModal = true;
    }

    public function doCheckIn(SubmitCheckInAction $action)
    {
        if (!$this->photoIn) {
            $this->error('Selfie wajib diambil!');
            return;
        }

        $action->execute([
            'photo' => $this->photoIn,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude
        ]);

        $this->checkInModal = false;
        $this->refreshAttendanceSession();
        $this->success('Shift dimulai! Lokasi tercatat.');
    }

    public function doCheckOut(SubmitCheckOutAction $action)
    {
        $this->validate([
            'photoOut' => 'required',
            'note' => 'required|min:5',
            'attachments.*' => 'image|max:10240',
        ], [
            'attachments.*.image' => 'File bukti harus berupa gambar.',
        ]);

        $data = [
            'photo' => $this->photoOut,
            'note' => $this->note,
            'selected_jobdesks' => $this->selectedJobdesks,
            'finished_jobdesks' => $this->finishedJobdesks,
            'attachments' => $this->attachments,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude
        ];

        $action->execute($this->currentSession, $data);
        $this->checkOutModal = false;
        $this->refreshAttendanceSession();
        $this->success('Shift selesai. Lokasi tercatat.');
    }

    // --- COMPUTED PROPERTIES ---

    // [BARU] Statistik Ringan untuk Header Dashboard
    // [UPDATE] Statistik dengan KPI Lateness
    public function getStatsProperty()
    {
        $todayWork = Attendance::where('user_id', auth()->id())
            ->whereDate('created_at', today())
            ->get();

        $totalDuration = 0;
        foreach ($todayWork as $session) {
            if ($session->check_out) {
                $totalDuration += $session->check_in->diffInMinutes($session->check_out);
            } elseif ($session->check_in) {
                $totalDuration += $session->check_in->diffInMinutes(now());
            }
        }

        $pendingTasks = Jobdesk::where('assigned_to', auth()->id())
            ->whereIn('status', ['pending', 'on_progress', 'revision'])
            ->count();

        // [BARU] Hitung Late Count bulan ini untuk KPI
        $lateCount = Jobdesk::where('assigned_to', auth()->id())
            ->where('lateness_minutes', '>', 0) // Yang ada nilai lateness
            ->whereMonth('submitted_at', now()->month)
            ->count();

        $hours = floor($totalDuration / 60);
        $minutes = $totalDuration % 60;

        return [
            'hours_today' => "{$hours}h {$minutes}m",
            'pending_tasks' => $pendingTasks,
            'late_count' => $lateCount, // Data baru
            'active_projects' => $this->myProjects->count()
        ];
    }

    public function getActiveTasksProperty()
    {
        return Jobdesk::with('project')
            ->where('assigned_to', auth()->id())
            ->when($this->filterStatus, function ($q) {
                $q->where('status', $this->filterStatus);
            }, function ($q) {
                $q->whereIn('status', ['pending', 'on_progress', 'revision', 'review']);
            })
            ->when($this->filterProjectId, fn($q) => $q->where('project_id', $this->filterProjectId))
            ->orderByRaw("FIELD(status, 'revision', 'on_progress', 'pending', 'review', 'approved')")
            ->orderBy('deadline_task', $this->sortDeadline)
            ->get();
    }

    public function getMyProjectsProperty()
    {
        return Project::whereHas('jobdesks', fn($q) => $q->where('assigned_to', auth()->id()))
            ->withCount(['jobdesks as total_tasks' => fn($q) => $q->where('assigned_to', auth()->id())])
            ->withCount([
                'jobdesks as pending_tasks' => fn($q) =>
                    $q->where('assigned_to', auth()->id())->whereIn('status', ['pending', 'on_progress', 'revision'])
            ])->get();
    }

    public function getAttendanceHistoryProperty()
    {
        return Attendance::with(['reports'])
            ->where('user_id', auth()->id())
            ->when($this->currentSession, fn($q) => $q->where('id', '!=', $this->currentSession->id))
            ->orderBy('created_at', 'desc')
            ->take(15)
            ->get();
    }

    public function render()
    {
        return view('livewire.staff.dashboard', [
            'activeTasks' => $this->getActiveTasksProperty(),
            'myProjects' => $this->getMyProjectsProperty(),
            'attendanceHistory' => $this->getAttendanceHistoryProperty(),
            'stats' => $this->getStatsProperty(),
        ]);
    }
}
