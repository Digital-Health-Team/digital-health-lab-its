<?php

namespace App\Livewire\Actions;

use App\Actions\Auth\LogoutAction;
use Livewire\Component;

class Logout extends Component
{
    public function logout(LogoutAction $action)
    {
        // 1. Panggil Action
        $action->execute();

        // 2. Redirect ke halaman login/home
        return redirect('/');
    }

    public function render()
    {
        return <<<'HTML'
        {{--
            Gunakan 'no-wire-navigate' pada link/tombol logout
            agar halaman melakukan full refresh dan session benar-benar bersih.
        --}}
        <x-menu-item
            title="Log Out"
            icon="o-arrow-right-start-on-rectangle"
            wire:click="logout"
            no-wire-navigate
        />
        HTML;
    }
}
