<?php

namespace App\Actions\Publication;

use App\Models\Publication;
use Illuminate\Support\Facades\Storage;

class DeletePublicationAction
{
    public function execute(Publication $publication): void
    {
        foreach (['thumbnail_path', 'pdf_path'] as $field) {
            if ($publication->$field && Storage::disk('public')->exists($publication->$field)) {
                Storage::disk('public')->delete($publication->$field);
            }
        }

        $publication->delete();
    }
}
