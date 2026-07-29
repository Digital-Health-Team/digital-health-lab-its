<?php

use App\Models\Event;
use App\Models\User;

it('renders the event detail page for guests', function () {
    $event = Event::factory()->create(['slug' => 'innovatech-medika-2026']);

    $this->get(route('events.show', $event->slug))
        ->assertStatus(200)
        ->assertInertia(fn ($page) => $page
            ->component('Features/Events/Pages/EventDetailPage')
            ->has('event')
            ->has('related')
        );
});

it('returns 404 for an unknown event slug', function () {
    $this->get(route('events.show', ['event' => 'non-existent-slug']))
        ->assertStatus(404);
});

it('returns 404 for an archived event', function () {
    $event = Event::factory()->create(['is_active' => false]);

    $this->get(route('events.show', $event->slug))
        ->assertStatus(404);
});

it('includes participating teams with their members and projects', function () {
    $event = Event::factory()->create();
    $team = $event->teams()->create([
        'name' => 'Tim Prostetik Nusantara',
        'course_name' => 'Perancangan Alat Medis',
    ]);
    $team->members()->attach(User::factory()->create(['name' => 'Rani'])->id, [
        'role_in_team' => 'Ketua',
    ]);
    $team->projects()->create([
        'title' => 'Prostetik Jari Tangan Low-Cost',
        'category' => '3d_products',
        'status' => 'approved',
    ]);

    $this->get(route('events.show', $event->slug))
        ->assertInertia(fn ($page) => $page
            ->where('event.teams.0.name', 'Tim Prostetik Nusantara')
            ->where('event.teams.0.members.0.name', 'Rani')
            ->where('event.teams.0.members.0.roleInTeam', 'Ketua')
            ->where('event.teams.0.projects.0.title', 'Prostetik Jari Tangan Low-Cost')
        );
});

it('excludes the current event from the related list', function () {
    $event = Event::factory()->create();
    Event::factory()->count(2)->create();

    $this->get(route('events.show', $event->slug))
        ->assertInertia(fn ($page) => $page
            ->has('related', 2)
            ->where('related.0.slug', fn ($slug) => $slug !== $event->slug)
        );
});
