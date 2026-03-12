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

        return view('livewire.admin.global-search', [
            'users' => $users,
        ]);
    }
}
