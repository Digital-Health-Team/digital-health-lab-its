<?php

use Illuminate\Support\Facades\Route;

// Auth Routes
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\ResetPassword;

// Email Verification Routes
use App\Livewire\Auth\VerifyEmail;
// Route khusus untuk handle klik link dari email (Laravel Handle Otomatis)
use Illuminate\Foundation\Auth\EmailVerificationRequest;

// Settings Route
use App\Livewire\Settings;

// Admin Routes
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\User\Index as AdminUserIndex;
use App\Livewire\Admin\Project\Index as AdminProjectIndex;
use App\Livewire\Admin\Jobdesk\Index as AdminJobdeskIndex;
use App\Livewire\Admin\Jobdesk\Revision\Index as AdminJobdeskRevision;
use App\Livewire\Admin\Attendance\Index as AdminAttendanceIndex;
use App\Livewire\Admin\Announcement\Index as AdminAnnouncementIndex;

// User Routes
use App\Livewire\User\Dashboard as UserDashboard;

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

// Route khusus untuk halaman "Please Verify"
Route::get('/email/verify', VerifyEmail::class)
    ->middleware('auth')
    ->name('verification.notice');


Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect()->route('user.dashboard'); // Redirect kemana setelah sukses
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::middleware(['auth'])->group(function () {
    // ... route dashboard lainnya

    // Route Settings (Bisa diakses semua user yang login)
    Route::get('/settings', Settings::class)->name('settings');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');

    // Halaman Request Link
    Route::get('/forgot-password', ForgotPassword::class)->name('password.request');

    // Halaman Input Password Baru (Link dari Email akan mengarah kesini)
    Route::get('/reset-password/{token}', ResetPassword::class)->name('password.reset');
});

Route::get('/register', Register::class)->name('register')->middleware('guest');

Route::middleware(['auth', 'role:super_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', AdminDashboard::class)->name('dashboard');
    Route::get('/users', AdminUserIndex::class)->name('users');
    Route::get('/projects', AdminProjectIndex::class)->name('projects');
    Route::get('/jobdesks', AdminJobdeskIndex::class)->name('jobdesks');
    Route::get('/jobdesks/{jobdesk}/revision', AdminJobdeskRevision::class)->name('jobdesks.revision');
    Route::get('/attendance', AdminAttendanceIndex::class)->name('attendance');
    Route::get('/announcements', AdminAnnouncementIndex::class)->name('announcements');
});

Route::middleware(['auth', 'verified', 'role:staff'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', UserDashboard::class)->name('dashboard');
});
