<?php

use App\Models\User;

test('dev documentation page is publicly accessible', function () {
    $this->get('/dev/documentations')
        ->assertOk()
        ->assertSee('Developer Documentation');
});

test('dev documentation page is accessible when authenticated', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/dev/documentations')
        ->assertOk();
});
