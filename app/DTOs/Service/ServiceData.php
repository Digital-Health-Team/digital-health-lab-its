<?php

namespace App\DTOs\Service;

class ServiceData
{
    public function __construct(
        public string $name,
        public ?string $description,
        public int $base_price
    ) {}
}
