<?php

namespace App\DTOs\Transaction;

class CreateBookingData
{
    public function __construct(
        public int $user_id,
        public int $service_id,
        public string $status = 'pending',
        public ?string $brief_description = null,
        public ?string $reference_photo_path = null,
        public ?string $model_file_path = null,
        public ?string $material_preference = null,
        public ?string $filament_width = null,
        public ?string $scan_purpose = null,
        public ?array $object_dimensions = null,
    ) {}
}
