<?php

namespace App\Actions\Project;

use App\Models\Project;
use App\DTOs\Project\ProjectData;
use App\Services\AutoTranslationService;

class CreateProjectAction
{
    public function __construct(
        protected AutoTranslationService $translator
    ) {
    }

    public function execute(ProjectData $data): Project
    {
        // 1. Auto Translate jika salah satu kosong
        $finalName = $this->translator->fillMissingTranslations($data->name);
        $finalDesc = $this->translator->fillMissingTranslations($data->description);

        // 2. Simpan ke Database
        return Project::create([
            'name' => $finalName,         // Array yang sudah lengkap (ID + EN)
            'slug' => $data->slug, // Masukkan slug dari DTO
            'description' => $finalDesc,  // Array yang sudah lengkap (ID + EN)
            'deadline_global' => $data->deadline_global,
            'status' => $data->status,
            'created_by' => auth()->id(),
        ]);
    }
}
