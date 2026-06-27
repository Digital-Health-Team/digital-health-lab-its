<?php

it('renders the training detail page for guests', function () {
    $this->get(route('training.show', ['training' => 'intro-3d-printing-prosthetics']))
        ->assertStatus(200)
        ->assertInertia(fn ($page) => $page
            ->component('Features/Training/Pages/TrainingDetailPage')
            ->where('trainingSlug', 'intro-3d-printing-prosthetics')
        );
});

it('renders the training detail page for any slug', function () {
    $this->get(route('training.show', ['training' => 'fdm-vs-resin']))
        ->assertStatus(200)
        ->assertInertia(fn ($page) => $page
            ->component('Features/Training/Pages/TrainingDetailPage')
            ->where('trainingSlug', 'fdm-vs-resin')
        );
});
