<?php

namespace App\Actions\Event;

use App\DTOs\Event\TeamMemberData;
use App\DTOs\Event\TeamData;
use App\Models\Team;

class AddTeamMemberAction
{
    public function execute(TeamData $data = null, TeamMemberData $memberData): void
    {
        $team = Team::findOrFail($memberData->team_id);

        // Validasi agar tidak ada duplikasi user dalam satu tim
        if ($team->members()->where('user_id', $memberData->user_id)->exists()) {
            throw new \Exception(__('This user is already a member of the team.'));
        }

        $team->members()->attach($memberData->user_id, [
            'role_in_team' => $memberData->role_in_team
        ]);
    }
}
