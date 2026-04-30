<?php

namespace App\Actions\Event;

use App\DTOs\Event\TeamData;
use App\Models\Team;

class UpdateTeamAction
{
    public function execute(Team $team, TeamData $data): Team
    {
        $team->update([
            'name' => $data->name,
            'course_name' => $data->course_name,
        ]);
        return $team;
    }
}
