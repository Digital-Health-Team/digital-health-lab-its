<?php

namespace App\Actions\Event\Project;

use App\Models\Project;

class UpdateProjectStatusAction
{
    public function execute(Project $project, string $status): void
    {
        $project->update([
            'status' => $status,
            'validated_by' => auth()->id()
        ]);
    }
}
