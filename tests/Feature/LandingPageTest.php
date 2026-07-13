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
