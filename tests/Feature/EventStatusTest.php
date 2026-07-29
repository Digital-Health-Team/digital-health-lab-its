<?php

use App\Models\Event;

it('reports upcoming when the start date is in the future', function () {
    $event = new Event(['starts_at' => now()->addWeek(), 'ends_at' => now()->addWeek()->addDays(2)]);

    expect($event->status())->toBe(Event::STATUS_UPCOMING);
});

it('reports ongoing while now sits inside the date range', function () {
    $event = new Event(['starts_at' => now()->subDay(), 'ends_at' => now()->addDay()]);

    expect($event->status())->toBe(Event::STATUS_ONGOING);
});

it('reports past once the end date has gone by', function () {
    $event = new Event(['starts_at' => now()->subDays(5), 'ends_at' => now()->subDays(3)]);

    expect($event->status())->toBe(Event::STATUS_PAST);
});

it('keeps a single-day event ongoing for the rest of that day', function () {
    $event = new Event(['starts_at' => now()->startOfDay(), 'ends_at' => null]);

    expect($event->status())->toBe(Event::STATUS_ONGOING);
});

it('treats an unscheduled event as upcoming', function () {
    $event = new Event(['starts_at' => null, 'ends_at' => null]);

    expect($event->status())->toBe(Event::STATUS_UPCOMING);
});
