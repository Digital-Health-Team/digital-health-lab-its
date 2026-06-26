<?php

namespace App\Livewire\User;

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('User Dashboard')]
class Dashboard extends Component
{
    public function render()
    {
        // 1. STATISTIK UTAMA
        $stats = [
            'total_staff' => User::where('role', 'staff')->count(),
        ];

        return view('livewire.user.dashboard', [
            'stats' => $stats,
        ]);
    }
}
