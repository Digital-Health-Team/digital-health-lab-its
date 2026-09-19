<?php

use App\Http\Controllers\User\PublishController;
use App\Models\OpenSourceProject;
use App\Models\Publication;
use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(fn () => $this->withoutVite());

function publishUser(): User
{
    $role = Role::firstOrCreate(['name' => 'mahasiswa']);

    return User::factory()->create(['role_id' => $role->id]);
}

function publishProject(User $user, array $overrides = []): OpenSourceProject
{
    return OpenSourceProject::create(array_merge([
        'user_id' => $user->id,
        'title' => 'Prosthetic Hand',
        'slug' => 'prosthetic-hand-'.uniqid(),
        'category' => '3d_model',
        'listing_type' => 'downloadable',
        'status' => 'pending',
    ], $overrides));
}

function publishPublication(User $user, array $overrides = []): Publication
{
    return Publication::create(array_merge([
        'user_id' => $user->id,
        'title' => 'Diagnostics Framework',
        'slug' => 'diagnostics-framework-'.uniqid(),
        'author' => 'S. Student',
        'category' => 'Papers',
        'status' => 'pending',
        'published_at' => now()->subWeek(),
    ], $overrides));
}

// ── Access ────────────────────────────────────────────────
it('redirects a guest to login', function (string $url) {
    $this->get($url)->assertRedirect('/login');
})->with(['/publish', '/publish/create']);

// ── The merged list ───────────────────────────────────────
it('lists both kinds of submission with their status', function () {
    $user = publishUser();
    publishProject($user, ['title' => 'My Project', 'status' => 'rejected']);
    publishPublication($user, ['title' => 'My Paper']);

    $this->actingAs($user)
        ->get('/publish')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Features/Publish/Pages/PublishPage')
            ->has('items', 2)
        );
});

it('exposes the fields the card needs', function () {
    $user = publishUser();
    publishPublication($user, ['title' => 'Solo Paper']);

    $this->actingAs($user)
        ->get('/publish')
        ->assertInertia(fn (Assert $page) => $page
            ->has('items.0', fn (Assert $i) => $i
                ->where('kind', 'publication')
                ->where('category', 'Papers')
                ->where('status', 'pending')
                ->where('title', 'Solo Paper')
                // Pending work has no public page to link to.
                ->where('detailHref', null)
                ->where('canEdit', true)
                ->where('canDelete', true)
                ->has('coverUrl')
                ->has('date')
                ->has('editHref')
                ->has('deleteUrl')
                ->etc()
            )
        );
});

it('links an approved submission to its public page', function () {
    $user = publishUser();
    $pub = publishPublication($user, ['status' => 'approved']);

    $this->actingAs($user)
        ->get('/publish')
        ->assertInertia(fn (Assert $page) => $page
            ->where('items.0.detailHref', route('publications.show', $pub->slug))
            ->where('items.0.canEdit', false)
            // Offered on every card — on approved work it files a removal request.
            ->where('items.0.canDelete', true)
        );
});

it('never shows another user submissions', function () {
    $user = publishUser();
    $other = publishUser();
    publishProject($other, ['title' => 'Not Mine']);
    publishPublication($other, ['title' => 'Also Not Mine']);

    $this->actingAs($user)
        ->get('/publish')
        ->assertInertia(fn (Assert $page) => $page->has('items', 0));
});

// ── Create ────────────────────────────────────────────────
it('renders the create page with no kind chosen yet', function () {
    $this->actingAs(publishUser())
        ->get('/publish/create')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Features/Publish/Pages/PublishFormPage')
            ->where('kind', null)
            ->where('item', null)
        );
});

it('submits a publication as pending and owned by the user', function () {
    Storage::fake('public');
    $user = publishUser();

    $this->actingAs($user)
        ->post('/publish', [
            'kind' => 'publication',
            'title' => 'AI-Driven Diagnostics',
            'author' => 'S. Student',
            'category' => 'Papers',
            'abstract' => 'A summary.',
        ])
        ->assertRedirect('/publish');

    expect(Publication::sole())
        ->status->toBe('pending')
        ->user_id->toBe($user->id)
        ->validated_by->toBeNull()
        ->title->toBe('AI-Driven Diagnostics');
});

it('validates the publication fields', function () {
    $this->actingAs(publishUser())
        ->post('/publish', ['kind' => 'publication'])
        ->assertSessionHasErrors(['title', 'author', 'category']);
});

it('rejects an unknown kind', function () {
    $this->actingAs(publishUser())
        ->post('/publish', ['kind' => 'thesis', 'title' => 'X'])
        ->assertSessionHasErrors('kind');
});

