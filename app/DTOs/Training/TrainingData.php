<?php

namespace App\DTOs\Training;

class TrainingData
{
    public function __construct(
        public string $title,
        public string $subtitle,
        public string $description,
        public ?string $thumbnail_url,
        public int $price,
        public bool $is_paid,
        public bool $is_active,
        public bool $is_featured,
        public string $date,
        public ?string $location,
        public ?int $max_participants,
        public string $level,
        public string $duration,
        public string $language,
        public string $instructor_name,
        public ?string $instructor_title,
        public ?string $instructor_bio,
        public ?string $instructor_avatar_url,
        public array $what_you_will_learn,
        public array $includes,
        public array $curriculum,
        // Appended last on purpose — inserting ahead of the existing params would
        // silently reassign the arguments of any positional caller.
        /** English overlay — empty falls back to the base column. See App\Traits\HasEnglishOverlay. */
        public ?string $title_en = null,
        public ?string $subtitle_en = null,
        public ?string $description_en = null,
        public ?string $location_en = null,
        public ?string $instructor_title_en = null,
        public ?string $instructor_bio_en = null,
        public array $what_you_will_learn_en = [],
        public array $includes_en = [],
        public array $curriculum_en = [],
    ) {}
}
