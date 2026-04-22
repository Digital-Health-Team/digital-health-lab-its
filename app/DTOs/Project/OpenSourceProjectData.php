<?php

namespace App\DTOs\Project;

class OpenSourceProjectData
{
    public function __construct(
        public int $user_id,
        public string $title,
        public string $category,
        public array $new_files = [], // Array dari Illuminate\Http\UploadedFile
        public string $status = 'pending'
    ) {}
}
