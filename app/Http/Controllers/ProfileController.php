<?php

namespace App\Http\Controllers;

use App\Actions\User\UpdateUserAction;
use App\DTOs\User\UserData;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Inertia\Response;

class ProfileController extends Controller
{
    public function show(): Response
    {
        /** @var User $user */
        $user = auth()->user()->load('profile', 'role');

        return inertia('Features/Dashboard/Pages/ProfilePage', [
            'profile' => [
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role?->name,
                'avatar' => $user->profile_photo
                    ? Storage::disk('public')->url($user->profile_photo)
                    : null,
                'full_name' => $user->profile?->full_name,
                'nim' => $user->profile?->nim,
                'nik' => $user->profile?->nik,
                'university' => $user->profile?->university,
                'faculty' => $user->profile?->faculty,
                'department' => $user->profile?->department,
                'phone' => $user->profile?->phone,
                'address' => $user->profile?->address,
            ],
        ]);
    }

    public function update(Request $request, UpdateUserAction $action): RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();
        $isMhs = $user->role?->name === 'mahasiswa';

        $validated = $request->validate([
            'name' => 'required|string|min:3|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'profile_photo' => 'nullable|image|max:2048',
            'nik' => 'required|string|max:20',
            'nim' => $isMhs ? 'required|string|max:50' : 'nullable|string|max:50',
            'university' => $isMhs ? 'required|string|max:255' : 'nullable|string|max:255',
            'faculty' => $isMhs ? 'required|string|max:255' : 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        $data = new UserData(
            full_name: $validated['name'],
            email: $validated['email'],
            role_id: $user->role_id,
            profile_photo: $request->hasFile('profile_photo')
                ? $request->file('profile_photo')
                : null,
            nik: $validated['nik'] ?? null,
            nim: $validated['nim'] ?? null,
            university: $validated['university'] ?? null,
            faculty: $validated['faculty'] ?? null,
            department: $validated['department'] ?? null,
            phone: $validated['phone'] ?? null,
            address: $validated['address'] ?? null,
        );

        // Sync users.name alongside profile.full_name
        $user->update(['name' => $validated['name']]);

        $action->execute($user, $data);

        return back()->with('success', 'Profil berhasil diperbarui!');
    }
}