it('rejects a publication category outside the whitelist', function () {
    $this->actingAs(publishUser())
        ->post('/publish', [
            'kind' => 'publication',
            'title' => 'X',
            'author' => 'Y',
            'category' => 'Research',
        ])
        ->assertSessionHasErrors('category');
});

// ── Edit ──────────────────────────────────────────────────
it('lets the owner open the edit form', function () {
    $user = publishUser();
    $pub = publishPublication($user);

    $this->actingAs($user)
        ->get("/publish/publication/{$pub->id}/edit")
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('kind', 'publication')
            ->where('item.id', $pub->id)
        );
});

it('forbids editing work that is not yours', function () {
    $user = publishUser();
    $pub = publishPublication(publishUser());

    $this->actingAs($user)->get("/publish/publication/{$pub->id}/edit")->assertForbidden();
});

it('freezes editing of an approved submission', function () {
    $user = publishUser();
    $pub = publishPublication($user, ['status' => 'approved']);

    $this->actingAs($user)->get("/publish/publication/{$pub->id}/edit")->assertForbidden();
});

// ── Withdrawal ────────────────────────────────────────────
it('records a removal request instead of deleting approved work', function () {
    $user = publishUser();
    $pub = publishPublication($user, ['status' => 'approved']);

    $this->actingAs($user)
        ->delete("/publish/publication/{$pub->id}")
        ->assertRedirect('/publish');

    expect(Publication::find($pub->id))->not->toBeNull()
        ->and($pub->fresh()->withdrawal_requested_at)->not->toBeNull()
        ->and($pub->fresh()->status)->toBe('approved');
});

it('keeps approved work public while its removal is pending', function () {
    $user = publishUser();
    $pub = publishPublication($user, ['status' => 'approved']);

    $this->actingAs($user)->delete("/publish/publication/{$pub->id}");

    // Only an admin takes it down; the request alone must not unpublish it.
    $this->get("/publications/{$pub->slug}")->assertOk();
});

it('surfaces the removal request on the card', function () {
    $user = publishUser();
    $pub = publishPublication($user, ['status' => 'approved']);
    $this->actingAs($user)->delete("/publish/publication/{$pub->id}");

    $this->actingAs($user)
        ->get('/publish')
        ->assertInertia(fn (Assert $page) => $page->where('items.0.withdrawalRequested', true));
});

it('still refuses to touch another user approved work', function () {
    $pub = publishPublication(publishUser(), ['status' => 'approved']);

    $this->actingAs(publishUser())->delete("/publish/publication/{$pub->id}")->assertForbidden();

    expect($pub->fresh()->withdrawal_requested_at)->toBeNull();
});

// ── Update ────────────────────────────────────────────────
it('puts a rejected publication back in the queue when edited', function () {
    Storage::fake('public');
    $user = publishUser();
    $pub = publishPublication($user, ['status' => 'rejected', 'validated_by' => publishUser()->id]);

    $this->actingAs($user)
        ->post("/publish/publication/{$pub->id}", [
            'kind' => 'publication',
            'title' => 'Revised Title',
            'author' => 'S. Student',
            'category' => 'Papers',
        ])
        ->assertRedirect('/publish');

    expect($pub->fresh())
        ->status->toBe('pending')
        ->validated_by->toBeNull()
        ->title->toBe('Revised Title');
});

it('keeps the original slug when the title changes', function () {
    Storage::fake('public');
    $user = publishUser();
    $pub = publishPublication($user, ['slug' => 'original-slug']);

    $this->actingAs($user)
        ->post("/publish/publication/{$pub->id}", [
            'kind' => 'publication',
            'title' => 'A Completely Different Title',
            'author' => 'S. Student',
            'category' => 'Papers',
        ]);

    expect($pub->fresh()->slug)->toBe('original-slug');
});

it('does not let a user edit clear the admin-only flags', function () {
    Storage::fake('public');
    $user = publishUser();
    $pub = publishPublication($user, ['is_featured' => true, 'is_free_access' => true]);

    $this->actingAs($user)
        ->post("/publish/publication/{$pub->id}", [
            'kind' => 'publication',
            'title' => 'Edited',
            'author' => 'S. Student',
            'category' => 'Papers',
        ]);

    expect($pub->fresh())
        ->is_featured->toBeTrue()
        ->is_free_access->toBeTrue();
});

