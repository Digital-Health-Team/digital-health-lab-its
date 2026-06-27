<?php

use App\Models\OpenSourceProject;
use App\Models\Role;
use App\Models\Service;
use App\Models\ServiceBooking;
use App\Models\Training;
use App\Models\TrainingRegistration;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(fn () => $this->withoutVite());

function portfolioUser(): User
{
    $role = Role::firstOrCreate(['name' => 'user_publik']);

    return User::factory()->create(['role_id' => $role->id]);
}

function portfolioProject(User $user, array $overrides = []): OpenSourceProject
{
    return OpenSourceProject::create(array_merge([
        'user_id' => $user->id,
        'title' => 'Test Project',
        'slug' => 'test-project-'.uniqid(),
        'category' => '3d_model',
        'listing_type' => 'downloadable',
        'status' => 'pending',
    ], $overrides));
}

function portfolioTraining(): Training
{
    return Training::factory()->create([
        'title' => 'Test Training',
        'slug' => 'test-training-'.uniqid(),
        'is_active' => true,
        'is_paid' => false,
        'price' => 0,
        'date' => now()->addMonth(),
        'location' => 'Online',
    ]);
}

// ── Guest access ──────────────────────────────────────────
test('guests are redirected to login from the portfolio page', function () {
    $this->get('/portfolio')->assertRedirect('/login');
});

// ── Portfolio index ───────────────────────────────────────
test('authenticated user can view the portfolio page', function () {
    $user = portfolioUser();

    $this->actingAs($user)
        ->get('/portfolio')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Features/Portfolio/Pages/PortfolioPage')
            ->has('orders')
            ->has('projects')
            ->has('enrollments')
        );
});

test('portfolio page shows only the authenticated user data', function () {
    $user = portfolioUser();
    $otherUser = portfolioUser();

    portfolioProject($user, ['title' => 'My Project']);
    portfolioProject($otherUser, ['title' => 'Other Project']);

    $this->actingAs($user)
        ->get('/portfolio')
        ->assertInertia(fn (Assert $page) => $page
            ->has('projects', 1)
            ->where('projects.0.title', 'My Project')
        );
});

test('portfolio page shows all three data sections', function () {
    $user = portfolioUser();

    $service = Service::create([
        'name' => 'FDM Print',
        'service_type' => 'printing',
        'description' => 'FDM',
        'base_price' => 50000,
        'whatsapp_number' => '6281234567890',
    ]);
    $transaction = Transaction::create([
        'user_id' => $user->id,
        'total_amount' => 0,
        'payment_status' => 'pending',
    ]);
    ServiceBooking::create([
        'user_id' => $user->id,
        'service_id' => $service->id,
        'transaction_id' => $transaction->id,
        'current_status' => 'pending',
        'brief_description' => 'Test brief',
    ]);

    portfolioProject($user);

    $training = portfolioTraining();
    TrainingRegistration::create([
        'user_id' => $user->id,
        'training_id' => $training->id,
        'full_name' => $user->name,
        'email' => $user->email,
        'phone_number' => '08123456789',
    ]);

    $this->actingAs($user)
        ->get('/portfolio')
        ->assertInertia(fn (Assert $page) => $page
            ->has('orders', 1)
            ->has('projects', 1)
            ->has('enrollments', 1)
        );
});

// ── Project create ────────────────────────────────────────
test('user can submit a new project which starts as pending', function () {
    Storage::fake('public');
    $user = portfolioUser();

    $this->actingAs($user)
        ->post('/my/projects', [
            'title' => 'Cranio Scanner',
            'category' => 'medical_device',
            'listing_type' => 'downloadable',
            'caption' => 'A scanner for craniosynostosis.',
            'license' => 'MIT',
        ])
        ->assertRedirect('/portfolio');

    $project = OpenSourceProject::first();
    expect($project->user_id)->toBe($user->id)
        ->and($project->status)->toBe('pending')
        ->and($project->title)->toBe('Cranio Scanner');
});

test('user can upload files when creating a project', function () {
    Storage::fake('public');
    $user = portfolioUser();

    $this->actingAs($user)
        ->post('/my/projects', [
            'title' => 'IoT Health Monitor',
            'category' => 'iot_system',
            'files' => [UploadedFile::fake()->create('model.pdf', 500, 'application/pdf')],
        ])
        ->assertRedirect('/portfolio');

    expect(OpenSourceProject::count())->toBe(1);
    expect(OpenSourceProject::first()->attachments()->count())->toBe(1);
});

test('project create validates required fields', function () {
    $user = portfolioUser();

    $this->actingAs($user)
        ->post('/my/projects', [])
        ->assertSessionHasErrors(['title', 'category']);
});

// ── Project update ────────────────────────────────────────
test('user can update their own pending project', function () {
    Storage::fake('public');
    $user = portfolioUser();
    $project = portfolioProject($user, ['status' => 'pending']);

    $this->actingAs($user)
        ->post("/my/projects/{$project->id}", [
            'title' => 'Updated Title',
            'category' => 'software',
        ])
        ->assertRedirect('/portfolio');

    expect($project->fresh()->title)->toBe('Updated Title');
});

test('updating a rejected project resets its status to pending', function () {
    Storage::fake('public');
    $user = portfolioUser();
    $project = portfolioProject($user, ['status' => 'rejected']);

    $this->actingAs($user)
        ->post("/my/projects/{$project->id}", [
            'title' => 'Fixed Title',
            'category' => '3d_model',
        ])
        ->assertRedirect('/portfolio');

    expect($project->fresh()->status)->toBe('pending');
});

test('user cannot update an approved project', function () {
    $user = portfolioUser();
    $project = portfolioProject($user, ['status' => 'approved']);

    $this->actingAs($user)
        ->post("/my/projects/{$project->id}", [
            'title' => 'New Title',
            'category' => '3d_model',
        ])
        ->assertForbidden();
});

test('user cannot update another user project', function () {
    $user = portfolioUser();
    $otherUser = portfolioUser();
    $project = portfolioProject($otherUser);

    $this->actingAs($user)
        ->post("/my/projects/{$project->id}", [
            'title' => 'Hijacked',
            'category' => '3d_model',
        ])
        ->assertForbidden();
});

// ── Project delete ────────────────────────────────────────
test('user can delete their own pending project', function () {
    Storage::fake('public');
    $user = portfolioUser();
    $project = portfolioProject($user, ['status' => 'pending']);

    $this->actingAs($user)
        ->delete("/my/projects/{$project->id}")
        ->assertRedirect('/portfolio');

    expect(OpenSourceProject::find($project->id))->toBeNull();
});

test('user can delete their own rejected project', function () {
    Storage::fake('public');
    $user = portfolioUser();
    $project = portfolioProject($user, ['status' => 'rejected']);

    $this->actingAs($user)
        ->delete("/my/projects/{$project->id}")
        ->assertRedirect('/portfolio');

    expect(OpenSourceProject::find($project->id))->toBeNull();
});

test('user cannot delete an approved project', function () {
    $user = portfolioUser();
    $project = portfolioProject($user, ['status' => 'approved']);

    $this->actingAs($user)
        ->delete("/my/projects/{$project->id}")
        ->assertForbidden();
});

test('user cannot delete another user project', function () {
    $user = portfolioUser();
    $otherUser = portfolioUser();
    $project = portfolioProject($otherUser);

    $this->actingAs($user)
        ->delete("/my/projects/{$project->id}")
        ->assertForbidden();
});
