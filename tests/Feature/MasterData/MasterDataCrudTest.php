<?php

use App\Models\Brand;
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
    Brand::create(['name' => 'eSUN']);
    MaterialCategory::create(['name' => 'Filament']);

    Livewire::test(\App\Livewire\Admin\MasterData\Index::class)
        ->assertSee('Filament')
        ->assertSee('Categories')
        ->assertSee('Brands')
        ->assertSee('Colors');
});

it('switches tabs and queries the correct model', function () {
    MaterialCategory::create(['name' => 'Resin']);
    Brand::create(['name' => 'TestBrandXYZ']);

    Livewire::test(\App\Livewire\Admin\MasterData\Index::class)
        ->assertSee('Resin')
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
    $category = MaterialCategory::create(['name' => 'Old Category']);

    Livewire::test(\App\Livewire\Admin\MasterData\Index::class)
        ->set('activeTab', 'categories')
        ->call('edit', $category->id)
        ->set('name', 'New Category')
        ->call('save');

    expect($category->fresh()->name)->toBe('New Category');
});

it('blocks deletion of a brand that still has raw materials', function () {
    $brand = Brand::create(['name' => 'eSUN']);

    RawMaterial::create([
        'brand_id' => $brand->id,
        'name' => 'PLA+ 1kg',
        'unit' => 'gram',
    ]);

    Livewire::test(\App\Livewire\Admin\MasterData\Index::class)
        ->set('activeTab', 'brands')
        ->call('confirmDelete', $brand->id)
        ->call('deleteRecord');

    expect(Brand::where('name', 'eSUN')->exists())->toBeTrue();
});

it('allows deletion of an unused record', function () {
    $category = MaterialCategory::create(['name' => 'Unused Category']);

    Livewire::test(\App\Livewire\Admin\MasterData\Index::class)
        ->set('activeTab', 'categories')
        ->call('confirmDelete', $category->id)
        ->call('deleteRecord');

    expect(MaterialCategory::where('name', 'Unused Category')->exists())->toBeFalse();
});

it('blocks deletion of a category that still has brands', function () {
    $category = MaterialCategory::create(['name' => 'Filament']);
    Brand::create(['name' => 'eSUN', 'material_category_id' => $category->id]);

    Livewire::test(\App\Livewire\Admin\MasterData\Index::class)
        ->set('activeTab', 'categories')
        ->call('confirmDelete', $category->id)
        ->call('deleteRecord');

    expect(MaterialCategory::where('name', 'Filament')->exists())->toBeTrue();
});
