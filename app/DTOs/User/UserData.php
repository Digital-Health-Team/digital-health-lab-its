<?php

namespace App\DTOs\User;

class UserData
{
    public function __construct(
        public string $name,
        public string $email,
        public string $role,
        public ?string $password = null,
        public $profile_photo = null,
        public array $departments = [] // Ubah jadi array
    ) {
    }
}
