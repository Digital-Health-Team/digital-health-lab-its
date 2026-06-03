<?php

use App\Models\Brand;
use App\Models\Color;
use App\Models\Lab;
use App\Models\MaterialCategory;
use App\Models\RawMaterial;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    $role = Role::create(['name' => 'super_admin', 'display_name' => 'Super Admin']);

    $this->admin = User::create([
        'name' => 'Test Admin',
        'email' => 'admin@test.com',
        'password' => Hash::make('password'),
        'role_id' => $role->id,
    ]);

    $this->actingAs($this->admin);
});

it('renders the master data page with tabs', function () {
    Lab::create(['name' => 'Lab Tekkes']);
    Brand::create(['name' => 'eSUN']);

    Livewire::test(\App\Livewire\Admin\MasterData\Index::class)
        ->assertSee('Lab Tekkes')
        ->assertSee('Labs')
        ->assertSee('Categories')
        ->assertSee('Brands')
        ->assertSee('Colors');
});

it('switches tabs and queries the correct model', function () {
    Lab::create(['name' => 'Lab Tekkes']);
    Brand::create(['name' => 'TestBrandXYZ']);

    // Default tab = labs, should show the lab
    Livewire::test(\App\Livewire\Admin\MasterData\Index::class)
        ->assertSee('Lab Tekkes')
        // Switch to brands tab — should show the brand record
        ->set('activeTab', 'brands')
        ->assertSee('TestBrandXYZ');
});

it('creates a new record via the form', function () {
    Livewire::test(\App\Livewire\Admin\MasterData\Index::class)
        ->set('activeTab', 'categories')
        ->call('create')
        ->set('name', 'Filament')
        ->call('save');

    expect(MaterialCategory::where('name', 'Filament')->exists())->toBeTrue();
});

it('edits an existing record', function () {
    $lab = Lab::create(['name' => 'Old Name']);

    Livewire::test(\App\Livewire\Admin\MasterData\Index::class)
        ->call('edit', $lab->id)
        ->set('name', 'New Name')
        ->call('save');

    expect($lab->fresh()->name)->toBe('New Name');
});

it('blocks deletion of a record with active relationships', function () {
    $lab = Lab::create(['name' => 'Lab Tekkes']);
    $cat = MaterialCategory::create(['name' => 'Filament']);
    $brand = Brand::create(['name' => 'eSUN']);
    $color = Color::create(['name' => 'White']);

    RawMaterial::create([
        'lab_id' => $lab->id,
        'material_category_id' => $cat->id,
        'brand_id' => $brand->id,
        'color_id' => $color->id,
        'unit' => 'gram',
        'current_stock' => 100,
    ]);

    Livewire::test(\App\Livewire\Admin\MasterData\Index::class)
        ->call('confirmDelete', $lab->id)
        ->call('deleteRecord');

    // Lab should NOT be deleted because it has rawMaterials
    expect(Lab::where('name', 'Lab Tekkes')->exists())->toBeTrue();
});

it('allows deletion of an unused record', function () {
    $lab = Lab::create(['name' => 'Unused Lab']);

    Livewire::test(\App\Livewire\Admin\MasterData\Index::class)
        ->call('confirmDelete', $lab->id)
        ->call('deleteRecord');

    expect(Lab::where('name', 'Unused Lab')->exists())->toBeFalse();
});

it('displays usage count for records', function () {
    $lab = Lab::create(['name' => 'Lab Tekkes']);
    $cat = MaterialCategory::create(['name' => 'Filament']);
    $brand = Brand::create(['name' => 'eSUN']);
    $color = Color::create(['name' => 'White']);

    RawMaterial::create([
        'lab_id' => $lab->id,
        'material_category_id' => $cat->id,
        'brand_id' => $brand->id,
        'color_id' => $color->id,
        'unit' => 'gram',
        'current_stock' => 100,
    ]);

    Livewire::test(\App\Livewire\Admin\MasterData\Index::class)
        ->assertSee('1 reference');
});
