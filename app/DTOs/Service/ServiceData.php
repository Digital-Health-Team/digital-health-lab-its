<?php

namespace App\DTOs\Service;

class ServiceData
{
    public function __construct(
        public string $name,
        public ?string $description,
        public int $base_price,
        public ?string $whatsapp_number = null,
        public string $service_type = 'printing',
        // Appended last on purpose: callers construct this positionally, so inserting
        // ahead of $whatsapp_number/$service_type would silently reassign their args.
        /** English overlay — nullable, falls back to the base column. See App\Traits\HasEnglishOverlay. */
        public ?string $name_en = null,
        public ?string $description_en = null,
    ) {}
}
