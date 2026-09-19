<?php

use App\Actions\Chatbot\BuildKnowledgeIndexAction;
use App\Actions\Publication\UpdatePublicationStatusAction;
use App\Models\KnowledgeChunk;
use App\Models\Publication;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Inertia\Testing\AssertableInertia as Assert;
use Symfony\Component\HttpKernel\Exception\HttpException;

beforeEach(fn () => $this->withoutVite());

// Pest loads every Feature file into one process and top-level functions are global —
// these names are prefixed to avoid colliding with PublicationsTest / GlobalSearchTest.
function visibilityPublication(string $status, array $overrides = []): Publication
{
    return Publication::create(array_merge([
        'title' => 'Unreviewed Cardiac Study',
        'slug' => 'unreviewed-'.uniqid(),
        'author' => 'S. Student',
        'category' => 'Papers',
        'abstract' => 'An abstract that has not been vetted by anyone.',
        'status' => $status,
        'published_at' => now()->subMonth(),
    ], $overrides));
}

function visibilityAdmin(): User
{
    $role = Role::firstOrCreate(['name' => 'admin_lab']);

    return User::factory()->create(['role_id' => $role->id]);
}

// ── /research list ────────────────────────────────────────
it('keeps unapproved publications off the research page', function (string $status) {
    visibilityPublication($status);

    $this->get('/research')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page->has('publications', 0));
})->with(['pending', 'rejected']);

it('shows approved publications on the research page', function () {
    visibilityPublication('approved');

    $this->get('/research')
        ->assertInertia(fn (Assert $page) => $page->has('publications', 1));
});

// ── /publications/{slug} detail ───────────────────────────
it('404s the detail page for an unapproved publication', function (string $status) {
    $pub = visibilityPublication($status);

    $this->get("/publications/{$pub->slug}")->assertNotFound();
})->with(['pending', 'rejected']);

it('serves the detail page for an approved publication', function () {
    $pub = visibilityPublication('approved');

    $this->get("/publications/{$pub->slug}")->assertOk();
});

it('does not increment view_count on a publication it refuses to show', function () {
    $pub = visibilityPublication('pending');

    $this->get("/publications/{$pub->slug}")->assertNotFound();

    expect($pub->fresh()->view_count)->toBe(0);
});

it('keeps unapproved publications out of the related list', function () {
    $approved = visibilityPublication('approved', ['title' => 'Approved Paper']);
    visibilityPublication('pending', ['title' => 'Pending Paper']);

    $this->get("/publications/{$approved->slug}")
        ->assertInertia(fn (Assert $page) => $page->has('publication.related', 0));
});

// ── /search ───────────────────────────────────────────────
it('keeps unapproved publications out of global search', function () {
    visibilityPublication('pending', ['title' => 'Cardiac Rhythm Anomalies']);

    $response = $this->getJson('/search?q=Cardiac');

    expect(collect($response->json('results'))->where('type', 'publication'))->toBeEmpty();
});

it('returns approved publications from global search', function () {
    visibilityPublication('approved', ['title' => 'Cardiac Rhythm Anomalies']);

    $response = $this->getJson('/search?q=Cardiac');

    expect(collect($response->json('results'))->where('type', 'publication'))->toHaveCount(1);
});

// ── /dashboard widgets (route has no auth middleware — fully public) ──
it('keeps unapproved publications off the public dashboard', function () {
    visibilityPublication('pending', ['view_count' => 9999, 'is_featured' => true]);

    $this->get('/dashboard')
        ->assertInertia(fn (Assert $page) => $page
            ->has('trendingPublications', 0)
            ->has('featuredPublications', 0)
        );
});

// ── Chatbot RAG corpus ────────────────────────────────────
it('does not index an unapproved publication into the chatbot knowledge base', function () {
    $knowledgePath = storage_path('framework/testing/knowledge-'.uniqid());
    File::ensureDirectoryExists("{$knowledgePath}/id");
    File::ensureDirectoryExists("{$knowledgePath}/en");

    config([
        'gemini.api_key' => 'test-key',
        'gemini.base_url' => 'https://example.test/v1beta',
        'gemini.embedding_model' => 'test-embedding-model',
        'gemini.embedding_dimensions' => 4,
        'gemini.knowledge_path' => $knowledgePath,
        'gemini.ingest_delay_ms' => 0,
    ]);
    Http::fake(['*:embedContent' => Http::response(['embedding' => ['values' => [1.0, 0.0, 0.0, 0.0]]])]);

    $pending = visibilityPublication('pending');
    $approved = visibilityPublication('approved', ['title' => 'Vetted Study']);

    app(BuildKnowledgeIndexAction::class)->execute(['publication']);

    $keys = KnowledgeChunk::query()->pluck('source_key')->unique();

    expect($keys->filter(fn ($k) => str_starts_with((string) $k, "db:publication:{$pending->id}:")))->toBeEmpty()
        ->and($keys->filter(fn ($k) => str_starts_with((string) $k, "db:publication:{$approved->id}:")))->not->toBeEmpty();

    File::deleteDirectory($knowledgePath);
});

// ── Status action ─────────────────────────────────────────
it('records the validating admin when a publication is approved', function () {
    $admin = visibilityAdmin();
    $pub = visibilityPublication('pending');

    $this->actingAs($admin);
    app(UpdatePublicationStatusAction::class)->execute($pub, 'approved');

    expect($pub->fresh()->status)->toBe('approved')
        ->and($pub->fresh()->validated_by)->toBe($admin->id);
});

it('rejects an unknown status', function () {
    $this->actingAs(visibilityAdmin());
    $pub = visibilityPublication('pending');

    expect(fn () => app(UpdatePublicationStatusAction::class)->execute($pub, 'published'))
        ->toThrow(HttpException::class);

    expect($pub->fresh()->status)->toBe('pending');
});
