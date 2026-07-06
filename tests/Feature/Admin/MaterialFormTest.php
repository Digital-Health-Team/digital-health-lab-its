<?php

use App\Livewire\Admin\Material\Form;
use App\Models\Brand;
use App\Models\Color;
use App\Models\ItemStock;
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

it('renders the create form with all field sections', function () {
    $category = MaterialCategory::create(['name' => 'Filament']);
    $brand = Brand::create(['name' => 'eSUN', 'material_category_id' => $category->id]);

    Livewire::test(Form::class)
        ->assertSee('Category')
        ->assertSee('Brand')
        ->assertSee('Item Name')
        ->assertSee('Unit')
        ->assertSee('Initial Stock');
});

it('saves a new raw material without initial stock', function () {
    $brand = Brand::create(['name' => 'eSUN']);

    Livewire::test(Form::class)
        ->set('brandId', $brand->id)
        ->set('name', 'PLA+ Black 1.75mm')
        ->set('unit', 'gram')
        ->call('save');

    $this->assertDatabaseHas('raw_materials', [
        'brand_id' => $brand->id,
        'name' => 'PLA+ Black 1.75mm',
        'unit' => 'gram',
    ]);

    $material = RawMaterial::where('name', 'PLA+ Black 1.75mm')->first();
    $this->assertEmpty($material->stocks);
});

it('saves a new material and records initial stock + movement', function () {
    $brand = Brand::create(['name' => 'eSUN']);
    $color = Color::create(['name' => 'Black']);
    $lab = Lab::create(['name' => 'Lab A']);
    $brand->colors()->attach($color);

    Livewire::test(Form::class)
        ->set('brandId', $brand->id)
        ->set('name', 'PLA+ Black 1.75mm')
        ->set('unit', 'gram')
        ->set('colorId', $color->id)
        ->set('labId', $lab->id)
        ->set('initialQty', 500)
        ->call('save');

    $material = RawMaterial::where('name', 'PLA+ Black 1.75mm')->firstOrFail();

    $this->assertDatabaseHas('item_stocks', [
        'raw_material_id' => $material->id,
        'color_id' => $color->id,
        'lab_id' => $lab->id,
        'quantity' => 500,
    ]);

    $this->assertDatabaseHas('raw_material_movements', [
        'raw_material_id' => $material->id,
        'type' => 'in',
        'quantity' => 500,
    ]);
});

it('pre-populates fields when editing an existing material', function () {
    $category = MaterialCategory::create(['name' => 'Filament']);
    $brand = Brand::create(['name' => 'eSUN', 'material_category_id' => $category->id]);
    $material = RawMaterial::create(['brand_id' => $brand->id, 'name' => 'PLA+ White', 'unit' => 'gram']);

    $component = Livewire::test(Form::class, ['material' => $material]);

    $component
        ->assertSet('brandId', $brand->id)
        ->assertSet('categoryId', $category->id)
        ->assertSet('name', 'PLA+ White')
        ->assertSet('unit', 'gram');
});

it('updates an existing material without touching item stocks', function () {
    $brand = Brand::create(['name' => 'eSUN']);
    $material = RawMaterial::create(['brand_id' => $brand->id, 'name' => 'PLA+ White', 'unit' => 'gram']);

    $stockCountBefore = ItemStock::where('raw_material_id', $material->id)->count();

    Livewire::test(Form::class, ['material' => $material])
        ->set('name', 'PLA+ White Updated')
        ->set('unit', 'pcs')
        ->call('save');

    $this->assertDatabaseHas('raw_materials', [
        'id' => $material->id,
        'name' => 'PLA+ White Updated',
        'unit' => 'pcs',
    ]);

    $this->assertEquals($stockCountBefore, ItemStock::where('raw_material_id', $material->id)->count());
});

it('resets brandId and colorId when category changes', function () {
    $category = MaterialCategory::create(['name' => 'Filament']);
    $brand = Brand::create(['name' => 'eSUN', 'material_category_id' => $category->id]);
    $color = Color::create(['name' => 'White']);
    $brand->colors()->attach($color);

    Livewire::test(Form::class)
        ->set('categoryId', $category->id)
        ->set('brandId', $brand->id)
        ->set('colorId', $color->id)
        ->set('categoryId', 0)
        ->assertSet('brandId', 0)
        ->assertSet('colorId', null);
});

it('resets colorId when brand changes', function () {
    $brand1 = Brand::create(['name' => 'eSUN']);
    $brand2 = Brand::create(['name' => 'SUNLU']);
    $color = Color::create(['name' => 'White']);
    $brand1->colors()->attach($color);

    Livewire::test(Form::class)
        ->set('brandId', $brand1->id)
        ->set('colorId', $color->id)
        ->set('brandId', $brand2->id)
        ->assertSet('colorId', null);
});

it('redirects to admin.inventory after saving', function () {
    $brand = Brand::create(['name' => 'eSUN']);

    Livewire::test(Form::class)
        ->set('brandId', $brand->id)
        ->set('name', 'PLA+')
        ->set('unit', 'gram')
        ->call('save')
        ->assertRedirect(route('admin.inventory'));
});
