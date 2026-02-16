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

#[Layout('layouts.app')]
#[Title('Workspace Dashboard')]
class Dashboard extends Component
{
    use WithFileUploads, Toast;

    // Data
    public ?Attendance $todayAttendance = null;
    public $announcements = [];

    // Filter & Tabs
    public string $selectedTab = 'tasks';
    public string $sortDeadline = 'asc';
    public ?int $filterProjectId = null;

    // Modal & Form
    public bool $checkInModal = false;
    public bool $checkOutModal = false;
    public $photoIn, $photoOut, $note;
    public $selectedJobdesks = [], $finishedJobdesks = [], $attachments = [];

    public function mount()
    {
        $this->refreshAttendance();
        $this->announcements = Announcement::latest()->take(3)->get();
    }

    public function refreshAttendance()
    {
        $this->todayAttendance = Attendance::where('user_id', auth()->id())
            ->whereDate('created_at', today())->first();
    }

    // Computed Property: Active Tasks
    public function getActiveTasksProperty()
    {
        return Jobdesk::with('project')
            ->where('assigned_to', auth()->id())
            ->whereIn('status', ['pending', 'on_progress', 'revision'])
            ->when($this->filterProjectId, fn($q) => $q->where('project_id', $this->filterProjectId))
            ->orderByRaw("FIELD(status, 'revision', 'on_progress', 'pending')")
            ->orderBy('deadline_task', $this->sortDeadline)
            ->get();
    }

    // Computed Property: My Projects
    public function getMyProjectsProperty()
    {
        return Project::whereHas('jobdesks', function (Builder $q) {
            $q->where('assigned_to', auth()->id());
        })
            ->withCount([
                'jobdesks as total_tasks' => function ($q) {
                    $q->where('assigned_to', auth()->id());
                }
            ])
            ->withCount([
                'jobdesks as pending_tasks' => function ($q) {
                    $q->where('assigned_to', auth()->id())
                        ->whereIn('status', ['pending', 'on_progress', 'revision']);
                }
            ])
            ->get();
    }

    // Actions
    public function doCheckIn(SubmitCheckInAction $action)
    {
        if (!$this->photoIn) {
            $this->error('Selfie wajib!');
            return;
        }
        $action->execute($this->photoIn);
        $this->checkInModal = false;
        $this->refreshAttendance();
        $this->success('Check In berhasil!');
    }

    public function doCheckOut(SubmitCheckOutAction $action)
    {
        $this->validate([
            'photoOut' => 'required',
            'note' => 'required|min:10',
            'selectedJobdesks' => 'required|array|min:1',
        ]);
        $data = [
            'photo' => $this->photoOut,
            'note' => $this->note,
            'selected_jobdesks' => $this->selectedJobdesks,
            'finished_jobdesks' => $this->finishedJobdesks,
            'attachments' => $this->attachments
        ];
        $action->execute($this->todayAttendance, $data);
        $this->checkOutModal = false;
        $this->refreshAttendance();
        $this->success('Laporan terkirim.');
    }

    public function render()
    {
        return view('livewire.staff.dashboard');
    }
}
