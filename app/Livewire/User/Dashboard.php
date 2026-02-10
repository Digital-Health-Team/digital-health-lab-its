<?php

namespace App\Livewire\User;

use App\Models\Attendance;
use App\Models\Jobdesk;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Dashboard Saya')]
class Dashboard extends Component
{
    public function render()
    {
        $user = Auth::user();

        return view('livewire.user.dashboard',
        );
    }
}
