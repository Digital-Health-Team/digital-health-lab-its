<?php

use App\Models\User;

/**
 * These render the real Blade, so a syntax error or a bad __() call fails here
 * rather than in production.
 */
test('guest auth screens render in indonesian', function (string $uri, string $expected) {
    session(['locale' => 'id']);

    $this->get($uri)->assertOk()->assertSee($expected, escape: false);
})->with([
    'login' => ['/login', 'Selamat datang kembali'],
    'register' => ['/register', 'Buat akun baru'],
    'forgot password' => ['/forgot-password', 'Lupa Password?'],
]);

test('guest auth screens render in english', function (string $uri, string $expected) {
    session(['locale' => 'en']);

    $this->get($uri)->assertOk()->assertSee($expected, escape: false);
})->with([
    'login' => ['/login', 'Welcome back'],
    'register' => ['/register', 'Create a new account'],
    'forgot password' => ['/forgot-password', 'Forgot Password?'],
]);

test('the guest layout exposes a language switcher', function () {
    $this->get('/login')->assertOk()->assertSeeLivewire('language-switcher');
});

test('login validation errors are localized', function () {
    session(['locale' => 'id']);

    Livewire::test(App\Livewire\Auth\Login::class)
        ->set('email', '')
        ->call('login')
        ->assertHasErrors(['email' => 'required']);

    // The framework default is English; lang/id/validation.php must override it.
    expect(__('validation.required', ['attribute' => 'email']))
        ->toContain('wajib');
});

test('the verification email is sent in the recipient locale', function () {
    Notification::fake();

    $user = User::factory()->unverified()->create(['locale' => 'en']);

    $user->sendEmailVerificationNotification();

    Notification::assertSentTo($user, App\Notifications\VerifyEmailNotification::class,
        function ($notification, $channels, $notifiable) {
            // HasLocalePreference on User drives this.
            return $notifiable->preferredLocale() === 'en';
        });
});
