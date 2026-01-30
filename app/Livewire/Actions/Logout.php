<?php

namespace App\Livewire\Actions;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class Logout extends Component
{
    public function logout()
    {
        Auth::guard('web')->logout();

        Session::invalidate();
        Session::regenerateToken();

        return redirect('/');
    }

    public function render()
    {
        return <<<'HTML'
        <x-menu-item title="Log Out" icon="o-arrow-right-start-on-rectangle" wire:click="logout" />
        HTML;
    }
}
