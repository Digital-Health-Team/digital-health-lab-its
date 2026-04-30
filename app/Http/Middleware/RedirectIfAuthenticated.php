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

                $user = Auth::user();
                $userRole = $user->role?->name;

                // Cek Role dan arahkan ke prefix masing-masing
                if ($userRole === 'super_admin') {
                    return redirect('/super-admin/dashboard');
                } elseif ($userRole === 'admin_lab') {
                    return redirect('/admin/dashboard');
                } elseif (in_array($userRole, ['mahasiswa', 'user_publik'])) {
                    return redirect('/user/dashboard');
                }

                // Fallback default jika role tidak dikenali
                return redirect('/dashboard');
            }
        }

        return $next($request);
    }
}
