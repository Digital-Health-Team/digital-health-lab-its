<?php

namespace App\DTOs\RawMaterial;

class RawMaterialData
{
    public function __construct(
        public string $name,
        public string $category,
        public string $unit,
        public int $current_stock
    ) {}
}
