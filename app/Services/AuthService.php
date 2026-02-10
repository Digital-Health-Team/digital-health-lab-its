<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

// Models
use App\Models\User;

class AuthService
{
    /**
     * Attempt to authenticate the user.
     *
     * @param string $email
     * @param string $password
     * @param bool $remember
     * @return bool
     */
    public function login(string $email, string $password, bool $remember = false): bool
    {
        if (Auth::attempt(['email' => $email, 'password' => $password], $remember)) {
            session()->regenerate();
            session()->flash('success', 'Berhasil login! Selamat datang kembali.');
            return true;
        }

        return false;
    }

    public function register(array $data): ?User
    {
        // Buat User Baru
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'user', // Default role untuk pendaftar umum
        ]);

        // Otomatis login setelah register
        if ($user) {
            Auth::login($user);
            return $user;
        }

        return null;
    }

    /**
     * Get the redirect route based on user role.
     *
     * @return string
     */
    public function getRedirectRoute(): string
    {
        return match (Auth::user()->role) {
            'admin' => 'admin.dashboard',
            'user' => 'user.dashboard',
            default => 'home',
        };
    }

    /**
     * Log the user out.
     */
    public function logout(): void
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
    }
}
