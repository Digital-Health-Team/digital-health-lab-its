<?php

namespace App\Actions\Announcement;

use App\Models\Announcement;

class DeleteAnnouncementAction
{
    public function execute(Announcement $announcement): bool
    {
        return $announcement->delete();
    }
}
