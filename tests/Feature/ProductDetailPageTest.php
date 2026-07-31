<?php

it('renders the product detail page for guests', function () {
    $this->get(route('products.show', ['product' => 'am1']))
        ->assertStatus(200)
        ->assertInertia(fn ($page) => $page
            ->component('Features/Products/Pages/ProductDetailPage')
            ->where('productId', 'am1')
        );
});
