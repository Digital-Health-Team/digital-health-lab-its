<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Mary\Traits\Toast;

#[Layout('layouts.guest')] // Gunakan layout tamu/kosong
#[Title('Verifikasi Email')]
class VerifyEmail extends Component
{
    use Toast;

    public function resend()
    {
        if (auth()->user()->hasVerifiedEmail()) {
            return $this->redirect(route('dashboard'), navigate: true);
        }

        auth()->user()->sendEmailVerificationNotification();

        $this->success('Link verifikasi baru telah dikirim ke email Anda.');
    }

    public function logout()
    {
        auth()->logout();
        return $this->redirect(route('login'), navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.verify-email');
    }
}
