<?php

namespace App\DTOs\Service;

class ServiceData
{
    public function __construct(
        public string $name,
        public ?string $description,
        public int $base_price,
        public ?string $whatsapp_number = null,
        public string $service_type = 'printing',
    ) {}
}
