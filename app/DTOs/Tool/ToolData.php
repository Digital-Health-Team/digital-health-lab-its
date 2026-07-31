<?php

namespace App\DTOs\Tool;

class ToolData
{
    public function __construct(
        public readonly string $name,
        public readonly int $lab_id,
    ) {}
}
