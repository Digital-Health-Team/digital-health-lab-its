<?php

use App\Actions\RawMaterial\CreateRawMaterialAction;
use App\Actions\RawMaterial\UpdateRawMaterialAction;
use App\DTOs\RawMaterial\RawMaterialData;
use App\Models\Brand;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    $role = Role::create(['name' => 'admin_lab', 'display_name' => 'Admin Lab']);

    $this->admin = User::create([
        'name' => 'Test Admin',
        'email' => 'admin@test.com',
        'password' => Hash::make('password'),
        'role_id' => $role->id,
    ]);

    $this->actingAs($this->admin);
    $this->brand = Brand::create(['name' => 'eSUN']);
});

it('creates a raw material with brand, name, and unit', function () {
    $dto = new RawMaterialData(
        brand_id: $this->brand->id,
        name: 'PLA+ 1.75mm 1kg',
        unit: 'gram',
    );

    $material = app(CreateRawMaterialAction::class)->execute($dto);

    expect($material->brand_id)->toBe($this->brand->id)
        ->and($material->name)->toBe('PLA+ 1.75mm 1kg')
        ->and($material->unit)->toBe('gram');
});

it('persists raw material to the database', function () {
    $dto = new RawMaterialData(
        brand_id: $this->brand->id,
        name: 'PETG Black 1kg',
        unit: 'gram',
    );

    app(CreateRawMaterialAction::class)->execute($dto);

    $this->assertDatabaseHas('raw_materials', [
        'brand_id' => $this->brand->id,
        'name' => 'PETG Black 1kg',
        'unit' => 'gram',
    ]);
});

it('updates a raw material name and unit', function () {
    $dto = new RawMaterialData(
        brand_id: $this->brand->id,
        name: 'PLA+ Original',
        unit: 'gram',
    );

    $material = app(CreateRawMaterialAction::class)->execute($dto);

    $updateDto = new RawMaterialData(
        brand_id: $this->brand->id,
        name: 'PLA+ Revised 2kg',
        unit: 'kg',
    );

    app(UpdateRawMaterialAction::class)->execute($material, $updateDto);

    expect($material->refresh()->name)->toBe('PLA+ Revised 2kg')
        ->and($material->refresh()->unit)->toBe('kg');
});

it('allows two materials with the same brand but different names', function () {
    $dto1 = new RawMaterialData(brand_id: $this->brand->id, name: 'PLA+ White 1kg', unit: 'gram');
    $dto2 = new RawMaterialData(brand_id: $this->brand->id, name: 'PLA+ Black 1kg', unit: 'gram');

    app(CreateRawMaterialAction::class)->execute($dto1);
    app(CreateRawMaterialAction::class)->execute($dto2);

    expect(\App\Models\RawMaterial::where('brand_id', $this->brand->id)->count())->toBe(2);
});

it('updates a raw material brand to a different brand', function () {
    $otherBrand = Brand::create(['name' => 'Anycubic']);

    $dto = new RawMaterialData(brand_id: $this->brand->id, name: 'Resin 1L', unit: 'ml');
    $material = app(CreateRawMaterialAction::class)->execute($dto);

    $updateDto = new RawMaterialData(brand_id: $otherBrand->id, name: 'Resin 1L', unit: 'ml');
    app(UpdateRawMaterialAction::class)->execute($material, $updateDto);

    expect($material->refresh()->brand_id)->toBe($otherBrand->id);
});
