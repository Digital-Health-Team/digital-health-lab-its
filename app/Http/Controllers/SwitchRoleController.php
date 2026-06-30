<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SwitchRoleController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        $role = $request->input('role');
        $user = $request->user();
        $user->loadMissing('roles');

        abort_unless($user->roles->contains('name', $role), 403, 'Role not assigned to this user.');

        session(['active_role' => $role]);

        $dashboard = match ($role) {
            'super_admin' => 'super-admin.dashboard',
            'admin_lab' => 'admin.dashboard',
            'admin_gudang' => 'gudang.dashboard',
            default => 'user.dashboard',
        };

        return redirect()->route($dashboard);
    }
}
