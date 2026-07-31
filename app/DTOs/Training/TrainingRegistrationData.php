<?php

namespace App\DTOs\Training;

class TrainingRegistrationData
{
    public function __construct(
        public int $training_id,
        public int $user_id,
        public string $full_name,
        public string $email,
        public string $phone_number,
        public ?string $preferred_session = null,
        public ?string $additional_notes = null,
    ) {}
}
