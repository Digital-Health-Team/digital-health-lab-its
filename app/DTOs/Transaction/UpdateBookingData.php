<?php

namespace App\DTOs\Transaction;

class UpdateBookingData
{
    public function __construct(
        public int $service_id,
        public string $status,
        public ?int $final_price
    ) {}
}
