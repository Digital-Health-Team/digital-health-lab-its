<?php

namespace App\DTOs\RawMaterial;

class RawMaterialData
{
    public function __construct(
        public int $brand_id,
        public string $name,
        public string $unit,
        public ?int $created_by = null,
    ) {}
}
