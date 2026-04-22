<?php

namespace App\Actions\Event;

use App\DTOs\Event\TeamData;
use App\Models\Team;

class CreateTeamAction
{
    public function execute(TeamData $data): Team
    {
        return Team::create([
            'event_id' => $data->event_id,
            'name' => $data->name,
            'course_name' => $data->course_name,
        ]);
    }
}
