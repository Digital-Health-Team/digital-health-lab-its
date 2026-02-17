<?php

namespace App\Livewire\Admin\Attendance;

use App\Actions\Attendance\Admin\CreateAttendanceAction;
use App\Actions\Attendance\Admin\UpdateAttendanceAction;
use App\Actions\Attendance\Admin\DeleteAttendanceAction;
use App\Models\Attendance;
use App\Models\Jobdesk;
use App\Models\RevisionThread;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Mary\Traits\Toast;

#[Layout('layouts.app')]
#[Title('Attendance Management')]
class Index extends Component
{
    use WithPagination, WithFileUploads, Toast;

    // --- STATES ---
    public string $search = '';
    public ?string $filterDate = null;

    public bool $drawerOpen = false;       // Form Drawer
    public bool $detailDrawerOpen = false; // Detail View Drawer
    public bool $deleteModalOpen = false;

    public ?int $editingId = null;
    public ?int $deletingId = null;
    public ?Attendance $selectedAttendance = null; // Data untuk View Detail

    // --- FORM DATA ---
    public ?int $targetUserId = null;
    public string $checkInDate = '';
    public string $checkInTime = '09:00';
    public string $checkOutTime = '17:00';
    public array $reports = []; // Repeater
    public string $userSearch = ''; // Helper Search Dropdown User

    // --- LISTENERS ---
    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedTargetUserId()
    {
        // Reset repeater hanya jika mode CREATE agar jobdesk list refresh
        if (!$this->editingId) {
            $this->reports = [];
            $this->addReportItem();
        }
    }

    public function searchUser(string $value = '')
    {
        $this->userSearch = trim($value);
    }

    // --- ACTIONS: VIEW & DETAIL ---

    public function view(Attendance $attendance)
    {
        $this->selectedAttendance = $attendance->load(['user', 'reports.jobdesk', 'reports.attachments', 'reports.details']);
        $this->detailDrawerOpen = true;
    }

    // --- ACTIONS: CREATE & EDIT ---

    public function create()
    {
        $this->resetForm();
        $this->drawerOpen = true;
    }

    public function edit(Attendance $attendance)
    {
        $this->resetForm();
        $this->editingId = $attendance->id;

        // Load Header
        $this->targetUserId = $attendance->user_id;
        $this->checkInDate = $attendance->check_in->format('Y-m-d');
        $this->checkInTime = $attendance->check_in->format('H:i');
        $this->checkOutTime = $attendance->check_out ? $attendance->check_out->format('H:i') : '';

        // Load Repeater
        foreach ($attendance->reports as $rep) {
            // Load opsi revisi untuk dropdown
            $availRevisions = [];
            if ($rep->jobdesk_id) {
                $availRevisions = RevisionThread::where('jobdesk_id', $rep->jobdesk_id)
                    ->latest()->take(5)->get()
                    ->map(fn($t) => ['id' => $t->id, 'name' => Str::limit($t->content, 40)])
                    ->toArray();
            }

            $this->reports[] = [
                'id' => $rep->id, // ID Report untuk Update
                'jobdesk_id' => $rep->jobdesk_id,
                'revision_thread_id' => $rep->revision_thread_id,
                'content' => $rep->details->first()->content ?? '',
                'status_at_report' => $rep->status_at_report,
                'new_files' => [], // Wadah file baru
                'existing_files' => $rep->attachments, // Wadah file lama untuk preview
                'available_revisions' => $availRevisions
            ];
        }

        if (empty($this->reports))
            $this->addReportItem();
        $this->drawerOpen = true;
    }

    public function addReportItem()
    {
        $this->reports[] = [
            'id' => null,
            'jobdesk_id' => null,
            'revision_thread_id' => null,
            'content' => '',
            'status_at_report' => 'on_progress',
            'new_files' => [],
            'existing_files' => [],
            'available_revisions' => []
        ];
    }

    public function removeReportItem($index)
    {
        unset($this->reports[$index]);
        $this->reports = array_values($this->reports);
    }

    // Logic: Saat Jobdesk dipilih di form, cari revisi terkait
    public function updatedReports($value, $key)
    {
        $parts = explode('.', $key);
        if (count($parts) === 3 && $parts[2] === 'jobdesk_id') {
            $index = $parts[1];
            $jobdeskId = $value;

            $threads = RevisionThread::where('jobdesk_id', $jobdeskId)
                ->latest()->take(5)->get()
                ->map(fn($t) => ['id' => $t->id, 'name' => Str::limit($t->content, 40) . ' (' . $t->created_at->format('d/m') . ')'])
                ->toArray();

            $this->reports[$index]['available_revisions'] = $threads;
            $this->reports[$index]['revision_thread_id'] = null;
        }
    }

