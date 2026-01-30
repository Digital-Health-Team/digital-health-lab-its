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

    public function login(\App\Services\AuthService $authService)
    {
        $this->validate();

        if ($authService->login($this->email, $this->password, $this->remember)) {
            return redirect()->intended(route($authService->getRedirectRoute()));
        }

        $this->addError('email', trans('auth.failed'));
        $this->password = '';
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
