<?php

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Support\Facades\Storage;

class DeleteUserAction
{
    public function execute(User $user): void
    {
        // Proteksi: Admin tidak boleh menghapus dirinya sendiri
        if ($user->id === auth()->id()) {
            throw new \Exception("Anda tidak bisa menghapus akun sendiri.");
        }

        // Delete profile photo from storage
        if ($user->profile_photo && Storage::exists($user->profile_photo)) {
            Storage::delete($user->profile_photo);
        }

        $user->delete();
    }
}
