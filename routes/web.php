<?php

use Illuminate\Support\Facades\Route;

// Auth Routes
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\ResetPassword;

// Email Verification Routes
use App\Livewire\Auth\VerifyEmail;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

// Settings Route
use App\Livewire\Settings;

// Admin Routes
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\GlobalSearch as GlobalSearch;
use App\Livewire\Admin\User\Index as AdminUserIndex;
use App\Livewire\Admin\RawMaterial\Index as AdminRawMaterialIndex;
use App\Livewire\Admin\Service\Index as AdminServiceIndex;
use App\Livewire\Admin\Product\Index as AdminProductIndex;
use App\Livewire\Admin\Event\Index as AdminEventIndex;
use App\Livewire\Admin\Event\Show\Index as AdminEventShow;
use App\Livewire\Admin\Event\Team\Index as AdminTeamShow;
use App\Livewire\Admin\OpenSourceProject\Index as AdminOpenSourceProjectIndex;

// Order Center Route (Phase 4)
use App\Livewire\Admin\OrderCenter\Index as AdminOrderCenterIndex;

// Tambahan CMS Routes (Pastikan menggunakan 'Cms' bukan 'CMS')
use App\Livewire\Admin\CMS\PageSection\Index as AdminCmsPageSectionIndex;
use App\Livewire\Admin\CMS\StructuralMember\Index as AdminCmsStructuralMemberIndex;

// User Routes
use App\Livewire\User\Dashboard as UserDashboard;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Home');
})->name('home');

// Route khusus untuk halaman "Please Verify"
Route::get('/email/verify', VerifyEmail::class)
    ->middleware('auth')
    ->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect()->route('user.dashboard');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::middleware(['auth'])->group(function () {
    Route::get('/settings', Settings::class)->name('settings');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
    Route::get('/forgot-password', ForgotPassword::class)->name('password.request');
    Route::get('/reset-password/{token}', ResetPassword::class)->name('password.reset');
});

Route::middleware(['auth', 'role:super_admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/global-search', GlobalSearch::class)->name('global-search');

    Route::get('/dashboard', AdminDashboard::class)->name('dashboard');
    Route::get('/users', AdminUserIndex::class)->name('users');
    Route::get('/raw-materials', AdminRawMaterialIndex::class)->name('raw-materials');
    Route::get('/services', AdminServiceIndex::class)->name('services');
    Route::get('/products', AdminProductIndex::class)->name('products');
    Route::get('/events', AdminEventIndex::class)->name('events');
    Route::get('/events/{event}', AdminEventShow::class)->name('events.show');
    Route::get('/events/teams/{team}', AdminTeamShow::class)->name('teams.show');
    Route::get('/open-source-projects', AdminOpenSourceProjectIndex::class)->name('open-source-projects');

    // Route Order Center
    Route::get('/order-center', AdminOrderCenterIndex::class)->name('order-center');

    // Route CMS Phase 3
    Route::get('/cms/page-sections', AdminCmsPageSectionIndex::class)->name('cms.page-sections');
    Route::get('/cms/structural-members', AdminCmsStructuralMemberIndex::class)->name('cms.structural-members');
});

Route::middleware(['auth', 'verified', 'role:user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', UserDashboard::class)->name('dashboard');
});
