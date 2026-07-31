<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Training;
use Illuminate\Support\Collection;
use Inertia\Inertia;
use Inertia\Response;

class EventController extends Controller
{
    /**
     * The one public agenda listing. Events and trainings stay separate tables —
     * only the page is merged — so each renders with its own card and keeps its
     * own detail flow. `group` is the shared taxonomy the tabs filter by.
     */
    public function index(): Response
    {
        $events = Event::where('is_active', true)
            ->withCount('teams')
            ->get();

        $trainings = Training::where('is_active', true)
            ->withCount('registrations')
            ->get();

        $items = collect([
            ...$events->map(fn ($e) => [
                'kind' => 'event',
                'group' => $e->category,
                ...$this->toCardShape($e),
            ]),
            ...$trainings->map(fn ($t) => [
                'kind' => 'training',
                // Every training is a workshop. Note `group` is not `category`:
                // a course card's own `category` is its topic ("Digital Fabrication").
                'group' => 'Workshop',
                'status' => $t->status(),
                // Normalised so the whole grid sorts on one key.
                'startsAt' => $t->date?->toIso8601String(),
                ...$t->toCardArray(),
            ]),
        ]);

        // The spotlight promotes one item; it does not remove it from the archive.
        // Keeping it in the grid is what lets "All" and its status filters tell the
        // truth — an ongoing event is otherwise unreachable by filter.
        $spotlight = $events->firstWhere('is_featured', true);
        $staffPick = $trainings->firstWhere('is_featured', true);

        return Inertia::render('Features/Events/Pages/EventsPage', [
            'items' => $this->inAgendaOrder($items),
            'spotlight' => $spotlight ? $this->toSpotlightShape($spotlight) : null,
            // One promo slot: an event wins it, a workshop fills it otherwise.
            'staffPick' => $spotlight === null && $staffPick ? $staffPick->toStaffPickArray() : null,
        ]);
    }

    /**
     * What's on, then what's next (soonest first), then the archive (newest first).
     *
     * @param  Collection<int, array<string, mixed>>  $items
     * @return array<int, array<string, mixed>>
     */
    private function inAgendaOrder(Collection $items): array
    {
        $byStatus = $items->groupBy('status');
        // ISO-8601 sorts lexically, so an unscheduled item ('9999') lands last
        // rather than jumping the queue the way a bare null sort would.
        $soonestFirst = fn ($i) => $i['startsAt'] ?? '9999';

        return collect([
            ...$byStatus->get(Event::STATUS_ONGOING, collect())->sortBy($soonestFirst),
            ...$byStatus->get(Event::STATUS_UPCOMING, collect())->sortBy($soonestFirst),
            ...$byStatus->get(Event::STATUS_PAST, collect())->sortByDesc('startsAt'),
        ])->values()->all();
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
            'name' => $event->localized('name'),
            'year' => $event->year,
            'themeTitle' => $event->localized('theme_title'),
            'thumbnailUrl' => $event->thumbnail_url,
            'startsAt' => $event->starts_at?->toIso8601String(),
            'endsAt' => $event->ends_at?->toIso8601String(),
            'location' => $event->localized('location'),
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
            'subtitle' => $event->localized('subtitle'),
        ];
    }

    /** @return array<string, mixed> */
    private function toDetailShape(Event $event): array
    {
        return [
            ...$this->toCardShape($event),
            'subtitle' => $event->localized('subtitle'),
            'description' => $event->localized('description'),
            'registrationUrl' => $event->registration_url,
            'teams' => $event->teams->map(fn ($team) => [
                'id' => $team->id,
                'name' => $team->localized('name'),
                'courseName' => $team->localized('course_name'),
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
                    'title' => $project->localized('title'),
                    'category' => $project->category,
                ])->all(),
            ])->all(),
        ];
    }
}
