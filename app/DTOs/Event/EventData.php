<?php

namespace App\DTOs\Event;

use Illuminate\Support\Str;

class EventData
{
    public function __construct(
        public string $name,
        public int $year,
        public string $theme_title,
        public bool $is_active = true,
        public ?string $slug = null,
        public ?string $subtitle = null,
        public ?string $description = null,
        public ?string $thumbnail_url = null,
        public ?string $starts_at = null,
        public ?string $ends_at = null,
        public ?string $location = null,
        public ?string $category = null,
        public ?string $registration_url = null,
        public bool $is_featured = false,
    ) {
        // slug is the public route key; derive it when the admin leaves it blank.
        $this->slug = $slug ?: Str::slug($name.'-'.$year);
    }

    /** @return array<string, mixed> */
    public function toAttributes(): array
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'year' => $this->year,
            'theme_title' => $this->theme_title,
            'subtitle' => $this->subtitle,
            'description' => $this->description,
            'thumbnail_url' => $this->thumbnail_url,
            'starts_at' => $this->starts_at,
            'ends_at' => $this->ends_at,
            'location' => $this->location,
            'category' => $this->category,
            'registration_url' => $this->registration_url,
            'is_featured' => $this->is_featured,
        ];
    }
}
