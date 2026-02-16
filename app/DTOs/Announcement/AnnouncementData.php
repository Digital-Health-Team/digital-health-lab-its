<?php

namespace App\DTOs\Announcement;

class AnnouncementData
{
    public function __construct(
        public string $title,
        public string $content,
        public bool $is_published,
        public bool $is_global, // [BARU] Status untuk semua user
        public array $recipient_ids = [],
        public ?int $created_by = null,
    ) {
    }
}
