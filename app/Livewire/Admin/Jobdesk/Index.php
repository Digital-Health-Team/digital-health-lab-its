<?php

namespace App\Livewire\Admin\Jobdesk;

use App\Actions\Jobdesk\CreateJobdeskAction;
use App\Actions\Jobdesk\UpdateJobdeskAction;
use App\DTOs\Jobdesk\JobdeskData;
use App\Models\Jobdesk;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

#[Layout('layouts.app')]
#[Title('Master Jobdesk')]
class Index extends Component
{
    use WithPagination, Toast;

    // --- GLOBAL SEARCH ---
    public string $globalSearch = '';

    // --- ADVANCED FILTERS ---
    public bool $filterModal = false;
    public ?int $filterProject = null;
    public ?int $filterAssignee = null;
    public ?string $filterStatus = null;
    public ?string $filterDateStart = null;
    public ?string $filterDateEnd = null;

    // --- DROPDOWN SEARCH STATES ---
    public string $projectSearch = '';
    public string $staffSearch = '';

    // --- CRUD STATES ---
    public bool $modalOpen = false;
    public bool $deleteModalOpen = false;
    public ?int $editingJobdeskId = null;
    public ?int $jobdeskToDeleteId = null;

    // --- FORM DATA ---
    public array $title = ['id' => '', 'en' => ''];
    public array $description = ['id' => '', 'en' => ''];
    public ?int $project_id = null;
    public ?int $assigned_to = null;
    public string $deadline_task = '';
    public string $status = 'pending';

    // --- VALIDATION RULES ---
    public function rules()
    {
        return [
            'project_id' => 'required|exists:projects,id',
            'assigned_to' => 'required|exists:users,id',
            'title.id' => 'required_without:title.en|string|nullable',
            'title.en' => 'required_without:title.id|string|nullable',
            'deadline_task' => 'required|date',
            'status' => 'required|in:pending,on_progress,review,revision,approved',
        ];
    }

    // --- RESET PAGINATION ---
    public function updatedGlobalSearch()
    {
        $this->resetPage();
    }
    public function updatedFilterProject()
    {
        $this->resetPage();
    }
    public function updatedFilterAssignee()
    {
        $this->resetPage();
    }
    public function updatedFilterStatus()
    {
        $this->resetPage();
    }

    // --- SEARCH LISTENERS (x-choices) ---
    public function searchProject(string $value = '')
    {
        $this->projectSearch = trim($value);
    }

    public function searchStaff(string $value = '')
    {
        $this->staffSearch = trim($value);
    }

    // --- CRUD ACTIONS ---
    public function create()
    {
        $this->reset(['editingJobdeskId', 'title', 'description', 'deadline_task', 'status', 'project_id', 'assigned_to', 'projectSearch', 'staffSearch']);
        $this->title = ['id' => '', 'en' => '']; // Reset array explicitly
        $this->description = ['id' => '', 'en' => ''];
        $this->status = 'pending';
        $this->modalOpen = true;
    }

    public function edit(Jobdesk $jobdesk)
    {
        $this->editingJobdeskId = $jobdesk->id;
        $this->project_id = $jobdesk->project_id;
        $this->assigned_to = $jobdesk->assigned_to;

        // Pastikan ambil data JSON dengan benar, fallback ke string kosong jika null
        $t = $jobdesk->title;
        $d = $jobdesk->description;

        $this->title = [
            'id' => is_array($t) ? ($t['id'] ?? '') : $t,
            'en' => is_array($t) ? ($t['en'] ?? '') : '',
        ];

        $this->description = [
            'id' => is_array($d) ? ($d['id'] ?? '') : $d,
            'en' => is_array($d) ? ($d['en'] ?? '') : '',
        ];

        $this->deadline_task = $jobdesk->deadline_task ? Carbon::parse($jobdesk->deadline_task)->format('Y-m-d\TH:i') : '';
        $this->status = $jobdesk->status;

        $this->projectSearch = '';
        $this->staffSearch = '';

        $this->modalOpen = true;
    }

