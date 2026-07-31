<?php

test('dev documentation page is publicly accessible', function () {
    $this->get('/dev/documentations')
        ->assertOk()
        ->assertSee('Developer Documentation');
});

test('dev documentation page is accessible when authenticated', function () {
    $user = \App\Models\User::factory()->create();

    $this->actingAs($user)
        ->get('/dev/documentations')
        ->assertOk();
});
