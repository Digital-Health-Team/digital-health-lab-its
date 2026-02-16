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

    // --- DROPDOWN SEARCH STATES (Untuk Filter & Form) ---
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

    public function rules()
    {
        return [
            'project_id' => 'required|exists:projects,id',
            'assigned_to' => 'required|exists:users,id',
            'title.id' => 'nullable|string',
            'deadline_task' => 'required|date',
            'status' => 'required',
        ];
    }

    // --- RESET PAGINATION SAAT FILTER BERUBAH ---
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
    public function updatedFilterDateStart()
    {
        $this->resetPage();
    }

    // --- SEARCH METHODS (Dipanggil x-choices saat mengetik) ---
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
        $this->modalOpen = true;
    }

    public function edit(Jobdesk $jobdesk)
    {
        $this->editingJobdeskId = $jobdesk->id;
        $this->project_id = $jobdesk->project_id;
        $this->assigned_to = $jobdesk->assigned_to;
        $this->title = $jobdesk->getTranslations('title');
        $this->description = $jobdesk->getTranslations('description');
        $this->deadline_task = $jobdesk->deadline_task ? Carbon::parse($jobdesk->deadline_task)->format('Y-m-d\TH:i') : '';
        $this->status = $jobdesk->status;

        // Reset search agar dropdown bersih saat dibuka
        $this->projectSearch = '';
        $this->staffSearch = '';

        $this->modalOpen = true;
    }

    public function save()
    {
        $this->validate();

        $data = new JobdeskData(
            project_id: $this->project_id,
            assigned_to: $this->assigned_to,
            title: $this->title,
            description: $this->description,
            deadline_task: $this->deadline_task,
            status: $this->status
        );

        if ($this->editingJobdeskId) {
            $jobdesk = Jobdesk::findOrFail($this->editingJobdeskId);
            app(UpdateJobdeskAction::class)->execute($jobdesk, $data);
            $this->success('Jobdesk updated successfully.');
        } else {
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
        $this->reset(['filterProject', 'filterAssignee', 'filterStatus', 'filterDateStart', 'filterDateEnd']);
        $this->resetPage();
        $this->success('All filters cleared.');
    }

    public function render()
    {
        // 1. Data Dropdown (Server-side Search)
        $projects = Project::query()
            ->when($this->projectSearch, fn($q) => $q->where('name', 'like', "%{$this->projectSearch}%"))
            ->orderBy('name')
            ->take(20) // Limit agar performa terjaga
            ->get()
            ->map(fn($p) => ['id' => $p->id, 'name' => $p->name])
            ->toArray();

        $staffs = User::where('role', 'staff')
            ->when($this->staffSearch, fn($q) => $q->where('name', 'like', "%{$this->staffSearch}%"))
            ->orderBy('name')
            ->take(20)
            ->get()
            ->map(fn($u) => ['id' => $u->id, 'name' => $u->name])
            ->toArray();

        // 2. Query Utama Jobdesk dengan Filter
        $jobdesks = Jobdesk::query()
            ->with(['project', 'assignee'])
            // Global Search (Text)
            ->where(function (Builder $q) {
                $term = "%{$this->globalSearch}%";
                $q->where('title->id', 'like', $term)
                    ->orWhere('title->en', 'like', $term)
                    ->orWhereHas('project', fn($sq) => $sq->where('name', 'like', $term))
                    ->orWhereHas('assignee', fn($sq) => $sq->where('name', 'like', $term));
            })
            // Advanced Filters
            ->when($this->filterProject, fn($q) => $q->where('project_id', $this->filterProject))
            ->when($this->filterAssignee, fn($q) => $q->where('assigned_to', $this->filterAssignee))
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
            ->when($this->filterDateStart, fn($q) => $q->whereDate('deadline_task', '>=', $this->filterDateStart))
            ->when($this->filterDateEnd, fn($q) => $q->whereDate('deadline_task', '<=', $this->filterDateEnd))

            ->latest()
            ->paginate(10);

        return view('livewire.admin.jobdesk.index', [
            'jobdesks' => $jobdesks,
            'projectsList' => $projects,
            'staffsList' => $staffs,
        ]);
    }
}
