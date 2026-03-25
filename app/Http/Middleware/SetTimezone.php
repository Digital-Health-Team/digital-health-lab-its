<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetTimezone
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Default fallback (ambil dari config/app.php)
        $timezone = config('app.timezone');

        // 2. Prioritas 1: Ambil dari Session
        if (Session::has('timezone')) {
            $timezone = Session::get('timezone');
        }
        // 3. Prioritas 2: Ambil dari Database (jika session hilang)
        elseif (auth()->check() && auth()->user()->timezone) {
            $timezone = auth()->user()->timezone;
            Session::put('timezone', $timezone); // Sync kembali ke session
        }

        // --- PERBAIKAN DI SINI ---
        // 4. Terapkan zona waktu ke sistem PHP bawaan
        date_default_timezone_set($timezone);

        // 5. Override config Laravel agar helper now(), today(), dan Carbon otomatis mengikuti
        config(['app.timezone' => $timezone]);

        return $next($request);
    }
}
