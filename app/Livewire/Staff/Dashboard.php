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

    public int $checkoutStep = 1;
    public $latitude = null;
    public $longitude = null;

    // --- FORMS ---
    public $photoIn, $photoOut, $note;
    public $selectedJobdesks = [];
    public $finishedJobdesks = [];
    public $taskAttachments = [];

    // [BARU] Menyimpan notes/catatan per tugas: [jobdesk_id => 'teks catatan']
    public $taskNotes = [];

    public string $checkoutTaskSearch = '';
    public string $checkoutTaskStatus = '';

    public function mount()
    {
        $this->refreshAttendanceSession();
        $this->announcements = Announcement::latest()->take(5)->get();

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

    public function openCheckoutModal()
    {
        // [UPDATE] Reset taskNotes
        $this->reset([
            'photoOut',
            'note',
            'selectedJobdesks',
            'finishedJobdesks',
            'taskAttachments',
            'taskNotes',
            'latitude',
            'longitude',
            'checkoutTaskSearch',
            'checkoutTaskStatus' // Reset filter modal
        ]);
        $this->checkoutStep = 1;
        $this->checkOutModal = true;
    }

    public function nextCheckOutStep($photoData = null)
    {
        if ($this->checkoutStep == 1) {
            if ($photoData) {
                $this->photoOut = $photoData;
            }
            if (!$this->photoOut) {
                $this->error('Wajib ambil foto selfie dulu!');
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

    public function doCheckIn($photoData)
    {
        $this->photoIn = $photoData;

        if (!$this->photoIn) {
            $this->error('Selfie wajib diambil!');
            return;
        }

        app(SubmitCheckInAction::class)->execute([
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
            'note' => 'nullable|string', // Note global sekarang opsional
            'taskNotes.*' => 'nullable|string', // Validasi input array taskNotes
            'taskAttachments.*.*' => 'image|max:10240',
        ]);

        $data = [
            'photo' => $this->photoOut,
            'note' => $this->note,
            'selected_jobdesks' => $this->selectedJobdesks,
            'finished_jobdesks' => $this->finishedJobdesks,
            'taskAttachments' => $this->taskAttachments,
            'taskNotes' => $this->taskNotes, // [BARU] Pass ke Action
            'latitude' => $this->latitude,
            'longitude' => $this->longitude
        ];

        $action->execute($this->currentSession, $data);
        $this->checkOutModal = false;
        $this->refreshAttendanceSession();
        $this->success('Shift selesai. Laporan berhasil dikirim.');
    }

    public function getStatsProperty()
    {
        $todayWork = Attendance::where('user_id', auth()->id())->whereDate('created_at', today())->get();
        $totalDuration = 0;
        foreach ($todayWork as $session) {
            if ($session->check_out) {
                $totalDuration += $session->check_in->diffInMinutes($session->check_out);
            } elseif ($session->check_in) {
                $totalDuration += $session->check_in->diffInMinutes(now());
            }
        }
        $pendingTasks = Jobdesk::where('assigned_to', auth()->id())->whereIn('status', ['pending', 'on_progress', 'revision'])->count();
        $lateCount = Jobdesk::where('assigned_to', auth()->id())->where('lateness_minutes', '>', 0)->whereMonth('submitted_at', now()->month)->count();

        return [
            'hours_today' => floor($totalDuration / 60) . 'h ' . ($totalDuration % 60) . 'm',
            'pending_tasks' => $pendingTasks,
            'late_count' => $lateCount,
            'active_projects' => $this->myProjects->count()
        ];
    }

    public function getActiveTasksProperty()
    {
        return Jobdesk::with('project')->where('assigned_to', auth()->id())
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus), fn($q) => $q->whereIn('status', ['pending', 'on_progress', 'revision', 'review']))
            ->when($this->filterProjectId, fn($q) => $q->where('project_id', $this->filterProjectId))
            ->orderByRaw("FIELD(status, 'revision', 'on_progress', 'pending', 'review', 'approved')")
            ->orderBy('deadline_task', $this->sortDeadline)->get();
    }

    public function getMyProjectsProperty()
    {
        return Project::whereHas('jobdesks', fn($q) => $q->where('assigned_to', auth()->id()))
            ->withCount(['jobdesks as total_tasks' => fn($q) => $q->where('assigned_to', auth()->id())])
            ->withCount(['jobdesks as pending_tasks' => fn($q) => $q->where('assigned_to', auth()->id())->whereIn('status', ['pending', 'on_progress', 'revision'])])->get();
    }

    public function getAttendanceHistoryProperty()
    {
        return Attendance::with(['reports'])->where('user_id', auth()->id())
            ->when($this->currentSession, fn($q) => $q->where('id', '!=', $this->currentSession->id))
            ->orderBy('created_at', 'desc')->take(15)->get();
    }

    // [BARU] Computed Property khusus untuk list tugas di dalam Modal Checkout
    public function getCheckoutTasksProperty()
    {
        return Jobdesk::with('project')
            ->where('assigned_to', auth()->id())
            // Hanya tampilkan tugas yang belum "approved" (selesai) agar bisa dilaporkan
            ->whereIn('status', ['pending', 'on_progress', 'revision', 'review'])

            // Filter Search Khusus Modal
            ->when($this->checkoutTaskSearch, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('title->id', 'like', "%{$this->checkoutTaskSearch}%")
                        ->orWhere('title->en', 'like', "%{$this->checkoutTaskSearch}%");
                });
            })
            // Filter Status Khusus Modal
            ->when($this->checkoutTaskStatus, fn($q) => $q->where('status', $this->checkoutTaskStatus))

            // Urutkan berdasarkan prioritas pengerjaan
            ->orderByRaw("FIELD(status, 'revision', 'on_progress', 'pending', 'review')")
            ->get();
        // Menggunakan get() agar semua tugas yang belum selesai bisa di-scroll & dicari di modal tanpa pagination
    }

    public function render()
    {
        return view('livewire.staff.dashboard', [
            'activeTasks' => $this->getActiveTasksProperty(),
            'myProjects' => $this->getMyProjectsProperty(),
            'attendanceHistory' => $this->getAttendanceHistoryProperty(),
            'stats' => $this->getStatsProperty(),
            'user' => auth()->user()
        ]);
    }
}