// ── Delete ────────────────────────────────────────────────
it('lets the owner delete a pending publication', function () {
    Storage::fake('public');
    $user = publishUser();
    $pub = publishPublication($user);

    $this->actingAs($user)
        ->delete("/publish/publication/{$pub->id}")
        ->assertRedirect('/publish');

    expect(Publication::find($pub->id))->toBeNull();
});

it('forbids deleting work that is not yours', function () {
    $pub = publishPublication(publishUser());

    $this->actingAs(publishUser())->delete("/publish/publication/{$pub->id}")->assertForbidden();
});

// ── Cover images ──────────────────────────────────────────
it('serves a seeded public-root cover path as-is', function () {
    // Seeded attachments store "assets/…" (public root), uploads store a disk path.
    // Running the former through Storage::url() produced "/storage/assets/…" — a 404,
    // which is why real covers silently fell back to placeholder gradients.
    $user = publishUser();
    $project = publishProject($user);
    $project->attachments()->create([
        'file_url' => 'assets/images/projects/craniosynostosis_detection.png',
        'file_name' => 'cover.png',
        'file_type' => 'image/png',
        'uploaded_by' => $user->id,
        'is_primary' => true,
        'sort_order' => 0,
    ]);

    $this->actingAs($user)
        ->get('/publish')
        ->assertInertia(fn (Assert $page) => $page
            ->where('items.0.coverUrl', '/assets/images/projects/craniosynostosis_detection.png')
        );
});

it('serves an uploaded cover from the public disk', function () {
    Storage::fake('public');
    $user = publishUser();
    $project = publishProject($user);
    $project->attachments()->create([
        'file_url' => 'open_source_projects/uploaded.png',
        'file_name' => 'uploaded.png',
        'file_type' => 'image/png',
        'uploaded_by' => $user->id,
        'is_primary' => true,
        'sort_order' => 0,
    ]);

    $this->actingAs($user)
        ->get('/publish')
        ->assertInertia(fn (Assert $page) => $page
            ->where('items.0.coverUrl', '/storage/open_source_projects/uploaded.png')
        );
});

it('stores an uploaded project cover as the primary attachment', function () {
    Storage::fake('public');
    $user = publishUser();

    $this->actingAs($user)->post('/publish', [
        'kind' => 'project',
        'title' => 'Hand Scanner',
        'category' => '3d_model',
        'cover_file' => UploadedFile::fake()->image('cover.jpg'),
        'files' => [UploadedFile::fake()->create('model.stl', 100)],
    ])->assertRedirect('/publish');

    $project = OpenSourceProject::sole();
    $primary = $project->attachments()->where('is_primary', true)->sole();

    // The cover must win index 0 — otherwise the STL becomes the card image.
    expect($primary->file_name)->toBe('cover.jpg')
        ->and($project->attachments()->count())->toBe(2)
        ->and($primary->public_url)->toStartWith('/storage/');
});

it('stores an uploaded publication cover and pdf on the public disk', function () {
    Storage::fake('public');
    $user = publishUser();

    $this->actingAs($user)->post('/publish', [
        'kind' => 'publication',
        'title' => 'Gait Analysis Study',
        'author' => 'S. Student',
        'category' => 'Papers',
        'thumbnail_file' => UploadedFile::fake()->image('cover.jpg'),
        'pdf_file' => UploadedFile::fake()->create('paper.pdf', 200, 'application/pdf'),
    ])->assertRedirect('/publish');

    $pub = Publication::sole();

    expect($pub->thumbnail_path)->toStartWith('publications/thumbnails/')
        ->and($pub->pdf_path)->toStartWith('publications/pdfs/')
        ->and($pub->pdf_file_size)->not->toBeNull();

    Storage::disk('public')->assertExists($pub->thumbnail_path);
    Storage::disk('public')->assertExists($pub->pdf_path);

    // The card reads thumbnail_url. It must be root-relative: the public disk builds its
    // url from APP_URL, so an absolute one ("http://localhost/storage/…") 404s on any
    // other host or port — exactly the trap BuildKnowledgeIndexAction documents.
    expect($pub->thumbnail_url)->toBe('/storage/'.$pub->thumbnail_path)
        ->and($pub->pdf_url)->toBe('/storage/'.$pub->pdf_path);
});

