<?php

namespace App\Actions\PM\Jobdesk;

use App\Models\Jobdesk;
use App\Models\RevisionThread;
use Illuminate\Support\Facades\DB;

class ReviewJobdeskAction
{
    public function execute(Jobdesk $jobdesk, string $decision, ?string $note = null): void
    {
        DB::transaction(function () use ($jobdesk, $decision, $note) {
            if ($decision === 'approve') {
                $jobdesk->update([
                    'status' => 'approved',
                    'completed_at' => now(),
                ]);

                // Finalisasi KPI: Jika approved tapi lateness_minutes > 0, sistem sudah mencatatnya saat staff submit.
            } elseif ($decision === 'revision') {
                $jobdesk->update(['status' => 'revision']);

                // Buat Thread Revisi
                RevisionThread::create([
                    'jobdesk_id' => $jobdesk->id,
                    'user_id' => auth()->id(), // PM
                    'content' => $note ?? 'Silakan direvisi sesuai arahan.',
                    'is_staff_reply' => false,
                ]);
            } elseif ($decision === 'reject') {
                // Opsional: Reset progress atau tandai reject
                $jobdesk->update(['status' => 'pending']); // Kembalikan ke pending atau status khusus 'rejected'
            }
        });
    }
}
