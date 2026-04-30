<?php

namespace App\Actions\Event;

use App\Models\Team;

class DeleteTeamAction
{
    public function execute(Team $team): void
    {
        if ($team->projects()->exists()) {
            throw new \Exception(__('Cannot delete team because they have submitted projects.'));
        }
        $team->delete();
    }
}
