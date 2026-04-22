<?php

namespace App\Actions\Event;

use App\Models\Team;

class RemoveTeamMemberAction
{
    public function execute(Team $team, int $userId): void
    {
        $team->members()->detach($userId);
    }
}
