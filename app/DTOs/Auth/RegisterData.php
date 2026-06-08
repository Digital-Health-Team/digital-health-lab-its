<?php

namespace App\DTOs\Auth;

class RegisterData
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
        public int $role_id,
        public ?string $nim = null,
        public ?string $nik = null,
        public ?string $university = null,
        public ?string $faculty = null,
        public ?string $department = null,
        public ?string $phone = null,
        public ?string $address = null,
        public ?string $profile_photo = null,
    ) {}
}
