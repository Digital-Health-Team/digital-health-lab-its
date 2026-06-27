<?php

it('renders the projects page for guests', function () {
    $this->get(route('projects'))
        ->assertStatus(200)
        ->assertInertia(fn ($page) => $page
            ->component('Features/Projects/Pages/ProjectsPage')
        );
});
