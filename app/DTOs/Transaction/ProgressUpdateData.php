<?php

namespace App\DTOs\Transaction;

class ProgressUpdateData
{
    public function __construct(
        public int $service_booking_id,
        public string $status_label, // Disesuaikan dengan DB
        public int $percentage,      // Tambahan Persentase
        public string $notes,
        public ?array $attachments = []
    ) {}
}
