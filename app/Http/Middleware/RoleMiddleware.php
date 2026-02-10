<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        $userRole = $request->user()->role;

        // If no roles are passed, just check if logged in (already done above)
        if (empty($roles)) {
            return $next($request);
        }

        // Check if user has one of the allowed roles
        if (in_array($userRole, $roles)) {
            return $next($request);
        }

        // Redirect based on role
        return match ($userRole) {
            'admin' => redirect()->route('admin.dashboard'),
            // 'user' => redirect()->route('mahasiswa.dashboard'),
            default => abort(403),
        };
    }
}
