<?php

namespace App\DTOs\Transaction;

class SendMessageData
{
    public function __construct(
        public int $service_booking_id,
        public string $body,
    ) {}
}
