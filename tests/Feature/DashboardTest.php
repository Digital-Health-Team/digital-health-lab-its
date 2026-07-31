<?php

use App\Models\User;

test('guests are redirected to the login page', function () {
    $this->get('/dashboard')->assertRedirect('/login');
});

test('authenticated users can visit the dashboard', function () {
    $this->actingAs($user = User::factory()->create());

    $this->get('/dashboard')->assertOk();
});

test('dashboard includes trainings prop for the training section', function () {
    $this->actingAs(User::factory()->create());

    $this->get('/dashboard')
        ->assertInertia(
            fn ($page) => $page
                ->component('Features/Dashboard/Pages/DashboardPage')
                ->has('trainings'),
        );
});

test('dashboard shares showWelcome=true in flash immediately after login', function () {
    $user = User::factory()->create();

    // Simulate what Login.php does: flash the show_welcome flag then redirect.
    session()->flash('show_welcome', true);

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertInertia(
            fn ($page) => $page
                ->component('Features/Dashboard/Pages/DashboardPage')
                ->where('flash.showWelcome', true),
        );
});

test('dashboard shares showWelcome=false on subsequent requests', function () {
    $user = User::factory()->create();

    // No flash set — simulates any navigation after the initial login redirect.
    $this->actingAs($user)
        ->get('/dashboard')
        ->assertInertia(
            fn ($page) => $page
                ->component('Features/Dashboard/Pages/DashboardPage')
                ->where('flash.showWelcome', false),
        );
});
