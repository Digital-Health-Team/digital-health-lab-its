<?php

namespace App\Actions\Event;

use App\DTOs\Event\EventData;
use App\Models\Event;

class CreateEventAction
{
    public function execute(EventData $data): Event
    {
        return Event::create([
            'name' => $data->name,
            'year' => $data->year,
            'theme_title' => $data->theme_title,
            'is_active' => $data->is_active,
        ]);
    }
}
