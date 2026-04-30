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
        public bool $is_active = true
    ) {}
}
