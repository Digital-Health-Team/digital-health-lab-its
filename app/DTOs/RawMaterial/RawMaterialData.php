<?php

namespace App\DTOs\RawMaterial;

/**
 * Carries raw string values from the admin UI.
 * The Action layer resolves these strings to FK IDs via firstOrCreate().
 */
class RawMaterialData
{
    public function __construct(
        public string $lab,
        public string $category,
        public string $brand,
        public string $color,
        public string $unit,
        public int $current_stock
    ) {}
}
