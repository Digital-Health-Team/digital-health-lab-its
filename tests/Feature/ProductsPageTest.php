<?php

it('renders the products page for guests', function () {
    $this->get(route('products'))
        ->assertStatus(200)
        ->assertInertia(fn ($page) => $page
            ->component('Features/Products/Pages/ProductsPage')
        );
});
