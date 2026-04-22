<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\Project;
use App\Models\Jobdesk;
use App\Models\Attendance;
use App\Models\Announcement;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Admin Dashboard')]
class Dashboard extends Component
{
    public function render()
    {
        // 1. STATISTIK UTAMA
        $stats = [
            'total_staff' => User::all(),
        ];

        // 4. USER TERBARU
        $recentUsers = User::latest()->take(5)->get();


        return view('livewire.admin.dashboard', [
            'stats' => $stats,
            'recentUsers' => $recentUsers,
        ]);
    }
}
