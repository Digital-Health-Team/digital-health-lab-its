<?php

namespace App\DTOs\CMS;

class StructuralMemberData
{
    public function __construct(
        public ?int $user_id,
        public string $name,
        public string $position,
        public int $display_order = 0,
        public bool $is_active = true
    ) {}
}
