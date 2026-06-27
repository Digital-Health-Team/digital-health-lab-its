<?php

namespace App\DTOs\Project;

class OpenSourceProjectData
{
    public function __construct(
        public int $user_id,
        public string $title,
        public string $category,
        public array $new_files = [],
        public string $status = 'pending',
        public ?string $slug = null,
        public ?string $caption = null,
        public ?string $listing_type = null,
        public array $description = [],
        public array $highlights = [],
        public ?string $cover_color = null,
        public bool $is_featured = false,
        public string $license = 'MIT',
        public ?string $version = null,
        public ?string $format = null,
        public array $includes = [],
    ) {}
}
