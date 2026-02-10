<?php

namespace App\Actions\User;

use App\DTOs\User\UserData;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UpdateUserAction
{
    public function execute(User $user, UserData $data): User
    {
        $updateData = [
            'name'  => $data->name,
            'email' => $data->email,
            'role'  => $data->role,
        ];

        // Hanya update password jika diisi
        if (!empty($data->password)) {
            $updateData['password'] = Hash::make($data->password);
        }

        $user->update($updateData);

        return $user;
    }
}
