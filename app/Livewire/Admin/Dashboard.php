<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\Project;
use App\Models\Attendance;
use App\Models\Jobdesk;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Dashboard Admin')]
class Dashboard extends Component
{
    public function render()
    {
        // Data Statistik (Bisa dipindah ke Service jika query makin berat)
        $stats = [
            'total_users' => User::count(),
        ];
        return view('livewire.admin.dashboard', [
            'stats' => $stats,
        ]);
    }
}
