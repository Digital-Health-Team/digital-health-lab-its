<?php

namespace App\Actions\User;

use App\Models\User;

class ToggleUserStatusAction
{
    public function execute(User $user): void
    {
        if ($user->id === auth()->id()) {
            throw new \Exception(__('You cannot deactivate your own account.'));
        }

        $user->update([
            'is_active' => !$user->is_active
        ]);
    }
}
