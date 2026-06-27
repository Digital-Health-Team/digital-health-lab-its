<?php

namespace App\DTOs\Transaction;

class BookingPaymentData
{
    public function __construct(
        public int $service_booking_id,
        public string $termin_name,
        public int $amount,
    ) {}
}
