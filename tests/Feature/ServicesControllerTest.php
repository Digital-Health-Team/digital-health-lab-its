<?php

use App\Models\Color;
use App\Models\FilamentType;
use App\Models\Service;

beforeEach(function () {
    // Seed the minimum data required for the controller
    Service::create([
        'name' => 'Jasa Print 3D',
        'service_type' => 'printing',
        'description' => 'Test printing service',
        'base_price' => 2000,
    ]);
    Service::create([
        'name' => 'Jasa Desain 3D',
        'service_type' => 'design',
        'description' => 'Test design service',
        'base_price' => 150000,
    ]);
    Service::create([
        'name' => 'Jasa Scanning 3D',
        'service_type' => 'scanning',
        'description' => 'Test scanning service',
        'base_price' => 100000,
    ]);

    FilamentType::insert([
        ['code' => 'PLA',  'name' => 'PLA',  'scientific_name' => 'Polylactic Acid',                   'price_per_gram' => 1500, 'description' => null, 'sort_order' => 1, 'is_active' => true,  'created_at' => now(), 'updated_at' => now()],
        ['code' => 'PETG', 'name' => 'PETG', 'scientific_name' => 'Polyethylene Terephthalate Glycol', 'price_per_gram' => 2000, 'description' => null, 'sort_order' => 2, 'is_active' => true,  'created_at' => now(), 'updated_at' => now()],
        ['code' => 'TPU',  'name' => 'TPU',  'scientific_name' => 'Thermoplastic Polyurethane',        'price_per_gram' => 2500, 'description' => null, 'sort_order' => 3, 'is_active' => false, 'created_at' => now(), 'updated_at' => now()],
    ]);

    Color::insert([
        ['name' => 'White', 'hex' => '#FFFFFF', 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Black', 'hex' => '#1A1A1A', 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Blue',  'hex' => '#2F6BE0', 'created_at' => now(), 'updated_at' => now()],
    ]);
});

// ── /services (index) ─────────────────────────────────────────────

test('services index page loads with db services prop', function () {
    $response = $this->get('/services');

    $response->assertOk();
    $response->assertInertia(
        fn ($page) => $page
            ->component('Features/Services/Pages/ServicesPage')
            ->has('dbServices', 3)
    );
});

// ── /services/printing (show) ──────────────────────────────────────

test('printing page renders with filaments prop', function () {
    $response = $this->get('/services/printing');

    $response->assertOk();
    $response->assertInertia(
        fn ($page) => $page
            ->component('Features/Services/Pages/ServiceRequestPage')
            ->where('service', 'printing')
            ->has('filaments', 2) // only is_active=true: PLA + PETG
    );
});

test('printing page filament items have required keys', function () {
    $response = $this->get('/services/printing');

    $response->assertOk();
    $response->assertInertia(
        fn ($page) => $page
            ->has('filaments.0', fn ($filament) => $filament
                ->has('code')
                ->has('name')
                ->has('scientificName')
                ->has('pricePerGram')
                ->has('priceLabel')
                ->has('description')
            )
    );
});

test('printing page renders with colors prop', function () {
    $response = $this->get('/services/printing');

    $response->assertOk();
    $response->assertInertia(
        fn ($page) => $page
            ->has('colors', 3)
    );
});

test('printing page colors have hex key', function () {
    $response = $this->get('/services/printing');

    $response->assertOk();
    $response->assertInertia(
        fn ($page) => $page
            ->has('colors.0', fn ($color) => $color
                ->has('id')
                ->has('name')
                ->has('hex')
            )
    );
});

test('printing page first filament priceLabel is formatted as Rupiah', function () {
    $response = $this->get('/services/printing');

    $response->assertOk();
    $response->assertInertia(
        fn ($page) => $page
            ->where('filaments.0.priceLabel', 'Rp 1.500')
    );
});

// ── /services/design and /services/scanning get empty arrays ──────

test('design page receives empty filaments and colors', function () {
    $response = $this->get('/services/design');

    $response->assertOk();
    $response->assertInertia(
        fn ($page) => $page
            ->where('service', 'design')
            ->where('filaments', [])
            ->where('colors', [])
    );
});

test('scanning page receives empty filaments and colors', function () {
    $response = $this->get('/services/scanning');

    $response->assertOk();
    $response->assertInertia(
        fn ($page) => $page
            ->where('service', 'scanning')
            ->where('filaments', [])
            ->where('colors', [])
    );
});

// ── 404 for unknown service slug ───────────────────────────────────

test('unknown service slug returns 404', function () {
    $this->get('/services/unknown')->assertNotFound();
});
