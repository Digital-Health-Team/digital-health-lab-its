<?php

use App\Livewire\Admin\Publication\Index as AdminPublicationIndex;
use App\Models\Publication;
use App\Models\Role;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;
use Livewire\Livewire;

beforeEach(fn () => $this->withoutVite());

function publicationUser(): User
{
    $role = Role::firstOrCreate(['name' => 'user_publik']);

    return User::factory()->create(['role_id' => $role->id]);
}

function adminLabUser(): User
{
    $role = Role::firstOrCreate(['name' => 'admin_lab']);

    return User::factory()->create(['role_id' => $role->id]);
}

function makePublication(array $overrides = []): Publication
{
    return Publication::create(array_merge([
        'title' => 'Test Publication',
        'slug' => 'test-publication-'.uniqid(),
        'author' => 'Test Author',
        'category' => 'Journals',
        'status' => 'approved',
        'published_at' => now()->subMonth(),
    ], $overrides));
}

// ── Public list page ──────────────────────────────────────
test('guest can view the research list page', function () {
    $this->get('/research')->assertOk();
});

test('research page passes publications prop to Inertia', function () {
    makePublication(['title' => 'Alpha Paper']);
    makePublication(['title' => 'Beta Paper', 'category' => 'Papers']);

    $this->get('/research')
        ->assertInertia(fn (Assert $page) => $page
            ->component('Features/Research/Pages/ResearchPage')
            ->has('publications', 2)
        );
});

test('publications list only contains seeded publications', function () {
    makePublication(['title' => 'Only Mine']);

    $this->get('/research')
        ->assertInertia(fn (Assert $page) => $page
            ->has('publications', 1)
            ->where('publications.0.title', 'Only Mine')
        );
});

test('publications list item contains expected fields', function () {
    makePublication(['title' => 'Field Test', 'author' => 'John Doe', 'category' => 'Papers']);

    $this->get('/research')
        ->assertInertia(fn (Assert $page) => $page
            ->has('publications.0', fn (Assert $p) => $p
                ->has('id')
                ->has('title')
                ->has('slug')
                ->has('author')
                ->has('category')
                ->has('thumbnailUrl')
                ->has('publishedAt')
                ->has('viewCount')
                ->has('href')
                ->etc()
            )
        );
});

// ── Publication detail page ───────────────────────────────
test('publication detail page returns 404 for unknown slug', function () {
    $this->get('/publications/slug-that-does-not-exist')->assertNotFound();
});

test('publication detail page returns correct publication prop', function () {
    $pub = makePublication(['title' => 'Detailed Paper', 'abstract' => 'Test abstract']);

    $this->get("/publications/{$pub->slug}")
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Features/Publications/Pages/PublicationDetailPage')
            ->has('publication', fn (Assert $p) => $p
                ->where('title', 'Detailed Paper')
                ->has('abstract')
                ->has('description')
                ->has('keywords')
                ->has('pdfUrl')
                ->has('related')
                ->etc()
            )
        );
});

test('viewing a publication detail page increments view_count', function () {
    $pub = makePublication(['view_count' => 5]);

    $this->get("/publications/{$pub->slug}");

    expect($pub->fresh()->view_count)->toBe(6);
});

test('related publications are from the same category', function () {
    $pub = makePublication(['title' => 'Main', 'category' => 'Journals']);
    makePublication(['title' => 'Related', 'category' => 'Journals']);
    makePublication(['title' => 'Unrelated', 'category' => 'Papers']);

    $this->get("/publications/{$pub->slug}")
        ->assertInertia(fn (Assert $page) => $page
            ->has('publication.related', 1)
            ->where('publication.related.0.title', 'Related')
        );
});

// ── Admin access ──────────────────────────────────────────
test('admin can access the admin publications page', function () {
    $admin = adminLabUser();

    $this->actingAs($admin)
        ->get('/admin/publications')
        ->assertOk();
});

test('regular user is redirected away from the admin publications page', function () {
    $user = publicationUser();

    $this->actingAs($user)
        ->get('/admin/publications')
        ->assertRedirect(route('user.dashboard'));
});

test('guest cannot access the admin publications page', function () {
    $this->get('/admin/publications')->assertRedirect('/login');
});

// ── Admin moderation ──────────────────────────────────────
test('the moderation queue lists unapproved publications', function () {
    makePublication(['title' => 'Awaiting Review', 'status' => 'pending']);

    Livewire::actingAs(adminLabUser())
        ->test(AdminPublicationIndex::class)
        ->assertSee('Awaiting Review');
});

test('admin can approve and reject a publication from the queue', function () {
    $admin = adminLabUser();
    $pub = makePublication(['status' => 'pending']);

    $component = Livewire::actingAs($admin)->test(AdminPublicationIndex::class);

    $component->call('updateStatus', $pub->id, 'approved');
    expect($pub->fresh()->status)->toBe('approved')
        ->and($pub->fresh()->validated_by)->toBe($admin->id);

    $component->call('updateStatus', $pub->id, 'rejected');
    expect($pub->fresh()->status)->toBe('rejected');
});

test('the moderation queue can filter by status', function () {
    makePublication(['title' => 'Live Paper', 'status' => 'approved']);
    makePublication(['title' => 'Queued Paper', 'status' => 'pending']);

    Livewire::actingAs(adminLabUser())
        ->test(AdminPublicationIndex::class)
        ->set('filterStatus', 'pending')
        ->assertSee('Queued Paper')
        ->assertDontSee('Live Paper');
});

test('admin-created publications are published immediately', function () {
    Livewire::actingAs(adminLabUser())
        ->test(AdminPublicationIndex::class)
        ->set('title', 'Editorial Note')
        ->set('author', 'Lab Staff')
        ->set('category', 'Journals')
        ->call('save');

    expect(Publication::where('title', 'Editorial Note')->sole())
        ->status->toBe('approved')
        ->user_id->toBeNull();
});
