<?php

use App\Models\Event;
use App\Models\Training;
use App\Models\User;

it('renders the events page for guests', function () {
    $this->get(route('events'))
        ->assertStatus(200)
        ->assertInertia(fn ($page) => $page
            ->component('Features/Events/Pages/EventsPage')
            ->has('items')
        );
});

it('promotes the featured event to the spotlight while keeping it in the archive', function () {
    Event::factory()->featured()->create(['name' => 'Innovatech Medika 2026']);
    Event::factory()->count(2)->create();

    // The grid is the full archive, so every status filter has something to select.
    $this->get(route('events'))
        ->assertInertia(fn ($page) => $page
            ->where('spotlight.name', 'Innovatech Medika 2026')
            ->has('items', 3)
        );
});

it('leaves the spotlight null when nothing is featured', function () {
    Event::factory()->count(2)->create();

    $this->get(route('events'))
        ->assertInertia(fn ($page) => $page
            ->where('spotlight', null)
            ->has('items', 2)
        );
});

it('hides archived events', function () {
    Event::factory()->create(['name' => 'Published']);
    Event::factory()->create(['name' => 'Archived', 'is_active' => false]);

    $this->get(route('events'))
        ->assertInertia(fn ($page) => $page
            ->has('items', 1)
            ->where('items.0.name', 'Published')
        );
});

it('orders the leader first in each team roster', function () {
    $event = Event::factory()->create();
    $team = $event->teams()->create([
        'name' => 'Tim Cetak Klinis',
        'course_name' => 'Perancangan Alat Medis',
    ]);
    // Attached members-first so a naive pass-through would put Anggota on top.
    $team->members()->attach(User::factory()->create(['name' => 'Anggota Satu'])->id, ['role_in_team' => 'Anggota']);
    $team->members()->attach(User::factory()->create(['name' => 'Ketua Tim'])->id, ['role_in_team' => 'Ketua']);

    $this->get(route('events.show', $event->slug))
        ->assertInertia(fn ($page) => $page
            ->where('event.teams.0.members.0.roleInTeam', 'Ketua')
        );
});

it('exposes the derived status and team count on each card', function () {
    $event = Event::factory()->ongoing()->create();
    $event->teams()->create(['name' => 'Tim Cetak Klinis', 'course_name' => 'Perancangan Alat Medis']);

    $this->get(route('events'))
        ->assertInertia(fn ($page) => $page
            ->where('items.0.status', 'ongoing')
            ->where('items.0.teamsCount', 1)
        );
});

it('lists trainings alongside events as Workshops', function () {
    Event::factory()->create(['category' => 'Exhibition']);
    Training::factory()->create(['title' => 'Dasar Cetak 3D', 'category' => 'Digital Fabrication']);

    $this->get(route('events'))
        ->assertInertia(function ($page) {
            $items = collect($page->toArray()['props']['items']);
            $training = $items->firstWhere('kind', 'training');

            expect($items)->toHaveCount(2)
                ->and($items->firstWhere('kind', 'event')['group'])->toBe('Exhibition')
                ->and($training['group'])->toBe('Workshop')
                // `group` is the page taxonomy; `category` stays the course's topic tag.
                ->and($training['category'])->toBe('Digital Fabrication')
                ->and($training['status'])->toBeIn(['upcoming', 'ongoing', 'past']);
        });
});

it('hides archived trainings from the merged grid', function () {
    Training::factory()->create(['is_active' => true]);
    Training::factory()->create(['is_active' => false]);

    $this->get(route('events'))
        ->assertInertia(fn ($page) => $page->has('items', 1));
});

it('falls back to the staff-pick training only when no event is featured', function () {
    Training::factory()->create(['title' => 'Kelas Unggulan', 'is_featured' => true]);

    $this->get(route('events'))
        ->assertInertia(fn ($page) => $page->where('staffPick.title', 'Kelas Unggulan'));

    Event::factory()->featured()->create();

    $this->get(route('events'))
        ->assertInertia(fn ($page) => $page->where('staffPick', null));
});

it('sorts the merged grid as an agenda: ongoing, then soonest upcoming, then newest past', function () {
    Event::factory()->create(['name' => 'Long past', 'starts_at' => now()->subYears(2), 'ends_at' => now()->subYears(2)->addDay()]);
    Event::factory()->create(['name' => 'Recent past', 'starts_at' => now()->subMonth(), 'ends_at' => now()->subMonth()->addDay()]);
    Event::factory()->create(['name' => 'Far off', 'starts_at' => now()->addYear(), 'ends_at' => now()->addYear()->addDay()]);
    Event::factory()->ongoing()->create(['name' => 'Happening now']);
    Training::factory()->create(['title' => 'Soon', 'date' => now()->addWeek()]);

    $this->get(route('events'))
        ->assertInertia(function ($page) {
            $labels = collect($page->toArray()['props']['items'])
                ->map(fn ($i) => $i['name'] ?? $i['title'])
                ->all();

            expect($labels)->toBe(['Happening now', 'Soon', 'Far off', 'Recent past', 'Long past']);
        });
});

it('redirects the old training index to the merged events page', function () {
    $this->get('/training')->assertRedirect('/events')->assertStatus(301);
});
