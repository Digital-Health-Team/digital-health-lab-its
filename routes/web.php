<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\PameranController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\User\PortfolioController;
use App\Http\Controllers\User\UserProjectController;
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
use App\Livewire\Admin\OrderCenter\Show as AdminOrderCenterShow;
use App\Livewire\Admin\Product\Index as AdminProductIndex;
use App\Livewire\Admin\RawMaterial\Index as AdminRawMaterialIndex;
use App\Livewire\Admin\Service\Index as AdminServiceIndex;
use App\Livewire\Admin\Training\Index as AdminTrainingIndex;
use App\Livewire\Admin\Training\Show as AdminTrainingShow;
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
Route::get('/exhibition/{exhibition_name}', [PameranController::class, 'index'])->name('exhibition');

Route::get('/email/verify', VerifyEmail::class)
    ->middleware('auth')
    ->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect()->route('user.dashboard');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/settings', Settings::class)->name('settings');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // User-facing 3D-printing order flow
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{booking}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{booking}/payments/{payment}/proof', [OrderController::class, 'uploadPaymentProof'])->name('orders.payments.proof');
    Route::post('/orders/{booking}/messages', [OrderController::class, 'sendMessage'])->name('orders.messages.store');

    // Centralized user portfolio
    Route::get('/portfolio', [PortfolioController::class, 'index'])->name('portfolio.index');

    // User-managed open-source projects
    Route::prefix('my/projects')->name('my.projects.')->group(function () {
        Route::post('/', [UserProjectController::class, 'store'])->name('store');
        Route::post('/{project}', [UserProjectController::class, 'update'])->name('update');
        Route::delete('/{project}', [UserProjectController::class, 'destroy'])->name('destroy');
    });
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
    Route::get('/order-center/{booking}', AdminOrderCenterShow::class)->middleware('role:super_admin|admin_lab')->name('order-center.show');
    Route::get('/services', AdminServiceIndex::class)->middleware('role:super_admin|admin_lab')->name('services');
    Route::get('/products', AdminProductIndex::class)->middleware('role:super_admin|admin_lab')->name('products');
    Route::get('/events', AdminEventIndex::class)->middleware('role:super_admin|admin_lab')->name('events');
    Route::get('/events/{event}', AdminEventShow::class)->middleware('role:super_admin|admin_lab')->name('events.show');
    Route::get('/events/teams/{team}', AdminTeamShow::class)->middleware('role:super_admin|admin_lab')->name('teams.show');
    Route::get('/open-source-projects', AdminOpenSourceProjectIndex::class)->middleware('role:super_admin|admin_lab')->name('open-source-projects');
    Route::get('/trainings', AdminTrainingIndex::class)->middleware('role:super_admin|admin_lab')->name('trainings');
    Route::get('/trainings/{training}', AdminTrainingShow::class)->middleware('role:super_admin|admin_lab')->name('trainings.show');

    // Warehouse — super_admin + admin_gudang
    Route::get('/raw-materials', AdminRawMaterialIndex::class)->middleware('role:super_admin|admin_gudang')->name('raw-materials');
    Route::get('/master-data', AdminMasterDataIndex::class)->middleware('role:super_admin|admin_gudang')->name('master-data');

    // System — super_admin only
    Route::get('/users', AdminUserIndex::class)->middleware('role:super_admin')->name('users');
    Route::get('/cms/page-sections', AdminCmsPageSectionIndex::class)->middleware('role:super_admin')->name('cms.page-sections');
    Route::get('/cms/structural-members', AdminCmsStructuralMemberIndex::class)->middleware('role:super_admin')->name('cms.structural-members');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('user.dashboard');

Route::get('/training', [TrainingController::class, 'index'])
    ->name('training');

Route::get('/training/{training}', [TrainingController::class, 'show'])
    ->name('training.show');

Route::post('/training/{training}/register', [TrainingController::class, 'register'])
    ->middleware('auth')
    ->name('training.register');

Route::post('/training/{training}/upload-proof', [TrainingController::class, 'uploadPaymentProof'])
    ->middleware('auth')
    ->name('training.upload-proof');

Route::get('/projects', [ProjectsController::class, 'index'])
    ->name('projects');

Route::get('/projects/{project}', [ProjectsController::class, 'show'])
    ->name('projects.show');

Route::get('/services', [ServicesController::class, 'index'])
    ->name('services');

Route::get('/services/{service}', [ServicesController::class, 'show'])
    ->name('services.show');

Route::get('/products', [ProductsController::class, 'index'])
    ->name('products');

Route::get('/products/{product}', [ProductsController::class, 'show'])
    ->name('products.show');
