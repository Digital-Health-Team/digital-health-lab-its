<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('guests default to indonesian', function () {
    $this->get('/')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page->where('locale', 'id'));
});

test('switching locale persists in the session', function () {
    $this->from('/')->post('/locale', ['locale' => 'en'])->assertRedirect('/');

    expect(session('locale'))->toBe('en');

    $this->get('/')->assertInertia(fn (Assert $page) => $page->where('locale', 'en'));
});

test('switching locale persists on the authenticated user', function () {
    $user = User::factory()->create(['locale' => 'id']);

    $this->actingAs($user)->from('/')->post('/locale', ['locale' => 'en']);

    expect($user->fresh()->locale)->toBe('en');
});

test('an unsupported locale is rejected', function () {
    $this->from('/')->post('/locale', ['locale' => 'fr'])
        ->assertSessionHasErrors('locale');

    expect(session('locale'))->toBeNull();
});

test('the translation map is shared for indonesian but empty for the fallback locale', function () {
    // Keys ARE the English source strings, so `en` needs no payload — t() returns the key.
    $this->get('/')->assertInertia(fn (Assert $page) => $page
        ->where('locale', 'id')
        ->where('translations.Home', 'Beranda')
    );

    $this->post('/locale', ['locale' => 'en']);

    $this->get('/')->assertInertia(fn (Assert $page) => $page
        ->where('locale', 'en')
        ->where('translations', [])
    );
});

test('a user preferred locale drives notifications and requests', function () {
    $user = User::factory()->create(['locale' => 'en']);

    expect($user->preferredLocale())->toBe('en');

    $this->actingAs($user)->get('/')
        ->assertInertia(fn (Assert $page) => $page->where('locale', 'en'));
});

test('validation errors are translated to indonesian', function () {
    app()->setLocale('id');

    expect(__('validation.required', ['attribute' => 'email']))
        ->not->toBe('validation.required')
        ->not->toContain('field is required');
});
