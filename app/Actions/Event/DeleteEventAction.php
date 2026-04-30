<?php

namespace App\Actions\Event;

use App\Models\Event;

class DeleteEventAction
{
    public function execute(Event $event): void
    {
        if ($event->teams()->exists()) {
            throw new \Exception(__('Cannot delete event because it has registered teams. Please delete the teams first.'));
        }
        $event->delete();
    }
}
