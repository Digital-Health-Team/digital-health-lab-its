<?php

use App\Actions\CMS\LabTeam\CreateLabTeamPersonAction;
use App\DTOs\CMS\LabTeamPersonData;
use App\Models\LabTeamSection;
use Inertia\Testing\AssertableInertia as Assert;

function makePersonData(array $overrides = []): LabTeamPersonData
{
    return new LabTeamPersonData(
        section_id: $overrides['section_id'],
        is_leader: false,
        name_full: $overrides['name_full'] ?? 'Test Person',
        display_line_1: 'Test',
        display_line_2: 'Person',
        role_id: 'Anggota',
        role_en: 'Member',
        bio: $overrides['bio'] ?? 'A short bio.',
        email: $overrides['email'] ?? null,
        linkedin_url: null,
        instagram_url: null,
        expertise: $overrides['expertise'] ?? ['Signal Processing'],
        // array_key_exists (not ??) so passing an explicit `null` override is
        // distinguishable from "not provided" — `??` treats both the same.
        completed_projects: array_key_exists('completed_projects', $overrides)
            ? $overrides['completed_projects']
            : [['title' => 'Test Project', 'description' => 'A project description.', 'url' => null]],
        education: array_key_exists('education', $overrides)
            ? $overrides['education']
            : ['S.T. Teknik Biomedis, Institut Teknologi Sepuluh Nopember'],
        initials: 'TP',
        photo_url: null,
        sort_order: $overrides['sort_order'] ?? 1,
        is_active: $overrides['is_active'] ?? true,
    );
}

test('active person renders the profile with mapped fields', function () {
    $section = LabTeamSection::create(['label_id' => 'TIM A', 'label_en' => 'Team A', 'sort_order' => 1, 'is_active' => true]);
    $person = app(CreateLabTeamPersonAction::class)->execute(makePersonData([
        'section_id' => $section->id,
        'name_full' => 'Ada Lovelace',
        'expertise' => ['Analytical Engines', 'Punch Cards'],
        'completed_projects' => [['title' => 'Analytical Engine Notes', 'description' => 'Published the first published algorithm.', 'url' => 'https://example.com/notes']],
        'education' => ['Self-taught mathematics, London'],
    ]));

    $this->get("/team/{$person->slug}")
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Features/Team/Pages/TeamMemberShow')
            ->where('member.name', 'Ada Lovelace')
            ->where('member.expertise', ['Analytical Engines', 'Punch Cards'])
            ->where('member.completedProjects', [['title' => 'Analytical Engine Notes', 'description' => 'Published the first published algorithm.', 'url' => 'https://example.com/notes']])
            ->where('member.education', ['Self-taught mathematics, London'])
            ->has('teammates')
        );
});

test('completed projects and education default to empty arrays when null', function () {
    $section = LabTeamSection::create(['label_id' => 'TIM A', 'label_en' => 'Team A', 'sort_order' => 1, 'is_active' => true]);
    $person = app(CreateLabTeamPersonAction::class)->execute(makePersonData([
        'section_id' => $section->id,
        'completed_projects' => null,
        'education' => null,
    ]));

    $this->get("/team/{$person->slug}")
        ->assertInertia(fn (Assert $page) => $page
            ->where('member.completedProjects', [])
            ->where('member.education', [])
        );
});

test('inactive person 404s', function () {
    $section = LabTeamSection::create(['label_id' => 'TIM A', 'label_en' => 'Team A', 'sort_order' => 1, 'is_active' => true]);
    $person = app(CreateLabTeamPersonAction::class)->execute(makePersonData([
        'section_id' => $section->id,
        'is_active' => false,
    ]));

    $this->get("/team/{$person->slug}")->assertNotFound();
});

test('unknown slug 404s', function () {
    $this->get('/team/does-not-exist')->assertNotFound();
});

test('teammates exclude self and inactive same-section people', function () {
    $section = LabTeamSection::create(['label_id' => 'TIM A', 'label_en' => 'Team A', 'sort_order' => 1, 'is_active' => true]);
    $otherSection = LabTeamSection::create(['label_id' => 'TIM B', 'label_en' => 'Team B', 'sort_order' => 2, 'is_active' => true]);

    $person = app(CreateLabTeamPersonAction::class)->execute(makePersonData(['section_id' => $section->id, 'name_full' => 'Person One', 'sort_order' => 1]));
    $activeTeammate = app(CreateLabTeamPersonAction::class)->execute(makePersonData(['section_id' => $section->id, 'name_full' => 'Person Two', 'sort_order' => 2]));
    app(CreateLabTeamPersonAction::class)->execute(makePersonData(['section_id' => $section->id, 'name_full' => 'Inactive Person', 'sort_order' => 3, 'is_active' => false]));
    app(CreateLabTeamPersonAction::class)->execute(makePersonData(['section_id' => $otherSection->id, 'name_full' => 'Different Section Person', 'sort_order' => 1]));

    $this->get("/team/{$person->slug}")
        ->assertInertia(fn (Assert $page) => $page
            ->has('teammates', 1)
            ->where('teammates.0.name', $activeTeammate->name_full)
        );
});

test('duplicate names get distinct slugs and unchanged names keep their slug on update', function () {
    $section = LabTeamSection::create(['label_id' => 'TIM A', 'label_en' => 'Team A', 'sort_order' => 1, 'is_active' => true]);

    $first = app(CreateLabTeamPersonAction::class)->execute(makePersonData(['section_id' => $section->id, 'name_full' => 'Same Name', 'sort_order' => 1]));
    $second = app(CreateLabTeamPersonAction::class)->execute(makePersonData(['section_id' => $section->id, 'name_full' => 'Same Name', 'sort_order' => 2]));

    expect($first->slug)->toBe('same-name');
    expect($second->slug)->toBe('same-name-2');

    $originalSlug = $first->slug;
    app(App\Actions\CMS\LabTeam\UpdateLabTeamPersonAction::class)->execute($first, makePersonData([
        'section_id' => $section->id,
        'name_full' => 'Same Name', // unchanged
        'sort_order' => 1,
    ]));
    expect($first->fresh()->slug)->toBe($originalSlug);

    app(App\Actions\CMS\LabTeam\UpdateLabTeamPersonAction::class)->execute($first, makePersonData([
        'section_id' => $section->id,
        'name_full' => 'Renamed Person',
        'sort_order' => 1,
    ]));
    expect($first->fresh()->slug)->toBe('renamed-person');
});
