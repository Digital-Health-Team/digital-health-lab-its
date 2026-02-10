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
        ]);

        // Auto login setelah register
        Auth::login($user);

        // Trigger event agar email verifikasi terkirim
        event(new Registered($user));

        return $user;
    }
}
