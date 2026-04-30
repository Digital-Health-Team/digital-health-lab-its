<?php

namespace App\DTOs\Event;

class TeamData
{
    public function __construct(
        public int $event_id,
        public string $name,
        public string $course_name
    ) {}
}
