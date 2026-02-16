<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Cek Role (Opsional): Redirect ke dashboard sesuai role
                $user = Auth::user();
                if ($user->role === 'super_admin') {
                    return redirect('/admin/dashboard'); // Dashboard Admin
                } else if ($user->role === 'pm') {
                    return redirect('/admin/dashboard'); // Dashboard Staf
                } else if ($user->role === 'staff') {
                    return redirect('/user/dashboard'); // Dashboard Staf
                }

                return redirect('/dashboard'); // Dashboard Staf
            }
        }

        return $next($request);
    }
}
