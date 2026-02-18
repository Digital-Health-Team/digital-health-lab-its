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

        // 1. Parsing Roles untuk mendukung format 'staff|freelance'
        // Jika route menggunakan 'role:staff|freelance', Laravel mengirimnya sebagai satu string dalam array.
        $allowedRoles = [];
        foreach ($roles as $role) {
            // Pecah string berdasarkan '|' dan merge ke array allowedRoles
            $allowedRoles = array_merge($allowedRoles, explode('|', $role));
        }

        // 2. Jika tidak ada role yang didefinisikan, atau user punya role yang sesuai
        if (empty($allowedRoles) || in_array($userRole, $allowedRoles)) {
            return $next($request);
        }

        // 3. Tentukan Route Tujuan Berdasarkan Role User Saat Ini
        $targetRoute = match ($userRole) {
            'super_admin' => 'admin.dashboard',
            'pm' => 'pm.dashboard',
            'staff' => 'user.dashboard',
            'freelance' => 'user.dashboard',
            default => null,
        };

        // 4. CEGAH LOOP REDIRECT (Fix Too Many Redirects)
        // Jika user sudah berada di route tujuannya sendiri, tapi masih ditolak aksesnya
        // (artinya konfigurasi middleware di route tersebut salah/ketat),
        // maka jangan redirect lagi, melainkan Abort 403.
        if ($targetRoute && $request->routeIs($targetRoute)) {
            abort(403, 'Unauthorized access to this dashboard.');
        }

        // Jika target route ditemukan dan user belum di sana, redirect.
        if ($targetRoute) {
            return redirect()->route($targetRoute);
        }

        // Default jika tidak ada match
        abort(403);
    }
}
