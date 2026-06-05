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

it('creates master records via firstOrCreate when they do not exist', function () {
    $dto = new RawMaterialData(
        lab: 'Lab Tekkes',
        category: 'Filament',
        brand: 'eSUN',
        color: 'White',
        unit: 'gram',
        current_stock: 1000
    );

    $material = app(CreateRawMaterialAction::class)->execute($dto);

    expect(Lab::count())->toBe(1)
        ->and(MaterialCategory::count())->toBe(1)
        ->and(Brand::count())->toBe(1)
        ->and(Color::count())->toBe(1)
        ->and($material->lab->name)->toBe('Lab Tekkes')
        ->and($material->materialCategory->name)->toBe('Filament')
        ->and($material->brand->name)->toBe('eSUN')
        ->and($material->color->name)->toBe('White')
        ->and($material->current_stock)->toBe(1000);
});

it('reuses existing master records via firstOrCreate', function () {
    // Pre-create master records
    Lab::create(['name' => 'Lab Tekkes']);
    MaterialCategory::create(['name' => 'Filament']);
    Brand::create(['name' => 'eSUN']);
    Color::create(['name' => 'White']);

    $dto = new RawMaterialData(
        lab: 'Lab Tekkes',
        category: 'Filament',
        brand: 'eSUN',
        color: 'White',
        unit: 'gram',
        current_stock: 500
    );

    app(CreateRawMaterialAction::class)->execute($dto);

    // No duplicates should have been created
    expect(Lab::count())->toBe(1)
        ->and(MaterialCategory::count())->toBe(1)
        ->and(Brand::count())->toBe(1)
        ->and(Color::count())->toBe(1);
});

it('enforces composite unique constraint on raw_materials', function () {
    $dto = new RawMaterialData(
        lab: 'Lab Tekkes',
        category: 'Filament',
        brand: 'eSUN',
        color: 'White',
        unit: 'gram',
        current_stock: 1000
    );

    app(CreateRawMaterialAction::class)->execute($dto);

    // Attempt to create exact same combination — should throw
    expect(fn () => app(CreateRawMaterialAction::class)->execute($dto))
        ->toThrow(\Illuminate\Database\QueryException::class);
});

it('resolves new master records on update via firstOrCreate', function () {
    $dto = new RawMaterialData(
        lab: 'Lab Tekkes',
        category: 'Filament',
        brand: 'eSUN',
        color: 'White',
        unit: 'gram',
        current_stock: 1000
    );

    $material = app(CreateRawMaterialAction::class)->execute($dto);

    // Update with a brand-new color that doesn't exist yet
    $updateDto = new RawMaterialData(
        lab: 'Lab Tekkes',
        category: 'Filament',
        brand: 'eSUN',
        color: 'Matte Black',
        unit: 'gram',
        current_stock: 0
    );

    app(UpdateRawMaterialAction::class)->execute($material, $updateDto);

    $material->refresh();

    expect(Color::count())->toBe(2)
        ->and($material->color->name)->toBe('Matte Black');
});
