<?php

namespace App\Actions\Event\Project;

use App\Models\Project;

class DeleteProjectAction
{
    public function execute(Project $project): void
    {
        // Jika ada lampiran fisik (Attachment), hapus juga di sini nantinya
        $project->delete();
    }
}
