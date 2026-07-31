<?php

namespace App\Livewire\Auth;

use App\Actions\Auth\LoginAction;
use App\DTOs\Auth\LoginData;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Mary\Traits\Toast;

#[Layout('layouts.guest')]
class Login extends Component
{
    use Toast;

    #[Validate('required|email')]
    public string $email = '';

    #[Validate('required')]
    public string $password = '';

    public bool $remember = false;

    public function login(LoginAction $action)
    {
        $this->validate();

        try {
            // Bungkus data ke DTO
            $data = new LoginData($this->email, $this->password, $this->remember);

            // Eksekusi Action
            $action->execute($data);

            session()->flash('success', __('Welcome back!'));
            session()->flash('show_welcome', true);

            $redirectRoute = match (auth()->user()->role?->name) {
                'super_admin' => route('super-admin.dashboard'),
                'admin_lab' => route('admin.dashboard'),
                'admin_gudang' => route('gudang.dashboard'),
                default => route('user.dashboard'),
            };

            return redirect()->intended($redirectRoute);

        } catch (ValidationException $e) {
            $this->addError('email', $e->getMessage());
            $this->error(__('Login Failed!'), __('Incorrect email or password.'), position: 'toast-top');
        }
    }

    public function render()
    {
        // __() cannot live in a #[Title] attribute — PHP attribute args must be constant expressions.
        return view('livewire.auth.login')->title(__('Login'));
    }
}
