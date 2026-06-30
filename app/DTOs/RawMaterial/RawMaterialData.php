<?php

namespace App\DTOs\RawMaterial;

class RawMaterialData
{
    public function __construct(
        public int $lab_id,
        public int $category_id,
        public int $brand_id,
        public int $color_id,
        public string $unit,
        public int $current_stock,
    ) {}
}
