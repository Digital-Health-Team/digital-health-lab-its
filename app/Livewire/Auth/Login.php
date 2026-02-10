<?php

namespace App\Livewire\Auth;

use App\Actions\Auth\LoginAction;
use App\DTOs\Auth\LoginData;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Title;
use Livewire\Component;
use Mary\Traits\Toast;
use Illuminate\Validation\ValidationException;

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

            // Redirect sesuai role (Logic redirect bisa dipisah jika kompleks)
            return redirect()->intended(route('admin.dashboard'));

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
