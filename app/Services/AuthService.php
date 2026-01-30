<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Auth;

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
            return true;
        }

        return false;
    }

    /**
     * Get the redirect route based on user role.
     *
     * @return string
     */
    public function getRedirectRoute(): string
    {
        return match (Auth::user()->role) {
            'super_admin' => 'admin.dashboard',
            'dosen' => 'dosen.dashboard',
            'mahasiswa' => 'mahasiswa.dashboard',
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
