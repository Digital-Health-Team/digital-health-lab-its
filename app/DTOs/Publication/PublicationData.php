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
    ) {}
}
