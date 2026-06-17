<?php

namespace App\Actions\Auth;

use App\DTOs\Auth\RegisterData;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterUserAction
{
    public function execute(RegisterData $data): User
    {
        $user = User::create([
            'name' => $data->name,
            'email' => $data->email,
            'password' => Hash::make($data->password),
            'role_id' => $data->role_id,
            'profile_photo' => $data->profile_photo,
        ]);

        $user->profile()->create([
            'full_name' => $data->name,
            'nim' => $data->nim,
            'nik' => $data->nik,
            'university' => $data->university,
            'faculty' => $data->faculty,
            'department' => $data->department,
            'phone' => $data->phone,
            'address' => $data->address,
        ]);

        Auth::login($user);

        event(new Registered($user));

        return $user;
    }
}
