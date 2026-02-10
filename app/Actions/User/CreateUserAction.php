<?php

namespace App\Actions\User;

use App\DTOs\User\UserData;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateUserAction
{
    public function execute(UserData $data): User
    {
        return User::create([
            'name'     => $data->name,
            'email'    => $data->email,
            'role'     => $data->role,
            'password' => Hash::make($data->password),
        ]);
    }
}
