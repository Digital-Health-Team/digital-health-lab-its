<?php

use App\Actions\RawMaterial\RestockMaterialAction;
use App\DTOs\RawMaterial\RestockMaterialData;
use App\Models\Attachment;
use App\Models\Brand;
use App\Models\Color;
use App\Models\ItemStock;
use App\Models\Lab;
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

    $role = Role::create(['name' => 'admin_lab', 'display_name' => 'Admin Lab']);

    $this->admin = User::create([
        'name' => 'Test Admin',
        'email' => 'admin@test.com',
        'password' => Hash::make('password'),
        'role_id' => $role->id,
    ]);

    $this->actingAs($this->admin);

    $brand = Brand::create(['name' => 'eSUN']);
    $this->color = Color::create(['name' => 'White']);
    $this->lab = Lab::create(['name' => 'Lab Tekkes']);

    $this->material = RawMaterial::create([
        'brand_id' => $brand->id,
        'name' => 'PLA+ 1.75mm 1kg',
        'unit' => 'gram',
    ]);
});

it('creates reimbursement, attachment, movement, and item_stock atomically on restock', function () {
    $file = UploadedFile::fake()->image('receipt.jpg');

    $dto = new RestockMaterialData(
        raw_material_id: $this->material->id,
        color_id: $this->color->id,
        lab_id: $this->lab->id,
        quantity: 500,
        total_amount: 150000,
        reimbursement_title: 'Restock eSUN PLA White',
        notes: 'Supplier Tokopedia, Batch #001',
        payment_proof: $file
    );

    app(RestockMaterialAction::class)->execute($dto);

    // 1. Reimbursement created with correct data
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

    // 3. Movement recorded
    $movement = RawMaterialMovement::first();
    expect($movement->type)->toBe('in')
        ->and($movement->quantity)->toBe(500)
        ->and($movement->reimbursement_id)->toBe($reimbursement->id)
        ->and($movement->raw_material_id)->toBe($this->material->id)
        ->and($movement->created_by)->toBe($this->admin->id);

    // 4. item_stocks row created/incremented for item+color+lab
    $stock = ItemStock::where([
        'raw_material_id' => $this->material->id,
        'color_id' => $this->color->id,
        'lab_id' => $this->lab->id,
    ])->first();

    expect($stock)->not->toBeNull()
        ->and($stock->quantity)->toBe(500);
});

it('increments existing item_stock on second restock to same color+lab', function () {
    $file1 = UploadedFile::fake()->image('receipt1.jpg');
    $file2 = UploadedFile::fake()->image('receipt2.jpg');

    $base = [
        'raw_material_id' => $this->material->id,
        'color_id' => $this->color->id,
        'lab_id' => $this->lab->id,
        'total_amount' => 100000,
        'notes' => 'Batch',
        'reimbursement_title' => 'Restock #1',
    ];

    app(RestockMaterialAction::class)->execute(
        new RestockMaterialData(...[...$base, 'quantity' => 300, 'payment_proof' => $file1])
    );

    app(RestockMaterialAction::class)->execute(
        new RestockMaterialData(...[...$base, 'quantity' => 200, 'reimbursement_title' => 'Restock #2', 'payment_proof' => $file2])
    );

    $stock = ItemStock::where([
        'raw_material_id' => $this->material->id,
        'color_id' => $this->color->id,
        'lab_id' => $this->lab->id,
    ])->first();

    expect($stock->quantity)->toBe(500)
        ->and(ItemStock::count())->toBe(1); // single row — not duplicated
});

it('creates separate item_stock rows for different labs', function () {
    $lab2 = Lab::create(['name' => 'Lab Praktikum']);

    app(RestockMaterialAction::class)->execute(new RestockMaterialData(
        raw_material_id: $this->material->id,
        color_id: $this->color->id,
        lab_id: $this->lab->id,
        quantity: 100,
        total_amount: 50000,
        reimbursement_title: 'Stock Lab Tekkes',
        notes: 'Initial',
        payment_proof: UploadedFile::fake()->image('r1.jpg')
    ));

    app(RestockMaterialAction::class)->execute(new RestockMaterialData(
        raw_material_id: $this->material->id,
        color_id: $this->color->id,
        lab_id: $lab2->id,
        quantity: 200,
        total_amount: 80000,
        reimbursement_title: 'Stock Lab Praktikum',
        notes: 'Initial',
        payment_proof: UploadedFile::fake()->image('r2.jpg')
    ));

    expect(ItemStock::count())->toBe(2)
        ->and(ItemStock::where('lab_id', $this->lab->id)->first()->quantity)->toBe(100)
        ->and(ItemStock::where('lab_id', $lab2->id)->first()->quantity)->toBe(200);
});

it('can access attachments via reimbursement morphMany relationship', function () {
    $file = UploadedFile::fake()->image('proof.png');

    $dto = new RestockMaterialData(
        raw_material_id: $this->material->id,
        color_id: $this->color->id,
        lab_id: $this->lab->id,
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
