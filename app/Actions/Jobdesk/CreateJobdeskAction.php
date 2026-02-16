<?php

namespace App\Actions\Jobdesk;

use App\Models\Jobdesk;
use App\DTOs\Jobdesk\JobdeskData;
// Pastikan service ini ada, atau hapus dependency injection jika belum dibuat
use App\Services\AutoTranslationService;

class CreateJobdeskAction
{
    public function __construct(
        protected AutoTranslationService $translator
    ) {
    }

    public function execute(JobdeskData $data): Jobdesk
    {
        // 1. Auto Translate jika salah satu kosong
        $finalTitle = $this->translator->fillMissingTranslations($data->title);
        $finalDesc = $this->translator->fillMissingTranslations($data->description);

        // 2. Simpan ke Database
        return Jobdesk::create([
            'project_id' => $data->project_id,
            'assigned_to' => $data->assigned_to,
            'title' => $finalTitle,
            'description' => $finalDesc,
            'deadline_task' => $data->deadline_task,
            'status' => $data->status,
            'created_by' => auth()->user()->id, // Pastikan ini diisi saat membuat Jobdesk
        ]);
    }
}
