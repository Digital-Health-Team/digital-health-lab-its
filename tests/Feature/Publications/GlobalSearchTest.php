<?php

use App\Models\Publication;
use App\Models\Role;
use App\Models\User;

beforeEach(fn () => $this->withoutVite());

function searchUser(): User
{
    $role = Role::firstOrCreate(['name' => 'user_publik']);

    return User::factory()->create(['role_id' => $role->id]);
}

it('returns empty results for a query shorter than 2 characters', function () {
    $user = searchUser();

    $this->actingAs($user)
        ->getJson('/search?q=x')
        ->assertOk()
        ->assertJsonFragment(['results' => []]);
});

it('returns empty results for a blank query', function () {
    $user = searchUser();

    $this->actingAs($user)
        ->getJson('/search?q=')
        ->assertOk()
        ->assertJsonFragment(['results' => []]);
});

it('finds publications by title', function () {
    $user = searchUser();

    Publication::factory()->create([
        'title' => 'Low-Cost 3D-Printed Prosthetic Arm',
        'slug' => 'low-cost-prosthetic-arm',
        'author' => 'Dr. Ahmad',
        'category' => 'Journals',
        'is_featured' => false,
        'is_free_access' => true,
        'published_at' => now(),
    ]);

    $this->actingAs($user)
        ->getJson('/search?q=prosthetic')
        ->assertOk()
        ->assertJsonPath('results.0.type', 'publication')
        ->assertJsonPath('results.0.title', 'Low-Cost 3D-Printed Prosthetic Arm');
});

it('finds publications by author', function () {
    $user = searchUser();

    Publication::factory()->create([
        'title' => 'IMU-Based Gait Analysis',
        'slug' => 'imu-gait-analysis',
        'author' => 'Dr. Rizki Amalia',
        'category' => 'Research',
        'published_at' => now(),
    ]);

    $this->actingAs($user)
        ->getJson('/search?q=Rizki')
        ->assertOk()
        ->assertJsonPath('results.0.type', 'publication');
});

it('is publicly accessible without authentication', function () {
    $this->getJson('/search?q=prosthetic')
        ->assertOk()
        ->assertJsonStructure(['query', 'results']);
});
