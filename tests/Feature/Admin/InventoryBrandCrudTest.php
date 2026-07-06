<?php

use App\Models\Brand;
use App\Models\Color;
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

it('creates a brand with category and colors assigned', function () {
    $category = MaterialCategory::create(['name' => 'Filament']);
    $white = Color::create(['name' => 'White']);
    $black = Color::create(['name' => 'Black']);

    Livewire::test(\App\Livewire\Admin\Inventory\Index::class)
        ->set('section', 'brands')
        ->call('createLookup')
        ->set('lookupName', 'eSUN')
        ->set('brandCategoryId', $category->id)
        ->set('brandColorIds', [$white->id, $black->id])
        ->call('saveLookup');

    $brand = Brand::where('name', 'eSUN')->firstOrFail();
    expect($brand->material_category_id)->toBe($category->id);
    expect($brand->colors->pluck('id')->toArray())->toContain($white->id, $black->id);
});

it('edits a brand — category changes and colors are synced', function () {
    $cat1 = MaterialCategory::create(['name' => 'Filament']);
    $cat2 = MaterialCategory::create(['name' => 'Resin']);
    $white = Color::create(['name' => 'White']);
    $grey = Color::create(['name' => 'Grey']);

    $brand = Brand::create(['name' => 'eSUN', 'material_category_id' => $cat1->id]);
    $brand->colors()->sync([$white->id]);

    Livewire::test(\App\Livewire\Admin\Inventory\Index::class)
        ->set('section', 'brands')
        ->call('editLookup', $brand->id)
        ->assertSet('brandCategoryId', $cat1->id)
        ->assertSet('brandColorIds', [$white->id])
        ->set('brandCategoryId', $cat2->id)
        ->set('brandColorIds', [$grey->id])
        ->call('saveLookup');

    $brand->refresh()->load('colors');
    expect($brand->material_category_id)->toBe($cat2->id);
    expect($brand->colors->pluck('id')->toArray())->toBe([$grey->id]);
    expect($brand->colors->pluck('id')->toArray())->not->toContain($white->id);
});

it('filters color options to brand colors when a material is selected', function () {
    $brandColor = Color::create(['name' => 'Grey']);
    $otherColor = Color::create(['name' => 'Red']);
    $brand = Brand::create(['name' => 'Anycubic']);
    $brand->colors()->sync([$brandColor->id]);

    $material = RawMaterial::create([
        'brand_id' => $brand->id,
        'name' => 'Standard Resin 1L',
        'unit' => 'ml',
    ]);

    $component = Livewire::test(\App\Livewire\Admin\Inventory\Index::class)
        ->set('section', 'materials')
        ->call('viewHistory', $material->id);

    // The rendered colorOptions should only include the brand's colors
    $colorOptions = $component->viewData('colorOptions');
    expect($colorOptions->pluck('id')->toArray())->toContain($brandColor->id);
    expect($colorOptions->pluck('id')->toArray())->not->toContain($otherColor->id);
});

it('resets brand fields when switching away from brands tab', function () {
    $color = Color::create(['name' => 'Blue']);

    $component = Livewire::test(\App\Livewire\Admin\Inventory\Index::class)
        ->set('section', 'brands')
        ->set('brandColorIds', [$color->id])
        ->set('brandCategoryId', 99)
        ->set('section', 'categories');

    $component->assertSet('brandCategoryId', 0);
    $component->assertSet('brandColorIds', []);
});

it('viewLookup sets activeLookupId and clearLookup resets it', function () {
    $lab = \App\Models\Lab::create(['name' => 'Test Lab']);

    Livewire::test(\App\Livewire\Admin\Inventory\Index::class)
        ->set('section', 'labs')
        ->call('viewLookup', $lab->id)
        ->assertSet('activeLookupId', $lab->id)
        ->call('clearLookup')
        ->assertSet('activeLookupId', null);
});

it('activeLookupId resets when switching tabs', function () {
    $lab = \App\Models\Lab::create(['name' => 'Test Lab']);

    Livewire::test(\App\Livewire\Admin\Inventory\Index::class)
        ->set('section', 'labs')
        ->call('viewLookup', $lab->id)
        ->assertSet('activeLookupId', $lab->id)
        ->set('section', 'categories')
        ->assertSet('activeLookupId', null);
});
