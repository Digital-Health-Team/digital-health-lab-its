<?php

namespace App\Actions\Staff;

use App\Models\Attendance;
use App\Models\Jobdesk;
use App\Models\JobdeskReport;
use App\Models\ReportDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SubmitCheckOutAction
{
    public function execute(Attendance $attendance, array $data): void
    {
        DB::transaction(function () use ($attendance, $data) {
            // 1. Simpan Selfie Check Out
            $imageName = 'checkout_' . auth()->id() . '_' . time() . '.webp';
            $path = 'selfies/' . $imageName;

            $image = str_replace('data:image/webp;base64,', '', $data['photo']);
            $image = str_replace(' ', '+', $image);
            Storage::disk('public')->put($path, base64_decode($image));

            // 2. Update Attendance
            $attendance->update([
                'check_out' => now(),
                'selfie_check_out' => $path,
            ]);

            // 3. Loop setiap Jobdesk yang dipilih
            foreach ($data['selected_jobdesks'] as $jobdeskId) {
                // Cek apakah jobdesk ini ditandai selesai (100%)
                $isFinished = in_array($jobdeskId, $data['finished_jobdesks']);

                // A. Buat Report Header
                $report = JobdeskReport::create([
                    'attendance_id' => $attendance->id,
                    'jobdesk_id' => $jobdeskId,
                    'status_at_report' => $isFinished ? 'completed' : 'on_progress',
                ]);

                // B. Buat Detail Konten
                $report->details()->create([
                    'content' => $data['note'],
                ]);

                // C. Upload Bukti Kerja (File)
                if (!empty($data['attachments'])) {
                    foreach ($data['attachments'] as $file) {
                        $filePath = $file->store('reports/proofs', 'public');

                        // Pastikan relasi attachments() ada di model JobdeskReport (polymorphic)
                        $report->attachments()->create([
                            'file_path' => $filePath,
                            'file_name' => $file->getClientOriginalName(),
                            'file_type' => $file->getClientMimeType(),
                            'uploader_id' => auth()->id(),
                        ]);
                    }
                }

                // D. Update Status Master Jobdesk
                $jobdesk = Jobdesk::find($jobdeskId);
                if ($isFinished) {
                    $jobdesk->update(['status' => 'review', 'completed_at' => now()]);
                } else {
                    // Jika status masih pending, ubah jadi on_progress
                    if ($jobdesk->status == 'pending') {
                        $jobdesk->update(['status' => 'on_progress']);
                    }
                }
            }
        });
    }
}
