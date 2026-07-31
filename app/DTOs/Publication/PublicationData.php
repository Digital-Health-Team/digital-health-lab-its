<?php

namespace App\DTOs\Publication;

class PublicationData
{
    public function __construct(
        public string $title,
        public string $author,
        public string $category,
        public ?string $slug = null,
        public ?string $abstract = null,
        public array $description = [],
        public array $keywords = [],
        public ?string $doi = null,
        public ?string $journal = null,
        public ?string $pmid = null,
        public ?string $pdf_file_size = null,
        public bool $is_free_access = false,
        public bool $is_featured = false,
        public ?string $published_at = null,
        public mixed $thumbnail_file = null,
        public mixed $pdf_file = null,
        // Appended last on purpose — inserting ahead of the existing params would
        // silently reassign the arguments of any positional caller.
        /** English overlay — empty falls back to the base column. See App\Traits\HasEnglishOverlay. */
        public ?string $title_en = null,
        public ?string $abstract_en = null,
        public array $description_en = [],
        public array $keywords_en = [],
        /** Null for admin-authored site content; set for student submissions. */
        public ?int $user_id = null,
        public string $status = 'pending',
    ) {}
}