    public function save()
    {
        $this->validate([
            'targetUserId' => 'required|exists:users,id',
            'checkInDate' => 'required|date',
            'checkInTime' => 'required',
            'checkOutTime' => 'nullable',
            'reports.*.jobdesk_id' => 'required|exists:jobdesks,id',
            'reports.*.content' => 'required|string|min:3',
            'reports.*.status_at_report' => 'required',
            'reports.*.new_files.*' => 'nullable|file|max:10240',
        ]);

        $checkInDateTime = Carbon::parse($this->checkInDate . ' ' . $this->checkInTime);
        $checkOutDateTime = $this->checkOutTime ? Carbon::parse($this->checkInDate . ' ' . $this->checkOutTime) : null;

        if ($checkOutDateTime && $checkOutDateTime->lessThan($checkInDateTime)) {
            $this->error('Check Out cannot be before Check In.');
            return;
        }

        $data = [
            'user_id' => $this->targetUserId,
            'check_in' => $checkInDateTime,
            'check_out' => $checkOutDateTime,
            'reports' => $this->reports
        ];

        if ($this->editingId) {
            $attendance = Attendance::findOrFail($this->editingId);
            app(UpdateAttendanceAction::class)->execute($attendance, $data);
            $this->success('Updated successfully.');
        } else {
            app(CreateAttendanceAction::class)->execute($data);
            $this->success('Created successfully.');
        }

        $this->drawerOpen = false;
        $this->resetPage();
    }

    // --- ACTIONS: DELETE ---

    public function confirmDelete($id)
    {
        $this->deletingId = $id;
        $this->deleteModalOpen = true;
    }

    public function delete()
    {
        if ($this->deletingId) {
            $att = Attendance::findOrFail($this->deletingId);
            app(DeleteAttendanceAction::class)->execute($att);
            $this->success('Record deleted.');
        }
        $this->deleteModalOpen = false;
    }

    // --- HELPER ---
    private function resetForm()
    {
        $this->reset(['targetUserId', 'reports', 'editingId', 'checkInDate', 'checkInTime', 'checkOutTime', 'userSearch', 'selectedAttendance']);
        $this->checkInDate = now()->format('Y-m-d');
        $this->checkInTime = '09:00';
        $this->checkOutTime = '17:00';
        if (!$this->editingId)
            $this->addReportItem();
    }

    public function render()
    {
        // 1. Data Staff untuk Dropdown (Manual Entry)
        $usersList = User::where('role', 'staff')
            ->orderBy('name')
            ->take(20)
            ->get();

        // 2. Query Utama Attendance (Table List)
        $attendances = Attendance::query()
            ->with(['user', 'reports.jobdesk'])
            ->when($this->search, fn($q) => $q->whereHas('user', fn($u) => $u->where('name', 'like', "%$this->search%")))
            ->when($this->filterDate, fn($q) => $q->whereDate('check_in', $this->filterDate))
            ->latest()
            ->paginate(10);

        // 3. Data Jobdesk untuk Dropdown (Modal Create/Edit)
        // [INI BAGIAN YANG DIPERBAIKI]
        $staffJobdesks = [];
        if ($this->targetUserId) {
            $staffJobdesks = Jobdesk::where('assigned_to', $this->targetUserId)
                ->with('project')
                ->get() // Ambil collection dulu
                ->map(function ($j) {
                    // FIX: Konversi Array Title ke String
                    $title = $j->title;
                    if (is_array($title)) {
                        $title = $title['id'] ?? ($title['en'] ?? '-');
                    }

                    // FIX: Konversi Array Project Name ke String
                    $projName = $j->project->name ?? '-';
                    if (is_array($projName)) {
                        $projName = $projName['id'] ?? ($projName['en'] ?? '-');
                    }

                    return [
                        'id' => $j->id,
                        'name' => Str::limit($title, 30) . ' (' . Str::limit($projName, 15) . ')'
                    ];
                });
        }

        return view('livewire.admin.attendance.index', [
            'attendances' => $attendances,
            'usersList' => $usersList,
            'staffJobdesks' => $staffJobdesks, // Kirim data yang sudah di-fix
        ]);
    }
}
