<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Mary\Traits\Toast;

#[Layout('layouts.guest')]
#[Title('Lupa Password')]
class ForgotPassword extends Component
{
    use Toast;

    #[Validate('required|email')]
    public string $email = '';

    public function sendLink()
    {
        $this->validate();

        // Menggunakan Broker Password bawaan Laravel
        $status = Password::sendResetLink(['email' => $this->email]);

        if ($status === Password::RESET_LINK_SENT) {
            $this->success('Link Terkirim!', __($status));
            $this->reset('email');
        } else {
            $this->addError('email', __($status));
        }
    }

    public function render()
    {
        return view('livewire.auth.forgot-password');
    }
}
