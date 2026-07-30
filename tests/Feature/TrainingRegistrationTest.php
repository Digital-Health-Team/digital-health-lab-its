<?php

use App\Models\Training;
use App\Models\TrainingRegistration;
use App\Models\User;

it('guests cannot register for a training', function () {
    $training = Training::factory()->create();

    $this->post(route('training.register', $training), [
        'full_name' => 'Budi Santoso',
        'email' => 'budi@example.com',
        'phone_number' => '081234567890',
    ])->assertRedirect(route('login'));
});

it('authenticated users can register for a training', function () {
    $training = Training::factory()->create();
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('training.register', $training), [
            'full_name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'phone_number' => '081234567890',
            'preferred_session' => 'Weekend morning',
            'additional_notes' => 'No notes',
        ])
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseHas('training_registrations', [
        'training_id' => $training->id,
        'user_id' => $user->id,
        'full_name' => 'Budi Santoso',
        'status' => 'pending',
    ]);
});

it('users cannot register for the same training twice', function () {
    $training = Training::factory()->create();
    $user = User::factory()->create();

    TrainingRegistration::create([
        'training_id' => $training->id,
        'user_id' => $user->id,
        'full_name' => 'Budi Santoso',
        'email' => 'budi@example.com',
        'phone_number' => '081234567890',
        'status' => 'pending',
    ]);

    $this->actingAs($user)
        ->post(route('training.register', $training), [
            'full_name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'phone_number' => '081234567890',
        ])
        ->assertRedirect()
        ->assertSessionHasErrors('registration');
});

it('users cannot register for a full training', function () {
    $training = Training::factory()->create(['max_participants' => 1]);
    $otherUser = User::factory()->create();
    $user = User::factory()->create();

    TrainingRegistration::create([
        'training_id' => $training->id,
        'user_id' => $otherUser->id,
        'full_name' => 'Other User',
        'email' => 'other@example.com',
        'phone_number' => '089999999999',
        'status' => 'pending',
    ]);

    $this->actingAs($user)
        ->post(route('training.register', $training), [
            'full_name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'phone_number' => '081234567890',
        ])
        ->assertRedirect()
        ->assertSessionHasErrors('registration');
});

it('registration requires required fields', function () {
    $training = Training::factory()->create();
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('training.register', $training), [])
        ->assertSessionHasErrors(['full_name', 'email', 'phone_number']);
});

// Active-only listing is covered on the merged page — see EventsPageTest.
