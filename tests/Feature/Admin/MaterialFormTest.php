<?php

use App\Livewire\Admin\Material\Form;
use App\Models\Attachment;
use App\Models\Brand;
use App\Models\Color;
use App\Models\ItemStock;
use App\Models\Lab;
use App\Models\MaterialCategory;
use App\Models\RawMaterial;
use App\Models\RawMaterialMovement;
use App\Models\Reimbursement;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('public');

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

it('saves a new material and records initial stock like a restock', function () {
    $brand = Brand::create(['name' => 'eSUN']);
    $color = Color::create(['name' => 'Black']);
    $lab = Lab::create(['name' => 'Lab A']);

    Livewire::test(Form::class)
        ->set('brandId', $brand->id)
        ->set('name', 'PLA+ Black 1.75mm')
        ->set('unit', 'gram')
        ->set('colorIds', [$color->id])
        ->set('colorQuantities', [$color->id => 500])
        ->set('colorAmounts', [$color->id => 150000])
        ->set('colorNotes', [$color->id => 'Supplier Tokopedia'])
        ->set('colorProofs.'.$color->id, UploadedFile::fake()->image('proof.jpg'))
        ->set('labId', $lab->id)
        ->call('save')
        ->assertHasNoErrors();

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
        'notes' => 'Supplier Tokopedia',
    ]);

    $reimbursement = Reimbursement::firstOrFail();
    expect($reimbursement->title)->toBe('Initial Stock — eSUN PLA+ Black 1.75mm')
        ->and($reimbursement->total_amount)->toBe(150000);

    $attachment = Attachment::firstOrFail();
    expect($attachment->attachable_type)->toBe(Reimbursement::class)
        ->and($attachment->attachable_id)->toBe($reimbursement->id);
    Storage::disk('public')->assertExists($attachment->file_url);
});

it('records stock per color and syncs all selected colors to the brand', function () {
    $brand = Brand::create(['name' => 'eSUN']);
    $preAttached = Color::create(['name' => 'Green']);
    $brand->colors()->attach($preAttached);

    $black = Color::create(['name' => 'Black']);
    $white = Color::create(['name' => 'White']);
    $red = Color::create(['name' => 'Red']);
    $lab = Lab::create(['name' => 'Lab A']);

    Livewire::test(Form::class)
        ->set('brandId', $brand->id)
        ->set('name', 'PLA+ 1.75mm')
        ->set('unit', 'gram')
        ->set('colorIds', [$black->id, $white->id, $red->id])
        ->set('colorQuantities', [$black->id => 500, $white->id => 250, $red->id => 0])
        ->set('colorAmounts', [$black->id => 100000, $white->id => 50000])
        ->set('colorNotes', [$black->id => 'Batch #1', $white->id => 'Batch #2'])
        ->set('colorProofs.'.$black->id, UploadedFile::fake()->image('p1.jpg'))
        ->set('colorProofs.'.$white->id, UploadedFile::fake()->image('p2.jpg'))
        ->set('labId', $lab->id)
        ->call('save')
        ->assertHasNoErrors();

    $material = RawMaterial::where('name', 'PLA+ 1.75mm')->firstOrFail();

    $this->assertDatabaseHas('item_stocks', [
        'raw_material_id' => $material->id,
        'color_id' => $black->id,
        'lab_id' => $lab->id,
        'quantity' => 500,
    ]);
    $this->assertDatabaseHas('item_stocks', [
        'raw_material_id' => $material->id,
        'color_id' => $white->id,
        'lab_id' => $lab->id,
        'quantity' => 250,
    ]);

    // Zero-quantity color gets no stock row, only the brand link.
    $this->assertDatabaseMissing('item_stocks', [
        'raw_material_id' => $material->id,
        'color_id' => $red->id,
    ]);

    expect(RawMaterialMovement::where('raw_material_id', $material->id)->where('type', 'in')->count())->toBe(2)
        ->and(Reimbursement::count())->toBe(2)
        ->and(Attachment::count())->toBe(2);

    foreach ([$black, $white, $red] as $color) {
        $this->assertDatabaseHas('brand_colors', ['brand_id' => $brand->id, 'color_id' => $color->id]);
    }

    // Existing pivot links are never detached.
    $this->assertDatabaseHas('brand_colors', ['brand_id' => $brand->id, 'color_id' => $preAttached->id]);
});

it('requires a lab when any selected color has a quantity', function () {
    $brand = Brand::create(['name' => 'eSUN']);
    $color = Color::create(['name' => 'Black']);

    Livewire::test(Form::class)
        ->set('brandId', $brand->id)
        ->set('name', 'PLA+')
        ->set('unit', 'gram')
        ->set('colorIds', [$color->id])
        ->set('colorQuantities', [$color->id => 100])
        ->call('save')
        ->assertHasErrors(['labId']);
});

