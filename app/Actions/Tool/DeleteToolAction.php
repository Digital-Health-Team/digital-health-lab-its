<?php

namespace App\Actions\Tool;

use App\Models\Tool;
use Illuminate\Support\Facades\Storage;

class DeleteToolAction
{
    public function execute(Tool $tool): void
    {
        $tool->loadMissing('attachments');

        foreach ($tool->attachments as $attachment) {
            $relativePath = str_replace(
                Storage::disk('public')->url(''),
                '',
                $attachment->file_url
            );
            Storage::disk('public')->delete($relativePath);
            $attachment->delete();
        }

        $tool->delete();
    }
}
