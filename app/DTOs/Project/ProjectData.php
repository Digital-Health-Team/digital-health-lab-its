<?php

namespace App\DTOs\Project;

use Illuminate\Support\Str;

class ProjectData
{
    public string $slug;

    public function __construct(
        public array $name,        // Array Bahasa
        public array $description, // Array Bahasa
        public string $deadline_global,
        public string $status = 'active',
    ) {
        // Ambil string dari nama untuk dijadikan slug (prioritas ID, lalu EN)
        $nameString = $this->name['id'] ?? ($this->name['en'] ?? 'project-' . uniqid());

        // Generate slug (contoh: "nama-project-saya")
        // Tambahkan uniqid() jika ingin memastikan slug selalu unik, atau handle di Action
        $this->slug = Str::slug($nameString);
    }
}
