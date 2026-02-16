<?php

namespace App\Actions\User;

use App\DTOs\User\UserData;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\UploadedFile;

class CreateUserAction
{
    public function execute(UserData $data)
    {
        $userData = [
            'name' => $data->name,
            'email' => $data->email,
            'role' => $data->role,
            'password' => bcrypt($data->password),
        ];

        // Pastikan profile_photo adalah instance dari UploadedFile sebelum memanggil store()
        if ($data->profile_photo && $data->profile_photo instanceof UploadedFile) {
            $userData['profile_picture'] = $data->profile_photo->store('profile_picture', 'public');
        }

        return User::create($userData);
    }
}
