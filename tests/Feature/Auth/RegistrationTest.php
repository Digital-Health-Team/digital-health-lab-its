<?php

use App\Livewire\Auth\Register;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;
use Livewire\Livewire;

beforeEach(function () {
    Role::insert([
        ['id' => 3, 'name' => 'mahasiswa'],
        ['id' => 4, 'name' => 'user_publik'],
    ]);
});

test('registration screen can be rendered', function () {
    $this->get(route('register'))->assertOk();
});

test('nextStep advances to step 2 with valid step 1 data', function () {
    $mahasiswaRole = Role::where('name', 'mahasiswa')->first();

    Livewire::test(Register::class)
        ->set('name', 'Budi Santoso')
        ->set('email', 'budi@example.com')
        ->set('role_id', $mahasiswaRole->id)
        ->set('password', 'password123')
        ->call('nextStep')
        ->assertHasNoErrors()
        ->assertSet('currentStep', 2);
});

test('nextStep stays on step 1 when required fields are missing', function () {
    Livewire::test(Register::class)
        ->set('name', '')
        ->set('email', '')
        ->set('role_id', '')
        ->set('password', '')
        ->call('nextStep')
        ->assertHasErrors(['name', 'email', 'role_id', 'password'])
        ->assertSet('currentStep', 1);
});

test('prevStep goes back to step 1', function () {
    $mahasiswaRole = Role::where('name', 'mahasiswa')->first();

    Livewire::test(Register::class)
        ->set('currentStep', 2)
        ->call('prevStep')
        ->assertSet('currentStep', 1);
});

test('mahasiswa registration requires NIM, university, and faculty', function () {
    $mahasiswaRole = Role::where('name', 'mahasiswa')->first();

    Livewire::test(Register::class)
        ->set('name', 'Budi Santoso')
        ->set('email', 'budi@example.com')
        ->set('role_id', $mahasiswaRole->id)
        ->set('password', 'password123')
        ->set('currentStep', 2)
        ->call('register')
        ->assertHasErrors(['nim', 'university', 'faculty']);
});

test('mahasiswa can register and creates profile with required fields', function () {
    Event::fake();

    $mahasiswaRole = Role::where('name', 'mahasiswa')->first();

    Livewire::test(Register::class)
        ->set('name', 'Budi Santoso')
        ->set('email', 'budi@example.com')
        ->set('role_id', $mahasiswaRole->id)
        ->set('password', 'password123')
        ->set('nim', '5031201013')
        ->set('university', 'ITS')
        ->set('faculty', 'FTEIC')
        ->set('department', 'Teknologi Kedokteran')
        ->call('register')
        ->assertRedirect(route('verification.notice'));

    $user = User::where('email', 'budi@example.com')->first();
    expect($user)->not->toBeNull()
        ->and($user->role_id)->toBe($mahasiswaRole->id);

    $profile = $user->profile;
    expect($profile)->not->toBeNull()
        ->and($profile->full_name)->toBe('Budi Santoso')
        ->and($profile->nim)->toBe('5031201013')
        ->and($profile->university)->toBe('ITS')
        ->and($profile->faculty)->toBe('FTEIC');

    Event::assertDispatched(Registered::class);
});

test('public user can register without profile fields', function () {
    Event::fake();

    $publicRole = Role::where('name', 'user_publik')->first();

    Livewire::test(Register::class)
        ->set('name', 'Siti Rahma')
        ->set('email', 'siti@example.com')
        ->set('role_id', $publicRole->id)
        ->set('password', 'password123')
        ->call('register')
        ->assertRedirect(route('verification.notice'));

    $user = User::where('email', 'siti@example.com')->first();
    expect($user)->not->toBeNull()
        ->and($user->role_id)->toBe($publicRole->id);

    Event::assertDispatched(Registered::class);
});

test('public user with NIK registers successfully', function () {
    Event::fake();

    $publicRole = Role::where('name', 'user_publik')->first();

    Livewire::test(Register::class)
        ->set('name', 'Siti Rahma')
        ->set('email', 'siti@example.com')
        ->set('role_id', $publicRole->id)
        ->set('password', 'password123')
        ->set('nik', '3201234567890001')
        ->set('phone', '081234567890')
        ->call('register')
        ->assertRedirect(route('verification.notice'));

    $profile = User::where('email', 'siti@example.com')->first()->profile;
    expect($profile->nik)->toBe('3201234567890001')
        ->and($profile->phone)->toBe('081234567890');
});

test('registration fails with missing required fields', function () {
    Livewire::test(Register::class)
        ->call('register')
        ->assertHasErrors(['name', 'email', 'role_id', 'password']);
});

test('registration fails with duplicate email', function () {
    $mahasiswaRole = Role::where('name', 'mahasiswa')->first();
    User::create([
        'name' => 'Existing User',
        'email' => 'existing@example.com',
        'password' => bcrypt('password'),
        'role_id' => $mahasiswaRole->id,
    ]);

    Livewire::test(Register::class)
        ->set('name', 'Another User')
        ->set('email', 'existing@example.com')
        ->set('role_id', $mahasiswaRole->id)
        ->set('password', 'password123')
        ->call('nextStep')
        ->assertHasErrors(['email']);
});
