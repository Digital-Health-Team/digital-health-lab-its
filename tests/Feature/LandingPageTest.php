<?php

use App\Models\LabTeamPerson;
use App\Models\LabTeamSection;
use App\Models\PageSection;
use App\Models\User;
use Database\Seeders\LabTeamSectionSeeder;
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

    $content = PageSection::where('page_name', 'landing')
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

    $content = PageSection::where('page_name', 'landing')
        ->where('section_key', 'articles_entry_1')
        ->value('content');

    $entry = json_decode($content, true);

    expect($entry)
        ->toHaveKeys(['title', 'category', 'date', 'excerpt', 'href'])
        ->and($entry)->not->toHaveKeys(['author', 'year']);
});

test('all seven capability blobs seed with the expected fields and overlay in english', function () {
    User::factory()->create();
    $this->seed(LandingContentSeeder::class);

    foreach (range(1, 7) as $n) {
        $blob = json_decode(
            PageSection::where('page_name', 'landing')
                ->where('section_key', "about_capability_{$n}")
                ->value('content'),
            true
        );

        expect($blob)->toHaveKeys(['tag', 'title', 'description', 'image_url', 'accent']);
    }

    session(['locale' => 'en']);

    $this->get('/')->assertInertia(fn (Assert $page) => $page
        ->has('landingContent.about_capability_7')
        // Suffixed keys are stripped, never leaked to the client.
        ->missing('landingContent.about_capability_7_en')
    );
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

    PageSection::where('page_name', 'landing')
        ->where('section_key', 'services_heading_en')
        ->delete();

    session(['locale' => 'en']);

    $this->get('/')->assertInertia(fn (Assert $page) => $page
        ->where('landingContent.services_heading', 'Tiga Pilar Inovasi')
    );
});

test('the org section ships one head section and one flat research roster', function () {
    $this->seed(LabTeamSectionSeeder::class);

    $this->get('/')->assertInertia(fn (Assert $page) => $page
        // Two sections, not three — HTECH and RCMED are merged.
        ->has('teamSections', 2)
        ->where('teamSections.1.label_en', 'All IDIG Research Members')
        ->has('teamSections.1.members', 16)
        // Flat: nobody in the roster is a leader.
        ->where('teamSections.1.leader', null)
        // The row shows the short name, with the full one along for the title tooltip.
        ->where('teamSections.1.members.0.name', 'Iqbal')
        ->where('teamSections.1.members.0.fullName', 'Muhammad Iqbal Putra Subekti.')
        ->where('teamSections.1.members.0.initials', 'IQB')
        ->where('teamSections.1.members.0.units', ['Manekin', 'Rehab'])
        ->where('teamSections.1.members.0.departments', ['CAD', 'CAM', 'Electronics'])
        // Leads keep their role; everyone on the shared role has it suppressed.
        ->where('teamSections.1.members.0.role', 'Ketua Tim HTech')
        ->where('teamSections.1.members.2.role', null)
    );
});

test('a role held by a strict majority is suppressed, an outlier role is not', function () {
    $section = LabTeamSection::create([
        'label_id' => 'TIM UJI', 'label_en' => 'Test Team', 'sort_order' => 1, 'is_active' => true,
    ]);

    foreach ([['Umum', 'A'], ['Umum', 'B'], ['Khusus', 'C']] as $i => [$role, $name]) {
        LabTeamPerson::create([
            'section_id' => $section->id, 'is_leader' => false, 'name_full' => $name,
            'slug' => strtolower($name), 'display_line_1' => $name, 'display_line_2' => $name,
            'role_id' => $role, 'role_en' => $role, 'initials' => $name,
            'sort_order' => $i + 1, 'is_active' => true,
        ]);
    }

    $this->get('/')->assertInertia(fn (Assert $page) => $page
        // 'Umum' is 2 of 3 — a strict majority, so it is the section default.
        ->where('teamSections.0.members.0.role', null)
        ->where('teamSections.0.members.1.role', null)
        ->where('teamSections.0.members.2.role', 'Khusus')
    );
});

test('the roster data file matches the org chart taxonomy', function () {
    $roster = require database_path('data/idig_roster.php');
    $codes = array_column($roster['members'], 'code');

    expect($roster['members'])->toHaveCount(16)
        ->and(array_unique($codes))->toHaveCount(16)
        ->and(array_unique(array_map('strlen', $codes)))->toBe([3]);

    // A typo in a unit or department fails here rather than shipping a stray tag that
    // silently belongs to no part of the org chart.
    foreach ($roster['members'] as $member) {
        expect($roster['units'])->toContain(...$member['units']);
        expect($roster['departments'])->toContain(...$member['departments']);
    }
});

test('a roster member with no expertise falls back to their role', function () {
    $section = LabTeamSection::create([
        'label_id' => 'TIM UJI',
        'label_en' => 'Test Team',
        'sort_order' => 1,
        'is_active' => true,
    ]);

    LabTeamPerson::create([
        'section_id' => $section->id,
        'is_leader' => false,
        'name_full' => 'Tanpa Keahlian',
        'slug' => 'tanpa-keahlian',
        'display_line_1' => 'Tanpa',
        'display_line_2' => 'Keahlian',
        'role_id' => 'Anggota Riset IDIG',
        'role_en' => 'IDIG Research Member',
        'expertise' => null,
        'initials' => 'TK',
        'sort_order' => 1,
        'is_active' => true,
    ]);

    $this->get('/')->assertInertia(fn (Assert $page) => $page
        ->where('teamSections.0.members.0.desc', 'Anggota Riset IDIG')
    );
});
