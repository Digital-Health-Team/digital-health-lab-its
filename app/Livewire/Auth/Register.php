<?php

namespace App\Livewire\Auth;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Title;
use Livewire\Component;
use App\Services\AuthService;
use Mary\Traits\Toast;

#[Layout('layouts.auth.layout')]
#[Title('Register Page')]
class Register extends Component
{
    use Toast;

    #[Validate('required|min:3|max:255')]
    public string $name = '';

    #[Validate('required|email|unique:users,email')]
    public string $email = '';

    #[Validate('required|min:6|confirmed')]
    // 'confirmed' akan otomatis mencari field bernama password_confirmation
    public string $password = '';

    #[Validate('required')]
    public string $password_confirmation = '';

    public function register(AuthService $authService)
    {
        $this->validate();

        try {
            $user = $authService->register([
                'name' => $this->name,
                'email' => $this->email,
                'password' => $this->password
            ]);

            if ($user) {
                session()->flash('success', 'Akun berhasil dibuat! Selamat datang.');
                return redirect()->intended(route($authService->getRedirectRoute()));
            }

        } catch (\Exception $e) {
            $this->error('Gagal Mendaftar', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
