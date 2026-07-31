<?php

namespace App\Livewire\Auth;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Mary\Traits\Toast;

#[Layout('layouts.guest')] // Gunakan layout tamu/kosong
class VerifyEmail extends Component
{
    use Toast;

    public function resend()
    {
        if (auth()->user()->hasVerifiedEmail()) {
            return $this->redirect(route('user.dashboard'), navigate: true);
        }

        auth()->user()->sendEmailVerificationNotification();

        $this->success(__('A new verification link has been sent to your email.'));
    }

    public function logout()
    {
        auth()->logout();

        return $this->redirect(route('login'), navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.verify-email')->title(__('Email Verification'));
    }
}
