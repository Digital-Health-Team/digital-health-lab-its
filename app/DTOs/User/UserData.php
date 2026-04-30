<?php

namespace App\DTOs\User;

use Illuminate\Http\UploadedFile;

class UserData
{
    public function __construct(
        public string $full_name,
        public string $email,
        public int $role_id,
        public ?string $password = null,
        public string|UploadedFile|null $profile_photo = null,
        public ?string $phone = null,
        public ?string $address = null,
        public ?string $nik = null,
        public ?string $nim = null,
        public ?string $department = null,
        public ?string $faculty = null,
        public ?string $university = null
    ) {}
}
