<?php

namespace App\Actions\Event\Project;

use App\DTOs\Event\ProjectData;
use App\Models\Project;

class UpdateProjectAction
{
    public function execute(Project $project, ProjectData $data): Project
    {
        $project->update([
            'title' => $data->title,
            'category' => $data->category,
        ]);
        return $project;
    }
}
