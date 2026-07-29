<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Inertia\Inertia;
use Inertia\Response;

class EventController extends Controller
{
    public function index(): Response
    {
        $events = Event::where('is_active', true)
            ->withCount('teams')
            // year is NOT NULL on every row; starts_at is not, and NULL ordering
            // differs between SQLite and MySQL.
            ->orderByDesc('year')
            ->orderByDesc('starts_at')
            ->get();

        // The spotlight promotes one event; it does not remove it from the archive.
        // Keeping it in the grid is what lets "All events" and its status filters
        // tell the truth — an ongoing event is otherwise unreachable by filter.
        $spotlight = $events->firstWhere('is_featured', true);

        return Inertia::render('Features/Events/Pages/EventsPage', [
            'events' => $events->map(fn ($e) => $this->toCardShape($e))->all(),
            'spotlight' => $spotlight ? $this->toSpotlightShape($spotlight) : null,
        ]);
    }

    public function show(Event $event): Response
    {
        abort_unless($event->is_active, 404);

        $event->load(['teams.members', 'teams.projects'])->loadCount('teams');

        $related = Event::where('is_active', true)
            ->where('id', '!=', $event->id)
            ->withCount('teams')
            ->orderByDesc('year')
            ->orderByDesc('starts_at')
            ->limit(3)
            ->get()
            ->map(fn ($e) => $this->toCardShape($e));

        return Inertia::render('Features/Events/Pages/EventDetailPage', [
            'event' => $this->toDetailShape($event),
            'related' => $related->all(),
        ]);
    }

    /** @return array<string, mixed> */
    private function toCardShape(Event $event): array
    {
        return [
            'id' => $event->id,
            'slug' => $event->slug,
            'href' => route('events.show', $event->slug),
            'name' => $event->name,
            'year' => $event->year,
            'themeTitle' => $event->theme_title,
            'thumbnailUrl' => $event->thumbnail_url,
            'startsAt' => $event->starts_at?->toIso8601String(),
            'endsAt' => $event->ends_at?->toIso8601String(),
            'location' => $event->location,
            'category' => $event->category,
            'status' => $event->status(),
            'teamsCount' => $event->teams_count,
        ];
    }

    /** @return array<string, mixed> */
    private function toSpotlightShape(Event $event): array
    {
        return [
            ...$this->toCardShape($event),
            'subtitle' => $event->subtitle,
        ];
    }

    /** @return array<string, mixed> */
    private function toDetailShape(Event $event): array
    {
        return [
            ...$this->toCardShape($event),
            'subtitle' => $event->subtitle,
            'description' => $event->description,
            'registrationUrl' => $event->registration_url,
            'teams' => $event->teams->map(fn ($team) => [
                'id' => $team->id,
                'name' => $team->name,
                'courseName' => $team->course_name,
                // Leader first — a roster that opens on a random member reads as unordered.
                'members' => $team->members
                    ->sortBy(fn ($member) => $member->pivot->role_in_team === 'Ketua' ? 0 : 1)
                    ->map(fn ($member) => [
                        'id' => $member->id,
                        'name' => $member->name,
                        'roleInTeam' => $member->pivot->role_in_team,
                    ])->values()->all(),
                'projects' => $team->projects->map(fn ($project) => [
                    'id' => $project->id,
                    'title' => $project->title,
                    'category' => $project->category,
                ])->all(),
            ])->all(),
        ];
    }
}
