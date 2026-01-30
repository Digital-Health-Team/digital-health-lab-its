<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Title;
use Livewire\Component;
use Mary\Traits\Toast;

#[Layout('layouts.auth.layout')]
#[Title('Login Page')]
class Login extends Component
{
    use Toast;

    #[Validate('required|email')]
    public string $email = '';

    #[Validate('required')]
    public string $password = '';

    public bool $remember = false;

    public function login(\App\Services\AuthService $authService)
    {
        $this->validate();

        if ($authService->login($this->email, $this->password, $this->remember)) {
            session()->flash('Login Sukses!', 'Selamat datang kembali pengguna!');

            return redirect()->intended(route($authService->getRedirectRoute()));
        }

        $this->addError('email', trans('auth.failed'));
        $this->error('Login Gagal!', 'Email atau Password salah.', position: 'toast-top');
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
