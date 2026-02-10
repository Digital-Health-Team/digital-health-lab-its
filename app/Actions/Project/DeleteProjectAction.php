<?php

namespace App\Actions\Project;

use App\Models\Project;

class DeleteProjectAction
{
    public function execute(Project $project): void
    {
        // Opsional: Cek apakah project punya task/jobdesk sebelum dihapus
        // if ($project->jobdesks()->exists()) { throw ... }

        $project->delete();
    }
}
