<?php

namespace App\Actions\Auth;

use Illuminate\Auth\Events\Verified;
use App\Models\User;

class VerifyEmailAction
{
    public function execute(User $user): void
    {
        if ($user->hasVerifiedEmail()) {
            return;
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }
    }
}