it('requires amount, notes, and proof for each color with a quantity', function () {
    $brand = Brand::create(['name' => 'eSUN']);
    $color = Color::create(['name' => 'Black']);
    $lab = Lab::create(['name' => 'Lab A']);

    Livewire::test(Form::class)
        ->set('brandId', $brand->id)
        ->set('name', 'PLA+')
        ->set('unit', 'gram')
        ->set('colorIds', [$color->id])
        ->set('colorQuantities', [$color->id => 100])
        ->set('labId', $lab->id)
        ->call('save')
        ->assertHasErrors([
            "colorAmounts.{$color->id}",
            "colorNotes.{$color->id}",
            "colorProofs.{$color->id}",
        ]);

    expect(RawMaterial::count())->toBe(0);
});

it('syncs colors without creating stock when all quantities are blank', function () {
    $brand = Brand::create(['name' => 'eSUN']);
    $color = Color::create(['name' => 'Black']);

    Livewire::test(Form::class)
        ->set('brandId', $brand->id)
        ->set('name', 'PLA+')
        ->set('unit', 'gram')
        ->set('colorIds', [$color->id])
        ->call('save');

    $material = RawMaterial::where('name', 'PLA+')->firstOrFail();

    $this->assertDatabaseHas('brand_colors', ['brand_id' => $brand->id, 'color_id' => $color->id]);
    expect(ItemStock::where('raw_material_id', $material->id)->count())->toBe(0);
    expect(RawMaterialMovement::where('raw_material_id', $material->id)->count())->toBe(0);
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

it('resets brandId but keeps selected colors when category changes', function () {
    $category = MaterialCategory::create(['name' => 'Filament']);
    $brand = Brand::create(['name' => 'eSUN', 'material_category_id' => $category->id]);
    $color = Color::create(['name' => 'White']);

    Livewire::test(Form::class)
        ->set('categoryId', $category->id)
        ->set('brandId', $brand->id)
        ->set('colorIds', [$color->id])
        ->set('categoryId', 0)
        ->assertSet('brandId', 0)
        ->assertSet('colorIds', [$color->id]);
});

it('prunes quantities, amounts, and notes when a color is unchecked', function () {
    $black = Color::create(['name' => 'Black']);
    $white = Color::create(['name' => 'White']);

    Livewire::test(Form::class)
        ->set('colorIds', [$black->id, $white->id])
        ->set('colorQuantities', [$black->id => 100, $white->id => 200])
        ->set('colorAmounts', [$black->id => 10000, $white->id => 20000])
        ->set('colorNotes', [$black->id => 'A', $white->id => 'B'])
        ->set('colorIds', [$black->id])
        ->assertSet('colorQuantities', [$black->id => 100])
        ->assertSet('colorAmounts', [$black->id => 10000])
        ->assertSet('colorNotes', [$black->id => 'A']);
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

// ==========================================
// QUICK CREATE
// ==========================================

it('quick-creates a category and selects it', function () {
    $component = Livewire::test(Form::class)
        ->call('quickCreate', 'category', 'Resin');

    $category = MaterialCategory::where('name', 'Resin')->firstOrFail();
    $component
        ->assertSet('categoryId', $category->id)
        ->assertSet('brandId', 0);
});

it('quick-creates a brand with the selected category and selects it', function () {
    $category = MaterialCategory::create(['name' => 'Filament']);

    $component = Livewire::test(Form::class)
        ->set('categoryId', $category->id)
        ->call('quickCreate', 'brand', 'SUNLU');

    $brand = Brand::where('name', 'SUNLU')->firstOrFail();
    expect($brand->material_category_id)->toBe($category->id);
    $component->assertSet('brandId', $brand->id);
});

it('quick-creates a color and adds it to the selection', function () {
    $component = Livewire::test(Form::class)
        ->call('quickCreate', 'color', 'Magenta');

    $color = Color::where('name', 'Magenta')->firstOrFail();
    expect($component->get('colorIds'))->toContain((string) $color->id);
});

it('quick-creates a color with a hex swatch', function () {
    Livewire::test(Form::class)
        ->call('quickCreate', 'color', 'Magenta', '#e0218a');

    $this->assertDatabaseHas('colors', ['name' => 'Magenta', 'hex' => '#E0218A']);
});

it('ignores an invalid hex on color quick-create', function () {
    Livewire::test(Form::class)
        ->call('quickCreate', 'color', 'Magenta', 'not-a-hex');

    $this->assertDatabaseHas('colors', ['name' => 'Magenta', 'hex' => null]);
});

it('quick-creates a lab and selects it', function () {
    Livewire::test(Form::class)
        ->call('quickCreate', 'lab', 'Lab B')
        ->assertSet('labId', Lab::where('name', 'Lab B')->first()->id);
});

it('rejects quick-create with a duplicate name', function () {
    Brand::create(['name' => 'eSUN']);

    Livewire::test(Form::class)
        ->call('quickCreate', 'brand', 'eSUN')
        ->assertSet('brandId', 0);

    expect(Brand::where('name', 'eSUN')->count())->toBe(1);
});

it('rejects quick-create with a blank name', function () {
    Livewire::test(Form::class)
        ->call('quickCreate', 'lab', '   ')
        ->assertSet('labId', null);

    expect(Lab::count())->toBe(0);
});

// ==========================================
// MANAGE MODAL
// ==========================================

it('renames a record from the manage modal', function () {
    $brand = Brand::create(['name' => 'eSUN']);

    Livewire::test(Form::class)
        ->call('openManage', 'brand')
        ->call('startRename', $brand->id)
        ->assertSet('manageEditName', 'eSUN')
        ->set('manageEditName', 'eSUN Official')
        ->call('saveRename');

    $this->assertDatabaseHas('brands', ['id' => $brand->id, 'name' => 'eSUN Official']);
});

it('updates a color hex from the manage modal', function () {
    $color = Color::create(['name' => 'Black', 'hex' => '#1C1C1C']);

    Livewire::test(Form::class)
        ->call('openManage', 'color')
        ->call('startRename', $color->id)
        ->assertSet('manageEditHex', '#1C1C1C')
        ->set('manageEditHex', '#222222')
        ->call('saveRename');

    $this->assertDatabaseHas('colors', ['id' => $color->id, 'hex' => '#222222']);
});

it('rejects a rename to a duplicate name', function () {
    Brand::create(['name' => 'eSUN']);
    $brand = Brand::create(['name' => 'SUNLU']);

    Livewire::test(Form::class)
        ->call('openManage', 'brand')
        ->call('startRename', $brand->id)
        ->set('manageEditName', 'eSUN')
        ->call('saveRename')
        ->assertHasErrors(['manageEditName']);

    $this->assertDatabaseHas('brands', ['id' => $brand->id, 'name' => 'SUNLU']);
});

it('blocks deleting a record that is in use', function (string $entity, Closure $setup) {
    $record = $setup();

    Livewire::test(Form::class)
        ->call('openManage', $entity)
        ->call('deleteEntity', $record->id);

    $this->assertDatabaseHas($record->getTable(), ['id' => $record->id]);
})->with([
    'category with brands' => ['category', function () {
        $category = MaterialCategory::create(['name' => 'Filament']);
        Brand::create(['name' => 'eSUN', 'material_category_id' => $category->id]);

        return $category;
    }],
    'brand with materials' => ['brand', function () {
        $brand = Brand::create(['name' => 'eSUN']);
        RawMaterial::create(['brand_id' => $brand->id, 'name' => 'PLA+', 'unit' => 'gram']);

        return $brand;
    }],
    'color with stock' => ['color', function () {
        $brand = Brand::create(['name' => 'eSUN']);
        $material = RawMaterial::create(['brand_id' => $brand->id, 'name' => 'PLA+', 'unit' => 'gram']);
        $color = Color::create(['name' => 'Black']);
        $lab = Lab::create(['name' => 'Lab A']);
        ItemStock::create(['raw_material_id' => $material->id, 'color_id' => $color->id, 'lab_id' => $lab->id, 'quantity' => 10]);

        return $color;
    }],
    'lab with stock' => ['lab', function () {
        $brand = Brand::create(['name' => 'eSUN']);
        $material = RawMaterial::create(['brand_id' => $brand->id, 'name' => 'PLA+', 'unit' => 'gram']);
        $color = Color::create(['name' => 'Black']);
        $lab = Lab::create(['name' => 'Lab A']);
        ItemStock::create(['raw_material_id' => $material->id, 'color_id' => $color->id, 'lab_id' => $lab->id, 'quantity' => 10]);

        return $lab;
    }],
]);

it('deletes an unused record and clears a stale lab selection', function () {
    $lab = Lab::create(['name' => 'Lab A']);

    Livewire::test(Form::class)
        ->set('labId', $lab->id)
        ->call('openManage', 'lab')
        ->call('deleteEntity', $lab->id)
        ->assertSet('labId', null);

    $this->assertDatabaseMissing('labs', ['id' => $lab->id]);
});

it('deletes an unused color and removes it from the selection', function () {
    $color = Color::create(['name' => 'Black']);

    Livewire::test(Form::class)
        ->set('colorIds', [$color->id])
        ->set('colorQuantities', [$color->id => 100])
        ->call('openManage', 'color')
        ->call('deleteEntity', $color->id)
        ->assertSet('colorIds', [])
        ->assertSet('colorQuantities', []);

    $this->assertDatabaseMissing('colors', ['id' => $color->id]);
});

it('rejects an unknown manage entity', function () {
    Livewire::test(Form::class)
        ->call('openManage', 'users')
        ->assertStatus(404);
});
