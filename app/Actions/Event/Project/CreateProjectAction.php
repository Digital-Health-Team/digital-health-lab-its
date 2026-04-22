<?php

namespace App\Actions\Event\Project;

use App\DTOs\Event\ProjectData;
use App\Models\Project;

class CreateProjectAction
{
    public function execute(ProjectData $data): Project
    {
        return Project::create([
            'team_id' => $data->team_id,
            'title' => $data->title,
            'category' => $data->category,
            'status' => $data->status,
        ]);
    }
}
