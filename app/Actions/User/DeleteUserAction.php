<?php

namespace App\Actions\User;

use App\Models\User;

class DeleteUserAction
{
    public function execute(User $user): void
    {
        // Proteksi: Admin tidak boleh menghapus dirinya sendiri
        if ($user->id === auth()->id()) {
            throw new \Exception("Anda tidak bisa menghapus akun sendiri.");
        }

        $user->delete();
    }
}
