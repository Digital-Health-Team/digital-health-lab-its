<?php

namespace App\DTOs\User;

class UserData
{
    public function __construct(
        public string $name,
        public string $email,
        public string $role,
        public ?string $password = null,
        public mixed $profile_photo = null, // Bisa berupa Objek File atau null
    ) {
    }
}
