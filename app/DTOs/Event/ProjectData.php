<?php

namespace App\DTOs\Event;

class ProjectData
{
    public function __construct(
        public int $team_id,
        public string $title,
        public string $category,
        public string $status = 'pending'
    ) {}
}
