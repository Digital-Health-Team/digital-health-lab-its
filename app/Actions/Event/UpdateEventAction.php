<?php

namespace App\Actions\Event;

use App\DTOs\Event\EventData;
use App\Models\Event;

class UpdateEventAction
{
    public function execute(Event $event, EventData $data): Event
    {
        // The slug is the public route key — renaming an event must not break
        // links that are already out in the world.
        $event->update([
            ...$data->toAttributes(),
            'slug' => $event->slug ?: $data->slug,
        ]);

        return $event;
    }
}
