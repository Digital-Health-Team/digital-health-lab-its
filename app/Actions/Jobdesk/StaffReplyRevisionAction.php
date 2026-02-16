<?php

namespace App\Actions\Jobdesk;

use App\Models\Jobdesk;
use App\Models\RevisionThread;
use Illuminate\Support\Facades\DB;

class StaffReplyRevisionAction
{
    public function execute(Jobdesk $jobdesk, int $userId, string $content, array $files = [], bool $markAsFixed = false)
    {
        return DB::transaction(function () use ($jobdesk, $userId, $content, $files, $markAsFixed) {

            // 1. Buat Thread Balasan
            $thread = RevisionThread::create([
                'jobdesk_id' => $jobdesk->id,
                'user_id' => $userId,
                'content' => $content,
                'is_staff_reply' => true, // Menandakan ini dari Staff
            ]);

            // 2. Upload Attachments
            foreach ($files as $file) {
                $path = $file->store('revisions', 'public');
                $thread->attachments()->create([
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'file_type' => $file->getClientMimeType(),
                    'uploader_id' => $userId,
                ]);
            }

            // 3. Update Status Jobdesk
            if ($markAsFixed) {
                // Jika Staff menandai selesai, status kembali ke 'review' (Menunggu Approval PM)
                $jobdesk->update(['status' => 'review']);
            } else {
                // Jika hanya chat biasa, pastikan status tetap 'revision' atau 'on_progress'
                // Opsional: biarkan status apa adanya
            }

            return $thread;
        });
    }
}
