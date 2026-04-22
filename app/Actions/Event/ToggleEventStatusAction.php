<?php

namespace App\Actions\Event;

use App\Models\Event;

class ToggleEventStatusAction
{
    public function execute(Event $event): void
    {
        $event->update(['is_active' => !$event->is_active]);
    }
}
