<?php

namespace App\DTOs\Transaction;

class MaterialMovementData
{
    public function __construct(
        public int $raw_material_id,
        public ?int $service_booking_id, // Bisa null jika restock manual
        public string $movement_type, // 'in' atau 'out'
        public int $quantity,
        public ?string $notes = null
    ) {}
}
