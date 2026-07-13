<?php

use App\Models\Brand;
use App\Models\Lab;
use App\Models\RawMaterial;
use App\Models\Role;
use App\Models\Tool;
use App\Models\User;
use App\Support\UniqueCodeGenerator;
use Illuminate\Support\Facades\Hash;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    $role = Role::firstOrCreate(['name' => 'admin_gudang'], ['display_name' => 'Admin Gudang']);
    $this->admin = User::create([
        'name' => 'Test Admin',
        'email' => 'gudang@test.com',
        'password' => Hash::make('password'),
        'role_id' => $role->id,
    ]);

    $this->brand = Brand::create(['name' => 'TestBrand']);
    $this->lab = Lab::create(['name' => 'Lab Test']);
});

// ─── UniqueCodeGenerator ────────────────────────────────

it('generates a code in the correct BAHAN format', function () {
    $date = new \DateTimeImmutable('2026-07-13');
    $code = UniqueCodeGenerator::generate('BAHAN', $date, 'raw_materials');

    expect($code)->toMatch('/^IDIG-BAHAN-20260713-[A-Z0-9]{6}$/');
});

it('generates a code in the correct ALAT format', function () {
    $date = new \DateTimeImmutable('2026-07-13');
    $code = UniqueCodeGenerator::generate('ALAT', $date, 'tools');

    expect($code)->toMatch('/^IDIG-ALAT-20260713-[A-Z0-9]{6}$/');
});

// ─── Auto-generation on model creation ──────────────────

it('auto-assigns unique_code when a RawMaterial is created', function () {
    $material = RawMaterial::create([
        'brand_id' => $this->brand->id,
        'name' => 'PLA Test',
        'unit' => 'gram',
    ]);

    expect($material->unique_code)
        ->not->toBeNull()
        ->toMatch('/^IDIG-BAHAN-\d{8}-[A-Z0-9]{6}$/');
});

it('auto-assigns unique_code when a Tool is created', function () {
    $tool = Tool::create([
        'name' => 'Printer Test',
        'lab_id' => $this->lab->id,
        'created_by' => $this->admin->id,
    ]);

    expect($tool->unique_code)
        ->not->toBeNull()
        ->toMatch('/^IDIG-ALAT-\d{8}-[A-Z0-9]{6}$/');
});

it('generates distinct codes for two materials', function () {
    $a = RawMaterial::create(['brand_id' => $this->brand->id, 'name' => 'A', 'unit' => 'gram']);
    $b = RawMaterial::create(['brand_id' => $this->brand->id, 'name' => 'B', 'unit' => 'gram']);

    expect($a->unique_code)->not->toBe($b->unique_code);
});

// ─── Scan routes (admin-only) ────────────────────────────

it('returns 200 on GET /scan/bahan/{code} for an authenticated admin', function () {
    $material = RawMaterial::create([
        'brand_id' => $this->brand->id,
        'name' => 'Resin Test',
        'unit' => 'ml',
    ]);

    $this->actingAs($this->admin)
        ->get("/scan/bahan/{$material->unique_code}")
        ->assertStatus(200);
});

it('returns 200 on GET /scan/alat/{code} for an authenticated admin', function () {
    $tool = Tool::create([
        'name' => 'Drill Test',
        'lab_id' => $this->lab->id,
        'created_by' => $this->admin->id,
    ]);

    $this->actingAs($this->admin)
        ->get("/scan/alat/{$tool->unique_code}")
        ->assertStatus(200);
});

it('redirects guest away from GET /scan/bahan', function () {
    $material = RawMaterial::create([
        'brand_id' => $this->brand->id,
        'name' => 'Guest Material',
        'unit' => 'gram',
    ]);

    $this->get("/scan/bahan/{$material->unique_code}")
        ->assertRedirect();
});

it('redirects guest away from GET /scan/alat', function () {
    $tool = Tool::create([
        'name' => 'Guest Tool',
        'lab_id' => $this->lab->id,
        'created_by' => $this->admin->id,
    ]);

    $this->get("/scan/alat/{$tool->unique_code}")
        ->assertRedirect();
});

it('returns 404 on GET /scan/bahan with unknown code when authenticated', function () {
    $this->actingAs($this->admin)
        ->get('/scan/bahan/IDIG-BAHAN-00000000-XXXXXX')
        ->assertStatus(404);
});

it('returns 404 on GET /scan/alat with unknown code when authenticated', function () {
    $this->actingAs($this->admin)
        ->get('/scan/alat/IDIG-ALAT-00000000-XXXXXX')
        ->assertStatus(404);
});

// ─── Print label route (admin-only) ─────────────────────

it('returns 200 on print-label for tool when admin is authenticated', function () {
    $tool = Tool::create([
        'name' => 'Printer Label Test',
        'lab_id' => $this->lab->id,
        'created_by' => $this->admin->id,
    ]);

    $this->actingAs($this->admin)
        ->get("/admin/print-label/tool/{$tool->id}")
        ->assertStatus(200)
        ->assertSee($tool->unique_code);
});

it('returns 200 on print-label for material when admin is authenticated', function () {
    $material = RawMaterial::create([
        'brand_id' => $this->brand->id,
        'name' => 'Print Material Test',
        'unit' => 'gram',
    ]);

    $this->actingAs($this->admin)
        ->get("/admin/print-label/material/{$material->id}")
        ->assertStatus(200)
        ->assertSee($material->unique_code);
});

it('redirects guest away from print-label route', function () {
    $tool = Tool::create([
        'name' => 'Guest Test Tool',
        'lab_id' => $this->lab->id,
        'created_by' => $this->admin->id,
    ]);

    $this->get("/admin/print-label/tool/{$tool->id}")
        ->assertRedirect();
});
