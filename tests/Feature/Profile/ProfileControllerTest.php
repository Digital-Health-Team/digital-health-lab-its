<?php

use App\Models\Role;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Role::insert([
        ['id' => 3, 'name' => 'mahasiswa'],
        ['id' => 4, 'name' => 'user_publik'],
    ]);
});

function makeUser(string $roleName, array $overrides = []): User
{
    $role = Role::where('name', $roleName)->first();

    return User::create(array_merge([
        'name' => 'Test User',
        'email' => fake()->unique()->safeEmail(),
        'password' => Hash::make('password'),
        'role_id' => $role->id,
        'email_verified_at' => now(),
    ], $overrides));
}

// ── GET /profile ──────────────────────────────────────────

test('guest is redirected to login when visiting profile page', function () {
    $this->get('/profile')->assertRedirect(route('login'));
});

test('authenticated user can view profile page', function () {
    $user = makeUser('user_publik');

    $this->actingAs($user)
        ->get('/profile')
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Features/Dashboard/Pages/ProfilePage')
            ->has('profile')
            ->where('profile.email', $user->email)
        );
});

test('profile page includes all expected data keys', function () {
    $user = makeUser('mahasiswa');
    UserProfile::create([
        'user_id' => $user->id,
        'full_name' => 'Budi Santoso',
        'nim' => '5031201013',
        'nik' => '3578123456789001',
        'university' => 'ITS',
        'faculty' => 'FTEIC',
        'department' => 'Teknologi Kedokteran',
        'phone' => '081234567890',
        'address' => 'Surabaya',
    ]);

    $this->actingAs($user)
        ->get('/profile')
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('profile.role', 'mahasiswa')
            ->where('profile.nim', '5031201013')
            ->where('profile.nik', '3578123456789001')
            ->where('profile.university', 'ITS')
            ->where('profile.faculty', 'FTEIC')
        );
});

// ── PUT /profile ──────────────────────────────────────────

test('user_publik can update their profile', function () {
    $user = makeUser('user_publik', ['name' => 'Old Name']);

    $this->actingAs($user)
        ->put('/profile', [
            'name' => 'New Name',
            'email' => $user->email,
            'nik' => '3578000000000001',
        ])
        ->assertRedirect();

    expect($user->fresh()->name)->toBe('New Name');

    $profile = $user->fresh()->profile;
    expect($profile)->not->toBeNull()
        ->and($profile->nik)->toBe('3578000000000001');
});

test('mahasiswa can update profile with all required fields', function () {
    $user = makeUser('mahasiswa');

    $this->actingAs($user)
        ->put('/profile', [
            'name' => 'Budi Santoso',
            'email' => $user->email,
            'nik' => '3578000000000001',
            'nim' => '5031201013',
            'university' => 'ITS',
            'faculty' => 'FTEIC',
            'department' => 'Teknologi Kedokteran',
            'phone' => '081234567890',
            'address' => 'Surabaya',
        ])
        ->assertRedirect();

    $profile = $user->fresh()->profile;
    expect($profile->nim)->toBe('5031201013')
        ->and($profile->university)->toBe('ITS')
        ->and($profile->faculty)->toBe('FTEIC');
});

test('mahasiswa update fails without NIM', function () {
    $user = makeUser('mahasiswa');

    $this->actingAs($user)
        ->put('/profile', [
            'name' => 'Budi Santoso',
            'email' => $user->email,
            'nik' => '3578000000000001',
            'nim' => '',
            'university' => 'ITS',
            'faculty' => 'FTEIC',
        ])
        ->assertSessionHasErrors(['nim']);
});

test('mahasiswa update fails without university and faculty', function () {
    $user = makeUser('mahasiswa');

    $this->actingAs($user)
        ->put('/profile', [
            'name' => 'Budi Santoso',
            'email' => $user->email,
            'nik' => '3578000000000001',
            'nim' => '5031201013',
        ])
        ->assertSessionHasErrors(['university', 'faculty']);
});

test('update fails when NIK is missing', function () {
    $user = makeUser('user_publik');

    $this->actingAs($user)
        ->put('/profile', [
            'name' => 'Siti Rahma',
            'email' => $user->email,
        ])
        ->assertSessionHasErrors(['nik']);
});

test('update fails with duplicate email', function () {
    $user = makeUser('user_publik');
    makeUser('user_publik', ['email' => 'taken@example.com']);

    $this->actingAs($user)
        ->put('/profile', [
            'name' => 'Siti Rahma',
            'email' => 'taken@example.com',
            'nik' => '3578000000000001',
        ])
        ->assertSessionHasErrors(['email']);
});

test('user can upload a profile photo', function () {
    Storage::fake('public');

    $user = makeUser('user_publik');
    $photo = UploadedFile::fake()->image('avatar.jpg', 100, 100);

    $this->actingAs($user)
        ->put('/profile', [
            'name' => $user->name,
            'email' => $user->email,
            'nik' => '3578000000000001',
            'profile_photo' => $photo,
        ])
        ->assertRedirect();

    Storage::disk('public')->assertExists('profile_pictures/'.$photo->hashName());
});

test('guest cannot update profile', function () {
    $this->put('/profile', [
        'name' => 'Hacker',
        'email' => 'hacker@example.com',
        'nik' => '0000000000000000',
    ])->assertRedirect(route('login'));
});
