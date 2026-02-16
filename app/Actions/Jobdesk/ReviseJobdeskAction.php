<?php

namespace App\Actions\Jobdesk;

use App\Models\Jobdesk;
use App\Models\RevisionThread;
use Illuminate\Support\Facades\Storage;

class ReviseJobdeskAction
{
    public function execute(Jobdesk $jobdesk, int $userId, string $content, array $files = [], ?string $deadlineRevision = null): void
    {
        // 1. Update Status Jobdesk & Deadline Revisi (jika ada)
        $updateData = ['status' => 'revision'];
        if ($deadlineRevision) {
            $updateData['deadline_revision'] = $deadlineRevision;
        }
        $jobdesk->update($updateData);

        // 2. Buat Thread Percakapan Baru
        $thread = RevisionThread::create([
            'jobdesk_id' => $jobdesk->id,
            'user_id' => $userId,
            'content' => $content,
            'is_staff_reply' => false, // Karena ini Action dari PM/Admin
        ]);

        // 3. Simpan Multiple Attachments
        foreach ($files as $file) {
            // Simpan ke storage (public/attachments)
            $path = $file->store('attachments', 'public');

            // Simpan ke database via Polymorphic Relation
            $thread->attachments()->create([
                'file_path' => $path,
                'file_name' => $file->getClientOriginalName(),
                'file_type' => $file->getClientMimeType(),
                'uploader_id' => $userId,
            ]);
        }
    }
}
