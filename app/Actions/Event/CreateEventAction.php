<?php

namespace App\Actions\Event;

use App\DTOs\Event\EventData;
use App\Models\Event;

class CreateEventAction
{
    public function execute(EventData $data): Event
    {
        return Event::create([
            ...$data->toAttributes(),
            'is_active' => $data->is_active,
        ]);
    }
}
