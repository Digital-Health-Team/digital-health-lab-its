<?php

use App\Actions\RawMaterial\CreateRawMaterialAction;
use App\Actions\RawMaterial\UpdateRawMaterialAction;
use App\DTOs\RawMaterial\RawMaterialData;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Lab;
use App\Models\MaterialCategory;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeMasterRecords(): array
{
    return [
        'lab' => Lab::create(['name' => 'Lab Tekkes']),
        'category' => MaterialCategory::create(['name' => 'Filament']),
        'brand' => Brand::create(['name' => 'eSUN']),
        'color' => Color::create(['name' => 'White']),
    ];
}

beforeEach(function () {
    $role = Role::create(['name' => 'admin_lab', 'display_name' => 'Admin Lab']);

    $this->admin = User::create([
        'name' => 'Test Admin',
        'email' => 'admin@test.com',
        'password' => Hash::make('password'),
        'role_id' => $role->id,
    ]);

    $this->actingAs($this->admin);
});

it('creates a raw material using FK ids directly', function () {
    $records = makeMasterRecords();

    $dto = new RawMaterialData(
        lab_id: $records['lab']->id,
        category_id: $records['category']->id,
        brand_id: $records['brand']->id,
        color_id: $records['color']->id,
        unit: 'gram',
        current_stock: 1000,
    );

    $material = app(CreateRawMaterialAction::class)->execute($dto);

    expect($material->lab_id)->toBe($records['lab']->id)
        ->and($material->material_category_id)->toBe($records['category']->id)
        ->and($material->brand_id)->toBe($records['brand']->id)
        ->and($material->color_id)->toBe($records['color']->id)
        ->and($material->current_stock)->toBe(1000);
});

it('enforces the composite unique constraint on raw_materials', function () {
    $records = makeMasterRecords();

    $dto = new RawMaterialData(
        lab_id: $records['lab']->id,
        category_id: $records['category']->id,
        brand_id: $records['brand']->id,
        color_id: $records['color']->id,
        unit: 'gram',
        current_stock: 1000,
    );

    app(CreateRawMaterialAction::class)->execute($dto);

    expect(fn () => app(CreateRawMaterialAction::class)->execute($dto))
        ->toThrow(\Illuminate\Database\QueryException::class);
});

it('updates a raw material to a different color via FK id', function () {
    $records = makeMasterRecords();
    $newColor = Color::create(['name' => 'Matte Black']);

    $dto = new RawMaterialData(
        lab_id: $records['lab']->id,
        category_id: $records['category']->id,
        brand_id: $records['brand']->id,
        color_id: $records['color']->id,
        unit: 'gram',
        current_stock: 1000,
    );

    $material = app(CreateRawMaterialAction::class)->execute($dto);

    $updateDto = new RawMaterialData(
        lab_id: $records['lab']->id,
        category_id: $records['category']->id,
        brand_id: $records['brand']->id,
        color_id: $newColor->id,
        unit: 'gram',
        current_stock: 0,
    );

    app(UpdateRawMaterialAction::class)->execute($material, $updateDto);

    expect($material->refresh()->color_id)->toBe($newColor->id);
});

it('rejects a non-existent lab_id via exists validation', function () {
    expect(fn () => new RawMaterialData(
        lab_id: 99999,
        category_id: 99999,
        brand_id: 99999,
        color_id: 99999,
        unit: 'gram',
        current_stock: 0,
    ))->not->toThrow(\Exception::class); // DTO construction is fine

    // Validation happens in the Livewire layer; here we verify the DB constraint fires
    $records = makeMasterRecords();
    $dto = new RawMaterialData(
        lab_id: 99999,
        category_id: $records['category']->id,
        brand_id: $records['brand']->id,
        color_id: $records['color']->id,
        unit: 'gram',
        current_stock: 0,
    );

    expect(fn () => app(CreateRawMaterialAction::class)->execute($dto))
        ->toThrow(\Illuminate\Database\QueryException::class);
});
