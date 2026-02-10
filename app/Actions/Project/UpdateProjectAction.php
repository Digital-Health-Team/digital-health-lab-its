<?php

namespace App\Actions\Project;

use App\Models\Project;
use App\DTOs\Project\ProjectData;
use App\Services\AutoTranslationService;

class UpdateProjectAction
{
    public function __construct(
        protected AutoTranslationService $translator
    ) {
    }

    public function execute(Project $project, ProjectData $data): Project
    {
        // 1. Auto Translate jika salah satu kosong
        $finalName = $this->translator->fillMissingTranslations($data->name);
        $finalDesc = $this->translator->fillMissingTranslations($data->description);

        // 2. Update Database
        $project->update([
            'name' => $finalName,
            'description' => $finalDesc,
            'deadline_global' => $data->deadline_global,
            'status' => $data->status,
        ]);

        return $project;
    }
}
