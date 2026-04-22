<?php

namespace App\DTOs\Event;

class EventData
{
    public function __construct(
        public string $name,
        public int $year,
        public string $theme_title,
        public bool $is_active = true
    ) {}
}
