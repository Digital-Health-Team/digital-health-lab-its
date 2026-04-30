<?php

namespace App\DTOs\Transaction;

class CreateBookingData
{
    public function __construct(
        public int $user_id,
        public int $service_id,
        public string $status = 'pending'
    ) {}
}
