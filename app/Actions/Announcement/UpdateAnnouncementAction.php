<?php

namespace App\Actions\Announcement;

use App\DTOs\Announcement\AnnouncementData;
use App\Models\Announcement;
use Illuminate\Support\Facades\DB;

class UpdateAnnouncementAction
{
    public function execute(Announcement $announcement, AnnouncementData $data): bool
    {
        return DB::transaction(function () use ($announcement, $data) {
            // 1. Update Data Utama
            $updated = $announcement->update([
                'title' => $data->title,
                'content' => $data->content,
                'is_published' => $data->is_published,
                'is_global' => $data->is_global,
            ]);

            // 2. Update Pivot
            if ($data->is_global) {
                // Jika berubah jadi global, hapus semua specific recipients
                $announcement->recipients()->detach();
            } else {
                // Jika spesifik, sync ID baru
                $announcement->recipients()->sync($data->recipient_ids);
            }

            return $updated;
        });
    }
}
