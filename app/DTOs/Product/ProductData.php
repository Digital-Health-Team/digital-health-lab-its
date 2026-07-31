<?php

namespace App\DTOs\Product;

class ProductData
{
    public function __construct(
        public string $name,
        public ?string $description,
        public int $price_min,
        public int $price_max,
        public int $creator_id,
        public array $new_photos = [], // Array dari Illuminate\Http\UploadedFile
        public bool $is_active = true,
        // Appended last on purpose: inserting ahead of the existing params would
        // silently reassign the arguments of any positional caller.
        /** English overlay — nullable, falls back to the base column. See App\Traits\HasEnglishOverlay. */
        public ?string $name_en = null,
        public ?string $description_en = null,
    ) {}
}
