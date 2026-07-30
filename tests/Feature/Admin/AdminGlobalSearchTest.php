<?php

use App\Models\Event;
use App\Models\OpenSourceProject;
use App\Models\Product;
use App\Models\Publication;
use App\Models\Role;
use App\Models\Service;
use App\Models\ServiceBooking;
use App\Models\Training;
use App\Models\User;

beforeEach(function () {
    $this->withoutVite();

    foreach ([1 => 'super_admin', 2 => 'admin_lab', 3 => 'admin_gudang', 4 => 'mahasiswa', 5 => 'user_publik'] as $id => $name) {
        Role::firstOrCreate(['id' => $id], ['name' => $name]);
    }
});

function searchAdminUser(): User
{
    return User::factory()->create([
        'role_id' => Role::where('name', 'admin_lab')->first()->id,
    ]);
}

function searchGuestUser(): User
{
    return User::factory()->create([
        'role_id' => Role::where('name', 'user_publik')->first()->id,
    ]);
}

it('redirects unauthenticated users away from admin search', function () {
    $this->get('/admin/search?q=test')->assertRedirect();
});

it('blocks non-admin users from accessing admin search', function () {
    $this->actingAs(searchGuestUser())
        ->get('/admin/search?q=test')
        ->assertRedirect();
});

it('renders the search page for admin_lab users', function () {
    $this->actingAs(searchAdminUser())
        ->get('/admin/search')
        ->assertOk()
        ->assertSee('Global Search');
});

it('shows idle state when query is shorter than 2 characters', function () {
    $this->actingAs(searchAdminUser())
        ->get('/admin/search?q=x')
        ->assertOk()
        ->assertSee('Start typing to search');
});

it('finds open-source projects by title', function () {
    $admin = searchAdminUser();

    OpenSourceProject::create([
        'user_id' => $admin->id,
        'title' => 'Adaptive Prosthetic Hand Mk3',
        'slug' => 'adaptive-prosthetic-hand-mk3',
        'status' => 'published',
        'category' => '3d_model',
    ]);

    $this->actingAs($admin)
        ->get('/admin/search?q=Prosthetic')
        ->assertOk()
        ->assertSee('Adaptive Prosthetic Hand Mk3');
});

it('finds publications by author', function () {
    Publication::factory()->create([
        'title' => 'Gait Analysis Study',
        'slug' => 'gait-analysis-study',
        'author' => 'Dr. Wirawan Kusuma',
        'category' => 'Papers',
        'published_at' => now(),
    ]);

    $this->actingAs(searchAdminUser())
        ->get('/admin/search?q=Wirawan')
        ->assertOk()
        ->assertSee('Dr. Wirawan Kusuma');
});

it('finds publications by title', function () {
    Publication::factory()->create([
        'title' => 'Bioprinting Cartilage Scaffolds',
        'slug' => 'bioprinting-cartilage',
        'author' => 'Some Author',
        'category' => 'Journals',
        'published_at' => now(),
    ]);

    $this->actingAs(searchAdminUser())
        ->get('/admin/search?q=Bioprinting')
        ->assertOk()
        ->assertSee('Bioprinting Cartilage Scaffolds');
});

it('finds products by name', function () {
    $admin = searchAdminUser();

    Product::create([
        'creator_id' => $admin->id,
        'name' => 'Finger Splint Set',
        'price_min' => 8000,
        'price_max' => 8000,
        'is_active' => true,
    ]);

    $this->actingAs($admin)
        ->get('/admin/search?q=Finger')
        ->assertOk()
        ->assertSee('Finger Splint Set');
});

it('finds services by name', function () {
    Service::create([
        'name' => 'Custom FDM Printing',
        'service_type' => 'printing',
        'base_price' => 2000,
    ]);

    $this->actingAs(searchAdminUser())
        ->get('/admin/search?q=FDM')
        ->assertOk()
        ->assertSee('Custom FDM Printing');
});

it('finds trainings by title', function () {
    Training::factory()->create([
        'title' => 'Intro to 3D Bioprinting',
        'instructor_name' => 'Dr. Sari',
        'level' => 'Beginner',
    ]);

    $this->actingAs(searchAdminUser())
        ->get('/admin/search?q=Bioprinting')
        ->assertOk()
        ->assertSee('Intro to 3D Bioprinting');
});

it('finds trainings by instructor name', function () {
    Training::factory()->create([
        'title' => 'Orthotic Design Workshop',
        'instructor_name' => 'Prof. Budiman Santoso',
        'level' => 'Intermediate',
    ]);

    $this->actingAs(searchAdminUser())
        ->get('/admin/search?q=Budiman')
        ->assertOk()
        ->assertSee('Prof. Budiman Santoso');
});

it('finds events by name', function () {
    Event::create([
        'name' => 'Innovatech 2025',
        'year' => 2025,
        'theme_title' => 'Biomedical Innovation',
        'is_active' => true,
    ]);

    $this->actingAs(searchAdminUser())
        ->get('/admin/search?q=Innovatech')
        ->assertOk()
        ->assertSee('Innovatech 2025');
});

it('finds events by theme title', function () {
    Event::create([
        'name' => 'IDIG Annual Event',
        'year' => 2026,
        'theme_title' => 'Sustainable Healthcare Technology',
        'is_active' => false,
    ]);

    $this->actingAs(searchAdminUser())
        ->get('/admin/search?q=Sustainable')
        ->assertOk()
        ->assertSee('Sustainable Healthcare Technology');
});

it('finds an order by its INV-XXXX display id', function () {
    $admin = searchAdminUser();
    $booking = ServiceBooking::factory()->create(['user_id' => $admin->id]);

    $padded = str_pad($booking->id, 4, '0', STR_PAD_LEFT);

    $this->actingAs($admin)
        ->get("/admin/search?q=INV-{$padded}")
        ->assertOk()
        ->assertSee("INV-{$padded}");
});

it('returns no results for a term that matches nothing', function () {
    $this->actingAs(searchAdminUser())
        ->get('/admin/search?q=zzzzzxxx999notfound')
        ->assertOk()
        ->assertSee('No results found');
});
