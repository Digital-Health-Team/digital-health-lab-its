<?php

namespace App\Actions\Project;

use App\Models\OpenSourceProject;
use Illuminate\Support\Facades\Storage;

class DeleteOpenSourceProjectAction
{
    public function execute(OpenSourceProject $project): void
    {
        foreach ($project->attachments as $attachment) {
            if (Storage::disk('public')->exists($attachment->file_url)) {
                Storage::disk('public')->delete($attachment->file_url);
            }
            $attachment->delete();
        }

        $project->delete();
    }
}
