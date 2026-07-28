<?php

use App\Models\User;
use Database\Seeders\LandingContentSeeder;
use Inertia\Testing\AssertableInertia as Assert;

test('landing page renders with seeded collaboration content', function () {
    // Seeder stamps updated_by = 1, so a user must exist first.
    User::factory()->create();

    $this->seed(LandingContentSeeder::class);

    $this->get('/')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Features/Landing/Pages/LandingPage')
            ->has('landingContent.collaboration_heading')
            ->has('landingContent.collaboration_chapter_1')
            ->has('landingContent.cta_heading')
            ->has('landingContent.cta_primary_label')
            ->has('landingContent.articles_heading')
            ->has('landingContent.articles_entry_1')
        );
});

test('collaboration chapter blobs are valid json with the expected fields', function () {
    User::factory()->create();

    $this->seed(LandingContentSeeder::class);

    $content = \App\Models\PageSection::where('page_name', 'landing')
        ->where('section_key', 'collaboration_chapter_1')
        ->value('content');

    $chapter = json_decode($content, true);

    expect($chapter)
        ->toHaveKeys(['name', 'name_line_1', 'name_line_2', 'type', 'period', 'description', 'images'])
        ->and($chapter['images'])->toBeArray()->not->toBeEmpty();
});

test('article blobs are valid json with lab-activity fields, not publication fields', function () {
    User::factory()->create();

    $this->seed(LandingContentSeeder::class);

    $content = \App\Models\PageSection::where('page_name', 'landing')
        ->where('section_key', 'articles_entry_1')
        ->value('content');

    $entry = json_decode($content, true);

    expect($entry)
        ->toHaveKeys(['title', 'category', 'date', 'excerpt', 'href'])
        ->and($entry)->not->toHaveKeys(['author', 'year']);
});

test('landing content resolves indonesian from base rows', function () {
    User::factory()->create();
    $this->seed(LandingContentSeeder::class);

    session(['locale' => 'id']);

    $this->get('/')->assertInertia(fn (Assert $page) => $page
        ->where('landingContent.services_heading', 'Tiga Pilar Inovasi')
    );
});

test('landing content prefers _en rows when the locale is english', function () {
    User::factory()->create();
    $this->seed(LandingContentSeeder::class);

    session(['locale' => 'en']);

    $this->get('/')->assertInertia(fn (Assert $page) => $page
        ->where('landingContent.services_heading', 'Three Pillars of Innovation')
        // Suffixed keys are stripped, never leaked to the client.
        ->missing('landingContent.services_heading_en')
    );
});

test('locale-independent rows survive the english overlay', function () {
    User::factory()->create();
    $this->seed(LandingContentSeeder::class);

    session(['locale' => 'en']);

    // hero_bg_image_url has no _en row; it must come through from the base row
    // rather than disappearing and reverting to the bundled default.
    $this->get('/')->assertInertia(fn (Assert $page) => $page
        ->where('landingContent.hero_bg_image_url', '/assets/images/hero_4.jpg')
    );
});

test('a text key with no _en row falls back to the indonesian base row', function () {
    User::factory()->create();
    $this->seed(LandingContentSeeder::class);

    \App\Models\PageSection::where('page_name', 'landing')
        ->where('section_key', 'services_heading_en')
        ->delete();

    session(['locale' => 'en']);

    $this->get('/')->assertInertia(fn (Assert $page) => $page
        ->where('landingContent.services_heading', 'Tiga Pilar Inovasi')
    );
});
