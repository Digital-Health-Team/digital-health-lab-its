<?php

namespace App\Actions\Project;

use App\Models\OpenSourceProject;

class UpdateOpenSourceProjectStatusAction
{
    public function execute(OpenSourceProject $project, string $status): void
    {
        $project->update([
            'status' => $status,
            'validated_by' => auth()->id()
        ]);
    }
}
