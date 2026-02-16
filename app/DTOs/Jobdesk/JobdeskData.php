<?php

namespace App\DTOs\Jobdesk;

class JobdeskData
{
    public function __construct(
        public int $project_id,
        public int $assigned_to,
        public array $title,        // ['id' => '...', 'en' => '...']
        public array $description,  // ['id' => '...', 'en' => '...']
        public string $deadline_task,
        public string $status = 'pending',
    ) {
    }
}
