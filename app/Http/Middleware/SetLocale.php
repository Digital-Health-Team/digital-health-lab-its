<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    private const SUPPORTED = ['en', 'id'];

    public function handle(Request $request, Closure $next): mixed
    {
        $locale = config('app.locale', 'en');

        if (Session::has('locale') && in_array(Session::get('locale'), self::SUPPORTED, true)) {
            $locale = Session::get('locale');
        } elseif (auth()->check()) {
            $user = auth()->user();
            $dbLocale = $user->locale ?? null;

            if ($dbLocale && in_array($dbLocale, self::SUPPORTED, true)) {
                $locale = $dbLocale;
                Session::put('locale', $locale);
            }
        }

        App::setLocale($locale);

        return $next($request);
    }
}
