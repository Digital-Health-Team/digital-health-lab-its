<?php

namespace App\Livewire\Admin;

use App\Models\Announcement;
use App\Models\Attendance;
use App\Models\Jobdesk;
use App\Models\Project;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Search Results')]
class GlobalSearch extends Component
{
    #[Url(as: 'q')]
    public string $search = '';

    // --- FILTERS (Advanced Filtering per category) ---
    public string $filterUserRole = '';
    public string $filterProjectStatus = '';
    public string $filterTaskStatus = '';
    public string $filterAttDate = '';

    public function mount()
    {
        // Ambil query dari URL jika ada
        $this->search = request()->query('q', '');
    }

    public function render()
    {
        $term = trim($this->search);

        // 1. Search Users
        $users = collect();
        if ($term) {
            $users = User::query()
                ->where('name', 'like', "%$term%")
                ->orWhere('email', 'like', "%$term%")
                // Advanced Filter: Role
                ->when($this->filterUserRole, fn($q) => $q->where('role', $this->filterUserRole))
                ->limit(10)
                ->get();
        }

        // 2. Search Projects
        $projects = collect();
        if ($term) {
            $projects = Project::query()
                ->where(function ($q) use ($term) {
                    $q->where('name->id', 'like', "%$term%")
                        ->orWhere('name->en', 'like', "%$term%");
                })
                // Advanced Filter: Status
                ->when($this->filterProjectStatus, fn($q) => $q->where('status', $this->filterProjectStatus))
                ->limit(10)
                ->get();
        }

        // 3. Search Jobdesks (Tasks)
        $tasks = collect();
        if ($term) {
            $tasks = Jobdesk::with(['project', 'assignee'])
                ->where(function ($q) use ($term) {
                    $q->where('title->id', 'like', "%$term%")
                        ->orWhere('title->en', 'like', "%$term%")
                        ->orWhereHas('assignee', fn($u) => $u->where('name', 'like', "%$term%"));
                })
                // Advanced Filter: Status
                ->when($this->filterTaskStatus, fn($q) => $q->where('status', $this->filterTaskStatus))
                ->limit(10)
                ->get();
        }

        // 4. Search Attendance
        // Mencari berdasarkan nama staff atau tanggal
        $attendances = collect();
        if ($term) {
            $attendances = Attendance::with('user')
                ->whereHas('user', fn($q) => $q->where('name', 'like', "%$term%"))
                // Advanced Filter: Date
                ->when($this->filterAttDate, fn($q) => $q->whereDate('check_in', $this->filterAttDate))
                ->limit(10)
                ->get();
        }

        // 5. Search Announcements
        $announcements = collect();
        if ($term) {
            $announcements = Announcement::query()
                ->where('title', 'like', "%$term%")
                ->orWhere('content', 'like', "%$term%")
                ->limit(5)
                ->get();
        }

        return view('livewire.admin.global-search', [
            'users' => $users,
            'projects' => $projects,
            'tasks' => $tasks,
            'attendances' => $attendances,
            'announcements' => $announcements
        ]);
    }
}
