<?php

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Support\Facades\Session;

class UpdateUserTimezoneAction
{
    public function execute(?User $user, string $timezone): void
    {
        // 1. Validasi: Pastikan timezone valid (misal "Asia/Jakarta")
        if (!in_array($timezone, timezone_identifiers_list())) {
            return;
        }

        // 2. Simpan di Session (agar berlaku langsung tanpa perlu query DB terus)
        Session::put('timezone', $timezone);

        // 3. Simpan ke Database (jika user sedang login dan timezone-nya beda)
        if ($user && $user->timezone !== $timezone) {
            $user->update(['timezone' => $timezone]);
        }
    }
}
