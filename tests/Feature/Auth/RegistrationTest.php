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
    Livewire::test(Register::class)
        ->set('name', 'Budi Santoso')
        ->set('email', 'budi@example.com')
        ->set('password', 'password123')
        ->call('nextStep')
        ->assertHasNoErrors()
        ->assertSet('currentStep', 2);
});

test('nextStep stays on step 1 when required fields are missing', function () {
    Livewire::test(Register::class)
        ->set('name', '')
        ->set('email', '')
        ->set('password', '')
        ->call('nextStep')
        ->assertHasErrors(['name', 'email', 'password'])
        ->assertSet('currentStep', 1);
});

test('prevStep goes back to step 1', function () {
    Livewire::test(Register::class)
        ->set('currentStep', 2)
        ->call('prevStep')
        ->assertSet('currentStep', 1);
});

test('academic affiliation registers as mahasiswa and stores institution details', function () {
    Event::fake();

    Livewire::test(Register::class)
        ->set('name', 'Budi Santoso')
        ->set('email', 'budi@example.com')
        ->set('password', 'password123')
        ->set('affiliation', 'academic')
        ->set('university', 'Institut Teknologi Sepuluh Nopember')
        ->set('faculty', 'Teknik Biomedis')
        ->set('department', 'Teknologi Kedokteran')
        ->call('register')
        ->assertRedirect(route('verification.notice'));

    $user = User::where('email', 'budi@example.com')->first();
    expect($user)->not->toBeNull()
        ->and($user->role_id)->toBe(Role::where('name', 'mahasiswa')->value('id'));

    $profile = $user->profile;
    expect($profile)->not->toBeNull()
        ->and($profile->full_name)->toBe('Budi Santoso')
        ->and($profile->university)->toBe('Institut Teknologi Sepuluh Nopember')
        ->and($profile->faculty)->toBe('Teknik Biomedis')
        ->and($profile->department)->toBe('Teknologi Kedokteran');

    Event::assertDispatched(Registered::class);
});

test('non-academic affiliation registers as public user and stores institution details', function () {
    Event::fake();

    Livewire::test(Register::class)
        ->set('name', 'Siti Rahma')
        ->set('email', 'siti@example.com')
        ->set('password', 'password123')
        ->set('affiliation', 'non_academic')
        ->set('university', 'PT Teknologi Nusantara')
        ->set('department', 'Riset & Pengembangan')
        ->call('register')
        ->assertRedirect(route('verification.notice'));

    $user = User::where('email', 'siti@example.com')->first();
    expect($user->role_id)->toBe(Role::where('name', 'user_publik')->value('id'))
        ->and($user->profile->university)->toBe('PT Teknologi Nusantara')
        ->and($user->profile->department)->toBe('Riset & Pengembangan');

    Event::assertDispatched(Registered::class);
});

test('user without affiliation registers as public user', function () {
    Event::fake();

    Livewire::test(Register::class)
        ->set('name', 'Rian Pratama')
        ->set('email', 'rian@example.com')
        ->set('password', 'password123')
        ->call('register')
        ->assertRedirect(route('verification.notice'));

    $user = User::where('email', 'rian@example.com')->first();
    expect($user->role_id)->toBe(Role::where('name', 'user_publik')->value('id'))
        ->and($user->profile->university)->toBeNull();

    Event::assertDispatched(Registered::class);
});

test('switching away from academic clears the major', function () {
    Livewire::test(Register::class)
        ->set('affiliation', 'academic')
        ->set('faculty', 'Teknik Biomedis')
        ->set('affiliation', 'non_academic')
        ->assertSet('faculty', null);
});

test('institution name is required once an affiliation is selected', function () {
    Livewire::test(Register::class)
        ->set('name', 'Budi Santoso')
        ->set('email', 'budi@example.com')
        ->set('password', 'password123')
        ->set('affiliation', 'academic')
        ->set('currentStep', 2)
        ->call('register')
        ->assertHasErrors(['university']);
});

test('phone number is persisted to the profile', function () {
    Event::fake();

    Livewire::test(Register::class)
        ->set('name', 'Siti Rahma')
        ->set('email', 'siti@example.com')
        ->set('password', 'password123')
        ->set('phone', '081234567890')
        ->call('register')
        ->assertRedirect(route('verification.notice'));

    expect(User::where('email', 'siti@example.com')->first()->profile->phone)->toBe('081234567890');
});

test('registration fails with missing required fields', function () {
    Livewire::test(Register::class)
        ->call('register')
        ->assertHasErrors(['name', 'email', 'password']);
});

test('registration fails with duplicate email', function () {
    User::create([
        'name' => 'Existing User',
        'email' => 'existing@example.com',
        'password' => bcrypt('password'),
        'role_id' => Role::where('name', 'mahasiswa')->value('id'),
    ]);

    Livewire::test(Register::class)
        ->set('name', 'Another User')
        ->set('email', 'existing@example.com')
        ->set('password', 'password123')
        ->call('nextStep')
        ->assertHasErrors(['email']);
});
