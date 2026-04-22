<?php

namespace App\Actions\Event;

use App\DTOs\Event\EventData;
use App\Models\Event;

class UpdateEventAction
{
    public function execute(Event $event, EventData $data): Event
    {
        $event->update([
            'name' => $data->name,
            'year' => $data->year,
            'theme_title' => $data->theme_title,
        ]);
        return $event;
    }
}
