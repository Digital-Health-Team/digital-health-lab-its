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
        // Appended last on purpose — inserting ahead of the existing params would
        // silently reassign the arguments of any positional caller.
        /** English overlay — empty falls back to the base column. See App\Traits\HasEnglishOverlay. */
        public ?string $title_en = null,
        public ?string $caption_en = null,
        public array $description_en = [],
        public array $highlights_en = [],
        public array $includes_en = [],
    ) {}
}
