<?php

namespace App\Livewire\Dosen;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app.layout')]
class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.dosen.dashboard');
    }
}
