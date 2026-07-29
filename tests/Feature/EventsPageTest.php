<?php

use App\Models\Event;
use App\Models\User;

it('renders the events page for guests', function () {
    $this->get(route('events'))
        ->assertStatus(200)
        ->assertInertia(fn ($page) => $page
            ->component('Features/Events/Pages/EventsPage')
            ->has('events')
        );
});

it('promotes the featured event to the spotlight while keeping it in the archive', function () {
    Event::factory()->featured()->create(['name' => 'Innovatech Medika 2026']);
    Event::factory()->count(2)->create();

    // The grid is the full archive, so every status filter has something to select.
    $this->get(route('events'))
        ->assertInertia(fn ($page) => $page
            ->where('spotlight.name', 'Innovatech Medika 2026')
            ->has('events', 3)
        );
});

it('leaves the spotlight null when nothing is featured', function () {
    Event::factory()->count(2)->create();

    $this->get(route('events'))
        ->assertInertia(fn ($page) => $page
            ->where('spotlight', null)
            ->has('events', 2)
        );
});

it('hides archived events', function () {
    Event::factory()->create(['name' => 'Published']);
    Event::factory()->create(['name' => 'Archived', 'is_active' => false]);

    $this->get(route('events'))
        ->assertInertia(fn ($page) => $page
            ->has('events', 1)
            ->where('events.0.name', 'Published')
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
            ->where('events.0.status', 'ongoing')
            ->where('events.0.teamsCount', 1)
        );
});
