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

        // Ambil nama role dari relasi tabel roles
        $userRole = $request->user()->role?->name;

        // 1. Parsing Roles untuk mendukung format 'super_admin|admin_lab'
        $allowedRoles = [];
        foreach ($roles as $role) {
            $allowedRoles = array_merge($allowedRoles, explode('|', $role));
        }

        // 2. Jika tidak ada role yang didefinisikan, atau user punya role yang sesuai, izinkan akses
        if (empty($allowedRoles) || in_array($userRole, $allowedRoles)) {
            return $next($request);
        }

        // 3. Tentukan Route Tujuan Berdasarkan Role User Saat Ini jika akses ditolak
        $targetRoute = match ($userRole) {
            'super_admin' => 'super-admin.dashboard',
            'admin_lab'   => 'admin.dashboard',
            'mahasiswa', 'user_publik' => 'user.dashboard',
            default => null,
        };

        // 4. CEGAH LOOP REDIRECT (Fix Too Many Redirects)
        if ($targetRoute && $request->routeIs($targetRoute)) {
            abort(403, 'Unauthorized access to this dashboard.');
        }

        // Jika target route ditemukan dan user belum di sana, arahkan ke dashboard masing-masing
        if ($targetRoute) {
            return redirect()->route($targetRoute);
        }

        // Default jika tidak ada match
        abort(403, 'Unauthorized access.');
    }
}
