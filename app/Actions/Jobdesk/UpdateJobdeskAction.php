<?php

namespace App\Actions\Jobdesk;

use App\Models\Jobdesk;
use App\DTOs\Jobdesk\JobdeskData;
use App\Services\AutoTranslationService;

class UpdateJobdeskAction
{
    public function __construct(
        protected AutoTranslationService $translator
    ) {
    }

    public function execute(Jobdesk $jobdesk, JobdeskData $data): Jobdesk
    {
        $finalTitle = $this->translator->fillMissingTranslations($data->title);
        $finalDesc = $this->translator->fillMissingTranslations($data->description);

        $jobdesk->update([
            'project_id' => $data->project_id,
            'assigned_to' => $data->assigned_to,
            'title' => $finalTitle,
            'description' => $finalDesc,
            'deadline_task' => $data->deadline_task,
            'status' => $data->status,
        ]);

        return $jobdesk;
    }
}
