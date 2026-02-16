<?php

namespace App\Actions\Announcement;

use App\DTOs\Announcement\AnnouncementData;
use App\Models\Announcement;
use Illuminate\Support\Facades\DB;

class CreateAnnouncementAction
{
    public function execute(AnnouncementData $data): Announcement
    {
        return DB::transaction(function () use ($data) {
            // 1. Create Announcement
            $announcement = Announcement::create([
                'title' => $data->title,
                'content' => $data->content,
                'is_published' => $data->is_published,
                'is_global' => $data->is_global, // Simpan flag global
                'created_by' => $data->created_by ?? auth()->id(),
            ]);

            // 2. Sync Recipients (Hanya jika TIDAK Global)
            // Jika Global, kita biarkan pivot kosong untuk menghemat space database
            if (!$data->is_global && !empty($data->recipient_ids)) {
                $announcement->recipients()->sync($data->recipient_ids);
            }

            return $announcement;
        });
    }
}
