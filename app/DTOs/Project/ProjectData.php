<?php

namespace App\DTOs\Project;

class ProjectData
{
    public function __construct(
        public array $name,        // Array Bahasa
        public array $description, // Array Bahasa
        public string $deadline_global,
        public string $status = 'active',
    ) {
    }
}
