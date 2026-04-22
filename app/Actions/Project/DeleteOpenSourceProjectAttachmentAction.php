<?php

namespace App\Actions\Project;

use App\Models\Attachment;
use Illuminate\Support\Facades\Storage;

class DeleteOpenSourceProjectAttachmentAction
{
    public function execute(Attachment $attachment): void
    {
        $project = $attachment->attachable;
        $wasPrimary = $attachment->is_primary;

        if (Storage::disk('public')->exists($attachment->file_url)) {
            Storage::disk('public')->delete($attachment->file_url);
        }

        $attachment->delete();

        if ($wasPrimary && $project) {
            $nextPrimary = $project->attachments()->orderBy('sort_order')->first();
            if ($nextPrimary) {
                $nextPrimary->update(['is_primary' => true]);
            }
        }
    }
}
