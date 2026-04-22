<?php

namespace App\Actions\Event;

use App\DTOs\Event\TeamMemberData;
use App\Models\Team;

class UpdateTeamMemberRoleAction
{
    public function execute(TeamMemberData $data): void
    {
        $team = Team::findOrFail($data->team_id);

        $team->members()->updateExistingPivot($data->user_id, [
            'role_in_team' => $data->role_in_team
        ]);
    }
}
