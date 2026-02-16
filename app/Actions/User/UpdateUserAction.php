<?php

namespace App\Actions\User;

use App\Models\User;
use App\DTOs\User\UserData;
use Illuminate\Support\Facades\Storage;

class UpdateUserAction
{
    public function execute(User $user, UserData $data)
    {
        $userData = [
            'name' => $data->name,
            'email' => $data->email,
            'role' => $data->role,
        ];

        // CEK: Hanya panggil store() jika profile_photo adalah OBJEK file, bukan string
        if ($data->profile_photo && !is_string($data->profile_photo)) {
            // Hapus foto lama jika ada
            if ($user->profile_photo && Storage::exists($user->profile_photo)) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            // Simpan file baru
            $userData['profile_photo'] = $data->profile_photo->store('profile_photo', 'public');
        }

        if (!empty($data->password)) {
            $userData['password'] = bcrypt($data->password);
        }

        return $user->update($userData);
    }
}
