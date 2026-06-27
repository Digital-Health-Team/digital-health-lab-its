<?php

use App\Models\Training;
use App\Models\User;

it('renders the training detail page for guests', function () {
    $training = Training::factory()->create(['slug' => 'intro-3d-printing-prosthetics']);

    $this->get(route('training.show', $training))
        ->assertStatus(200)
        ->assertInertia(fn ($page) => $page
            ->component('Features/Training/Pages/TrainingDetailPage')
            ->has('training')
            ->where('isAuthenticated', false)
            ->where('isRegistered', false)
            ->where('userRegistration', null)
        );
});

it('renders the training detail page with registration status for authenticated users', function () {
    $training = Training::factory()->create(['slug' => 'fdm-vs-resin']);
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('training.show', $training))
        ->assertStatus(200)
        ->assertInertia(fn ($page) => $page
            ->component('Features/Training/Pages/TrainingDetailPage')
            ->where('isAuthenticated', true)
            ->where('isRegistered', false)
        );
});

it('returns 404 for unknown training slug', function () {
    $this->get(route('training.show', ['training' => 'non-existent-slug']))
        ->assertStatus(404);
});