    public function save()
    {
        $this->validate();

        // Siapkan DTO (Data Transfer Object)
        // Pastikan Anda sudah membuat Class DTO ini di App\DTOs\Jobdesk\JobdeskData
        // Jika belum pakai DTO, bisa langsung array
        $data = new JobdeskData(
            project_id: (int) $this->project_id,
            assigned_to: (int) $this->assigned_to,
            created_by: auth()->id(), // Tambahkan created_by
            title: $this->title,
            description: $this->description,
            deadline_task: $this->deadline_task,
            status: $this->status
        );

        if ($this->editingJobdeskId) {
            $jobdesk = Jobdesk::findOrFail($this->editingJobdeskId);
            // Panggil Action Update
            app(UpdateJobdeskAction::class)->execute($jobdesk, $data);
            $this->success('Jobdesk updated successfully.');
        } else {
            // Panggil Action Create
            app(CreateJobdeskAction::class)->execute($data);
            $this->success('Jobdesk created successfully.');
        }

        $this->modalOpen = false;
    }

    public function confirmDelete($id)
    {
        $this->jobdeskToDeleteId = $id;
        $this->deleteModalOpen = true;
    }

    public function delete()
    {
        if ($this->jobdeskToDeleteId) {
            Jobdesk::destroy($this->jobdeskToDeleteId);
            $this->success('Jobdesk deleted successfully.');
        }
        $this->deleteModalOpen = false;
    }

    public function clearFilters()
    {
        $this->reset(['filterProject', 'filterAssignee', 'filterStatus', 'filterDateStart', 'filterDateEnd', 'globalSearch']);
        $this->resetPage();
        $this->success('Filters cleared.');
    }

    public function render()
    {
        // ---------------------------------------------------------
        // 1. DATA PROJECT (Dropdown) - FIX ARRAY TO STRING
        // ---------------------------------------------------------
        $projectsQuery = Project::query();

        if ($this->projectSearch) {
            $term = "%{$this->projectSearch}%";
            $projectsQuery->where(function ($q) use ($term) {
                $q->where('name->id', 'like', $term)
                    ->orWhere('name->en', 'like', $term);
            });
        }

        $projects = $projectsQuery
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get()
            ->map(function ($p) {
                // Konversi Array Name ke String agar tidak error di x-choices
                $name = $p->name;
                if (is_array($name)) {
                    $name = $name['id'] ?? $name['en'] ?? 'Unknown Project';
                }
                return [
                    'id' => $p->id,
                    'name' => $name // Ini sekarang STRING
                ];
            }); // Tidak perlu ->toArray() jika x-choices support collection, tapi array lebih aman.

        // ---------------------------------------------------------
        // 2. DATA STAFF (Dropdown)
        // ---------------------------------------------------------
        $staffs = User::where('role', 'staff')
            ->when($this->staffSearch, fn($q) => $q->where('name', 'like', "%{$this->staffSearch}%"))
            ->orderBy('name')
            ->take(20)
            ->get()
            ->map(fn($u) => ['id' => $u->id, 'name' => $u->name]);

        // ---------------------------------------------------------
        // 3. QUERY JOBDESK (Main Table)
        // ---------------------------------------------------------
        $jobdesks = Jobdesk::query()
            ->with(['project', 'assignee', 'creator'])

            // Global Search (Handling Translatable JSON)
            ->where(function (Builder $q) {
                if ($this->globalSearch) {
                    $term = "%{$this->globalSearch}%";
                    $q->where('title->id', 'like', $term)
                        ->orWhere('title->en', 'like', $term)
                        ->orWhereHas('project', function ($pq) use ($term) {
                            $pq->where('name->id', 'like', $term)
                                ->orWhere('name->en', 'like', $term);
                        })
                        ->orWhereHas('assignee', fn($u) => $u->where('name', 'like', $term));
                }
            })

            // Filters
            ->when($this->filterProject, fn($q) => $q->where('project_id', $this->filterProject))
            ->when($this->filterAssignee, fn($q) => $q->where('assigned_to', $this->filterAssignee))
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
            ->when($this->filterDateStart, fn($q) => $q->whereDate('deadline_task', '>=', $this->filterDateStart))
            ->when($this->filterDateEnd, fn($q) => $q->whereDate('deadline_task', '<=', $this->filterDateEnd))

            ->latest()
            ->paginate(10);

        return view('livewire.admin.jobdesk.index', [
            'jobdesks' => $jobdesks,
            'projectsList' => $projects, // Variable ini dikirim ke view untuk x-choices
            'staffsList' => $staffs,
        ]);
    }
}
