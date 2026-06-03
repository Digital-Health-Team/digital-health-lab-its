<?php

use App\Actions\RawMaterial\RestockMaterialAction;
use App\DTOs\RawMaterial\RestockMaterialData;
use App\Models\Attachment;
use App\Models\Brand;
use App\Models\Color;
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

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('public');

    // Create role + user manually to avoid factory 2FA column mismatch
    $role = Role::create(['name' => 'admin_lab', 'display_name' => 'Admin Lab']);

    $this->admin = User::create([
        'name' => 'Test Admin',
        'email' => 'admin@test.com',
        'password' => Hash::make('password'),
        'role_id' => $role->id,
    ]);

    $this->actingAs($this->admin);

    // Create master lookup records
    $lab = Lab::create(['name' => 'Lab Tekkes']);
    $category = MaterialCategory::create(['name' => 'Filament']);
    $brand = Brand::create(['name' => 'eSUN']);
    $color = Color::create(['name' => 'White']);

    $this->material = RawMaterial::create([
        'lab_id' => $lab->id,
        'material_category_id' => $category->id,
        'brand_id' => $brand->id,
        'color_id' => $color->id,
        'unit' => 'gram',
        'current_stock' => 100,
    ]);
});

it('creates reimbursement, attachment, and movement atomically on restock', function () {
    $file = UploadedFile::fake()->image('receipt.jpg');

    $dto = new RestockMaterialData(
        raw_material_id: $this->material->id,
        quantity: 500,
        total_amount: 150000,
        reimbursement_title: 'Restock eSUN PLA White',
        notes: 'Supplier Tokopedia, Batch #001',
        payment_proof: $file
    );

    app(RestockMaterialAction::class)->execute($dto);

    // 1. Reimbursement was created with correct data
    $reimbursement = Reimbursement::first();
    expect($reimbursement)->not->toBeNull()
        ->and($reimbursement->title)->toBe('Restock eSUN PLA White')
        ->and($reimbursement->total_amount)->toBe(150000)
        ->and($reimbursement->status)->toBe('pending')
        ->and($reimbursement->user_id)->toBe($this->admin->id);

    // 2. Polymorphic attachment linked to reimbursement
    $attachment = Attachment::first();
    expect($attachment)->not->toBeNull()
        ->and($attachment->attachable_type)->toBe(Reimbursement::class)
        ->and($attachment->attachable_id)->toBe($reimbursement->id)
        ->and($attachment->is_primary)->toBeTrue();
    Storage::disk('public')->assertExists($attachment->file_url);

    // 3. Material movement recorded with reimbursement link
    $movement = RawMaterialMovement::first();
    expect($movement->type)->toBe('in')
        ->and($movement->quantity)->toBe(500)
        ->and($movement->reimbursement_id)->toBe($reimbursement->id)
        ->and($movement->raw_material_id)->toBe($this->material->id)
        ->and($movement->created_by)->toBe($this->admin->id);

    // 4. Stock was incremented (100 + 500 = 600)
    $this->material->refresh();
    expect($this->material->current_stock)->toBe(600);
});

it('can access attachments via reimbursement morphMany relationship', function () {
    $file = UploadedFile::fake()->image('proof.png');

    $dto = new RestockMaterialData(
        raw_material_id: $this->material->id,
        quantity: 250,
        total_amount: 75000,
        reimbursement_title: 'Restock Resin Clear',
        notes: 'Direct supplier',
        payment_proof: $file
    );

    app(RestockMaterialAction::class)->execute($dto);

    $reimbursement = Reimbursement::first();
    expect($reimbursement->attachments)->toHaveCount(1)
        ->and($reimbursement->attachments->first()->file_type)->toContain('image');
});
