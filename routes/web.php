<?php

use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\ProfileController;
use App\Livewire\Admin\CMS\PageSection\Index as AdminCmsPageSectionIndex;
use App\Livewire\Admin\CMS\StructuralMember\Index as AdminCmsStructuralMemberIndex;
use App\Livewire\Admin\Dashboard as AdminLabDashboard;
use App\Livewire\Admin\Event\Index as AdminEventIndex;
use App\Livewire\Admin\Event\Show\Index as AdminEventShow;
use App\Livewire\Admin\Event\Team\Index as AdminTeamShow;
use App\Livewire\Admin\GlobalSearch\Index as AdminGlobalSearch;
use App\Livewire\Admin\MasterData\Index as AdminMasterDataIndex;
use App\Livewire\Admin\OpenSourceProject\Index as AdminOpenSourceProjectIndex;
use App\Livewire\Admin\OrderCenter\Index as AdminOrderCenterIndex;
use App\Livewire\Admin\Product\Index as AdminProductIndex;
use App\Livewire\Admin\RawMaterial\Index as AdminRawMaterialIndex;
use App\Livewire\Admin\Service\Index as AdminServiceIndex;
use App\Livewire\Admin\User\Index as AdminUserIndex;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\Auth\VerifyEmail;
use App\Livewire\Gudang\Dashboard\Index as GudangDashboard;
use App\Livewire\Settings;
use App\Livewire\SuperAdmin\Dashboard\Index as SuperAdminDashboard;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingPageController::class, 'index'])->name('home');

Route::get('/email/verify', VerifyEmail::class)
    ->middleware('auth')
    ->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect()->route('user.dashboard');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::middleware(['auth'])->group(function () {
    Route::get('/settings', Settings::class)->name('settings');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
    Route::get('/forgot-password', ForgotPassword::class)->name('password.request');
    Route::get('/reset-password/{token}', ResetPassword::class)->name('password.reset');
});

// Super Admin exclusive dashboard
Route::middleware(['auth', 'role:super_admin'])->prefix('super-admin')->name('super-admin.')->group(function () {
    Route::get('/dashboard', SuperAdminDashboard::class)->name('dashboard');
});

// Gudang exclusive dashboard
Route::middleware(['auth', 'role:admin_gudang'])->prefix('gudang')->name('gudang.')->group(function () {
    Route::get('/dashboard', GudangDashboard::class)->name('dashboard');
});

// Admin operations area (accessible by super_admin + admin_lab + admin_gudang at group level;
// individual routes apply tighter role restrictions for their specific audience)
Route::middleware(['auth', 'role:super_admin|admin_lab|admin_gudang'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/search', AdminGlobalSearch::class)->name('search');

    // Admin Lab dashboard (super_admin can also see this for oversight)
    Route::get('/dashboard', AdminLabDashboard::class)->middleware('role:super_admin|admin_lab')->name('dashboard');

    // Operations — super_admin + admin_lab
    Route::get('/order-center', AdminOrderCenterIndex::class)->middleware('role:super_admin|admin_lab')->name('order-center');
    Route::get('/services', AdminServiceIndex::class)->middleware('role:super_admin|admin_lab')->name('services');
    Route::get('/products', AdminProductIndex::class)->middleware('role:super_admin|admin_lab')->name('products');
    Route::get('/events', AdminEventIndex::class)->middleware('role:super_admin|admin_lab')->name('events');
    Route::get('/events/{event}', AdminEventShow::class)->middleware('role:super_admin|admin_lab')->name('events.show');
    Route::get('/events/teams/{team}', AdminTeamShow::class)->middleware('role:super_admin|admin_lab')->name('teams.show');
    Route::get('/open-source-projects', AdminOpenSourceProjectIndex::class)->middleware('role:super_admin|admin_lab')->name('open-source-projects');

    // Warehouse — super_admin + admin_gudang
    Route::get('/raw-materials', AdminRawMaterialIndex::class)->middleware('role:super_admin|admin_gudang')->name('raw-materials');
    Route::get('/master-data', AdminMasterDataIndex::class)->middleware('role:super_admin|admin_gudang')->name('master-data');

    // System — super_admin only
    Route::get('/users', AdminUserIndex::class)->middleware('role:super_admin')->name('users');
    Route::get('/cms/page-sections', AdminCmsPageSectionIndex::class)->middleware('role:super_admin')->name('cms.page-sections');
    Route::get('/cms/structural-members', AdminCmsStructuralMemberIndex::class)->middleware('role:super_admin')->name('cms.structural-members');
});

Route::get('/dashboard', fn () => inertia('Features/Dashboard/Pages/DashboardPage'))
    ->name('user.dashboard');
