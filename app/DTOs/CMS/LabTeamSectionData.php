<?php

namespace App\DTOs\CMS;

class LabTeamSectionData
{
    public function __construct(
        public readonly string $label_id,
        public readonly string $label_en,
        public readonly int $sort_order,
        public readonly bool $is_active,
    ) {}
}
