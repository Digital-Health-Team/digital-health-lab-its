<?php

it('renders the training page for guests', function () {
    $this->get(route('training'))
        ->assertStatus(200)
        ->assertInertia(fn ($page) => $page
            ->component('Features/Training/Pages/TrainingPage')
        );
});
