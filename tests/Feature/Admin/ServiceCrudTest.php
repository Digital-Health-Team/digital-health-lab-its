<?php

use App\Actions\Services\CreateServiceAction;
use App\Actions\Services\UpdateServiceAction;
use App\DTOs\Service\ServiceData;
use App\Livewire\Admin\Service\Index;
use App\Models\Role;
use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    $role = Role::create(['name' => 'super_admin', 'display_name' => 'Super Admin']);

    $this->admin = User::create([
        'name' => 'Admin',
        'email' => 'admin@test.com',
        'password' => Hash::make('password'),
        'role_id' => $role->id,
    ]);

    $this->actingAs($this->admin);
});

test('CreateServiceAction persists the given service_type', function () {
    $dto = new ServiceData('Jasa Scanning 3D', 'desc', 100000, null, 'scanning');

    $service = app(CreateServiceAction::class)->execute($dto);

    expect($service->service_type)->toBe('scanning');
    $this->assertDatabaseHas('services', ['id' => $service->id, 'service_type' => 'scanning']);
});

test('CreateServiceAction defaults service_type to printing', function () {
    $dto = new ServiceData('New Service', null, 50000);

    $service = app(CreateServiceAction::class)->execute($dto);

    expect($service->service_type)->toBe('printing');
});

test('UpdateServiceAction changes service_type', function () {
    $service = Service::create([
        'name' => 'Old Service',
        'service_type' => 'printing',
        'base_price' => 50000,
    ]);

    $dto = new ServiceData('Old Service', null, 50000, null, 'design');
    app(UpdateServiceAction::class)->execute($service, $dto);

    expect($service->fresh()->service_type)->toBe('design');
});

test('Livewire save rejects invalid service_type', function () {
    Livewire::test(Index::class)
        ->set('name', 'Test Service')
        ->set('service_type', 'mencetak')
        ->set('base_price', 10000)
        ->call('save')
        ->assertHasErrors(['service_type' => 'in']);
});

test('Livewire save persists correct service_type', function () {
    Livewire::test(Index::class)
        ->set('name', 'Jasa Scan')
        ->set('service_type', 'scanning')
        ->set('base_price', 100000)
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('services', ['name' => 'Jasa Scan', 'service_type' => 'scanning']);
});

test('Livewire edit loads service_type into form', function () {
    $service = Service::create([
        'name' => 'Existing',
        'service_type' => 'design',
        'base_price' => 150000,
    ]);

    Livewire::test(Index::class)
        ->call('edit', $service)
        ->assertSet('service_type', 'design');
});
