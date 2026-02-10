<?php

use Illuminate\Support\Facades\Route;

// Auth Routes
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\ResetPassword;

// Admin Routes
use App\Livewire\Admin\Index as AdminDashboard;
use App\Livewire\Admin\News\Index as AdminNewsDashboard;
use App\Livewire\Admin\News\Approval\Index as AdminApprovalDashboard;
use App\Livewire\Admin\User\Index as AdminUserDashboard;
use App\Livewire\Admin\Category\Index as AdminCategoryDashboard;
use App\Livewire\Admin\Tags\Index as AdminTagsDashboard;

// User Routes
use App\Livewire\User\Index as UserDashboard;
use App\Livewire\User\News\Index as UserNewsDashboard;
use App\Livewire\User\Profile\Index as UserProfileIndex;


Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');

    // Halaman Request Link
    Route::get('/forgot-password', ForgotPassword::class)->name('password.request');

    // Halaman Input Password Baru (Link dari Email akan mengarah kesini)
    Route::get('/reset-password/{token}', ResetPassword::class)->name('password.reset');
});

Route::get('/register', Register::class)->name('register')->middleware('guest');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', AdminDashboard::class)->name('dashboard');
    Route::get('/news', AdminNewsDashboard::class)->name('news');
    Route::get('/approval', AdminApprovalDashboard::class)->name('approval');
    Route::get('/users', AdminUserDashboard::class)->name('users');
    Route::get('/categories', AdminCategoryDashboard::class)->name('categories');
    Route::get('/tags', AdminTagsDashboard::class)->name('tags');
});

Route::middleware(['auth', 'role:user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', UserDashboard::class)->name('dashboard');
    Route::get('/news', UserNewsDashboard::class)->name('news.index');
    Route::get('/profile', UserProfileIndex::class)->name('profile');
});
