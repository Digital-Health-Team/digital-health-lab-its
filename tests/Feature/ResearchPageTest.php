<?php

use App\Models\Publication;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(fn () => $this->withoutVite());

it('renders the merged research page with its publications', function () {
    Publication::create([
        'title' => 'Alpha Paper',
        'slug' => 'alpha-paper',
        'author' => 'A. Author',
        'category' => 'Journals',
        'status' => 'approved',
        'published_at' => now()->subMonth(),
    ]);

    $this->get(route('research'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Features/Research/Pages/ResearchPage')
            ->has('publications', 1)
            ->where('publications.0.title', 'Alpha Paper')
        );
});

it('no longer serves the old list urls', function (string $url) {
    $this->get($url)->assertNotFound();
})->with(['/projects', '/publications']);

it('still serves both detail routes', function () {
    Publication::create([
        'title' => 'Detail Paper',
        'slug' => 'detail-paper',
        'author' => 'A. Author',
        'category' => 'Papers',
        'status' => 'approved',
        'published_at' => now()->subMonth(),
    ]);

    $this->get('/publications/detail-paper')->assertOk();
    $this->get('/projects/craniosynostosis-ct-detection')->assertOk();
});