it('never hands the frontend a host-bound asset url', function () {
    config(['app.url' => 'https://publikasi.its.ac.id']);
    Storage::fake('public');
    $user = publishUser();
    $pub = publishPublication($user, ['thumbnail_path' => 'publications/thumbnails/x.png']);
    $project = publishProject($user);
    $project->attachments()->create([
        'file_url' => 'open_source_projects/y.png',
        'file_name' => 'y.png',
        'file_type' => 'image/png',
        'is_primary' => true,
        'sort_order' => 0,
        'uploaded_by' => $user->id,
    ]);

    $this->actingAs($user)
        ->get('/publish')
        ->assertInertia(fn (Assert $page) => $page
            ->has('items', 2)
            ->where('items.0.coverUrl', fn (?string $url) => $url === null || str_starts_with($url, '/'))
            ->where('items.1.coverUrl', fn (?string $url) => $url === null || str_starts_with($url, '/'))
        );

    expect($pub->fresh()->thumbnail_url)->toStartWith('/storage/');
});

it('keeps the existing cover when an edit uploads no new file', function () {
    Storage::fake('public');
    $user = publishUser();
    $pub = publishPublication($user, ['thumbnail_path' => 'publications/thumbnails/keep.png']);

    $this->actingAs($user)->post("/publish/publication/{$pub->id}", [
        'kind' => 'publication',
        'title' => 'Retitled',
        'author' => 'S. Student',
        'category' => 'Papers',
    ])->assertRedirect('/publish');

    expect($pub->fresh()->thumbnail_path)->toBe('publications/thumbnails/keep.png');
});

it('replaces the cover when an edit uploads a new one', function () {
    Storage::fake('public');
    $user = publishUser();
    $pub = publishPublication($user);

    $this->actingAs($user)->post("/publish/publication/{$pub->id}", [
        'kind' => 'publication',
        'title' => 'Retitled',
        'author' => 'S. Student',
        'category' => 'Papers',
        'thumbnail_file' => UploadedFile::fake()->image('new-cover.png'),
    ])->assertRedirect('/publish');

    expect($pub->fresh()->thumbnail_path)->toStartWith('publications/thumbnails/');
    Storage::disk('public')->assertExists($pub->fresh()->thumbnail_path);
});

it('rejects a non-image cover and a non-pdf document', function () {
    Storage::fake('public');

    $this->actingAs(publishUser())->post('/publish', [
        'kind' => 'publication',
        'title' => 'X',
        'author' => 'Y',
        'category' => 'Papers',
        'thumbnail_file' => UploadedFile::fake()->create('evil.php', 10),
        'pdf_file' => UploadedFile::fake()->image('not-a-pdf.jpg'),
    ])->assertSessionHasErrors(['thumbnail_file', 'pdf_file']);

    expect(Publication::count())->toBe(0);
});

// ── Upload size limits ────────────────────────────────────
it('never advertises a limit larger than php will accept', function () {
    // PHP enforces upload_max_filesize/post_max_size in the SAPI, before Laravel runs.
    // A `max:` rule above that ceiling is a promise the server cannot keep, and the user
    // gets a raw 413 instead of a validation error.
    $phpKb = floor(UploadedFile::getMaxFilesize() / 1024);

    foreach (['cover', 'files', 'pdf'] as $key) {
        expect(PublishController::maxKb($key))->toBeLessThanOrEqual($phpKb)
            ->and(PublishController::maxKb($key))->toBeGreaterThan(0);
    }
});

it('sends the effective limits to the form page', function () {
    $this->actingAs(publishUser())
        ->get('/publish/create')
        ->assertInertia(fn (Assert $page) => $page
            ->where('limits.coverKb', PublishController::maxKb('cover'))
            ->where('limits.filesKb', PublishController::maxKb('files'))
            ->where('limits.pdfKb', PublishController::maxKb('pdf'))
        );
});

it('turns an oversized post into a redirect rather than a stack trace', function () {
    $request = Request::create('/publish', 'POST');
    $request->headers->set('referer', 'http://localhost/publish/create');

    $response = app(ExceptionHandler::class)->render($request, new PostTooLargeException);

    expect($response->getStatusCode())->toBe(302)
        ->and($response->headers->get('Location'))->toContain('/publish/create')
        ->and($response->headers->get('Location'))->toContain('uploadError=');
});

it('answers an oversized json post with 413 and a message', function () {
    $request = Request::create('/publish', 'POST');
    $request->headers->set('accept', 'application/json');

    $response = app(ExceptionHandler::class)->render($request, new PostTooLargeException);

    expect($response->getStatusCode())->toBe(413)
        ->and($response->getData(true))->toHaveKey('message');
});

// ── Route constraints ─────────────────────────────────────
it('404s an unknown kind segment', function () {
    $this->actingAs(publishUser())->get('/publish/thesis/1/edit')->assertNotFound();
});
