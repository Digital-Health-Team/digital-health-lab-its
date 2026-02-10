<?php

use App\Livewire\Auth\Login;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/login', Login::class)->name('login');


Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', \App\Livewire\Admin\Dashboard::class)->name('dashboard');

    // Manajemen Berita (News)
    Route::get('/news', \App\Livewire\Admin\News\Dashboard::class)->name('news');

    // Manajemen Pengguna (Users)
    Route::get('/users', \App\Livewire\Admin\User\Dashboard::class)->name('users');

    // Manajemen Kategori
    Route::get('/categories', \App\Livewire\Admin\Category\Dashboard::class)->name('categories');

    // Manajemen Tags
    Route::get('/tags', \App\Livewire\Admin\Tags\Dashboard::class)->name('tags');
});

// Route::middleware(['auth', 'role:user'])->prefix('user')->name('user.')->group(function () {
//     Route::get('/dashboard', \App\Livewire\User\Dashboard::class)->name('dashboard');
// });
