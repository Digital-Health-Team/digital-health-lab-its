<?php

namespace App\Livewire\Auth;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Support\Facades\Password;
use Mary\Traits\Toast;

#[Layout('layouts.auth.layout')]
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
