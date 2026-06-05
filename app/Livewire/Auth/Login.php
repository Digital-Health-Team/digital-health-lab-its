<?php

namespace App\Livewire\Auth;

use App\Actions\Auth\LoginAction;
use App\DTOs\Auth\LoginData;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Mary\Traits\Toast;

#[Layout('layouts.guest')]
#[Title('Login Page')]
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

            session()->flash('success', 'Selamat datang kembali!');

            $redirectRoute = match (auth()->user()->role?->name) {
                'super_admin' => route('super-admin.dashboard'),
                'admin_lab' => route('admin.dashboard'),
                'admin_gudang' => route('gudang.dashboard'),
                default => route('user.dashboard'),
            };

            return redirect()->intended($redirectRoute);

        } catch (ValidationException $e) {
            $this->addError('email', $e->getMessage());
            $this->error('Login Gagal!', 'Email atau password salah.', position: 'toast-top');
        }
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
