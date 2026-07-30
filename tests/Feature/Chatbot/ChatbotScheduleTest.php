<?php

use Illuminate\Console\Scheduling\Schedule;

it('schedules the knowledge index daily at three in the morning', function () {
    $event = collect(app(Schedule::class)->events())
        ->first(fn ($event): bool => str_contains($event->command ?? '', 'chatbot:index'));

    expect($event)->not->toBeNull()
        ->and($event->expression)->toBe('0 3 * * *')
        // Two concurrent ingests would double the embedding spend and race each other's
        // pruning pass, so overlap prevention is part of the contract, not a nicety.
        ->and($event->withoutOverlapping)->toBeTrue();
});
