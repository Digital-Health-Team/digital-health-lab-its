<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.auth.layout')]
#[Title('Login')]
class Login extends Component
{
    #[Rule('required|email')]
    public string $email = '';

    #[Rule('required')]
    public string $password = '';

    public bool $remember = false;

    public function login()
    {
        $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            session()->regenerate();

            return redirect()->intended(route(match (Auth::user()->role) {
                'super_admin' => 'admin.dashboard',
                'dosen' => 'dosen.dashboard',
                'mahasiswa' => 'mahasiswa.dashboard',
                default => 'home',
            }));
        }

        $this->addError('email', trans('auth.failed'));
        $this->password = '';
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
