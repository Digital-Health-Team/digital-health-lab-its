<?php

namespace App\DTOs\Event;

class TeamMemberData
{
    public function __construct(
        public int $team_id,
        public int $user_id,
        public string $role_in_team
    ) {}
}
