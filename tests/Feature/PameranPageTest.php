<?php

it('renders the pameran exhibition page for guests', function () {
    $this->get(route('pameran'))
        ->assertStatus(200)
        ->assertInertia(fn ($page) => $page
            ->component('Features/Pameran/Pages/PameranPage')
        );
});
