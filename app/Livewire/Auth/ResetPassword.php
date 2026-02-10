<?php

namespace App\Livewire\Auth;

use App\Actions\Auth\ResetPasswordAction;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Title;
use Livewire\Component;
use Mary\Traits\Toast;
use Illuminate\Validation\ValidationException;

#[Layout('layouts.guest')]
#[Title('Reset Password')]
class ResetPassword extends Component
{
    use Toast;

    public $token;

    #[Validate('required|email')]
    public $email;

    #[Validate('required|min:8|confirmed')]
    public $password;

    #[Validate('required')]
    public $password_confirmation;

    public function mount($token)
    {
        $this->token = $token;
        $this->email = request()->query('email');
    }

    public function resetPassword(ResetPasswordAction $action)
    {
        $this->validate();

        try {
            $action->execute([
                'email' => $this->email,
                'password' => $this->password,
                'password_confirmation' => $this->password_confirmation,
                'token' => $this->token,
            ]);

            session()->flash('success', 'Password berhasil direset! Silakan login.');
            return redirect()->route('login');

        } catch (ValidationException $e) {
            $this->addError('email', $e->getMessage());
            $this->error('Gagal!', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.auth.reset-password');
    }
}
