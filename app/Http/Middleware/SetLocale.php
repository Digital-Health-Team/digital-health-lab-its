<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $locale = config('app.locale'); // Default (en/id)

        // 1. Cek Session (Prioritas Tertinggi - User baru ganti bahasa)
        if (Session::has('locale')) {
            $locale = Session::get('locale');
        }
        // 2. Cek Database (Jika user login tapi session habis)
        elseif (auth()->check() && auth()->user()->locale) {
            $locale = auth()->user()->locale;
            Session::put('locale', $locale); // Sync ke session
        }

        // Terapkan Bahasa
        App::setLocale($locale);

        return $next($request);
    }
}
