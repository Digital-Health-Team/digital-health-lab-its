<?php

namespace App\Actions\Staff;

use App\Models\Attendance;
use App\Models\Jobdesk;
use App\Models\JobdeskReport;
use App\Models\RevisionThread;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class SubmitCheckOutAction
{
    public function execute(Attendance $attendance, array $data): void
    {
        DB::transaction(function () use ($attendance, $data) {
            // 1. Simpan Foto & Update Attendance (Sama seperti sebelumnya)
            $imageName = 'checkout_' . $attendance->id . '_' . time() . '.webp';
            $path = 'selfies/' . $imageName;

            $image = str_replace('data:image/webp;base64,', '', $data['photo']);
            $image = str_replace(' ', '+', $image);
            Storage::disk('public')->put($path, base64_decode($image));

            $attendance->attachments()->create([
                'file_path' => $path,
                'file_name' => $imageName,
                'file_type' => 'image/webp',
                'uploader_id' => auth()->id(),
            ]);

            $attendance->update([
                'check_out' => now(),
                'check_out_latitude' => $data['latitude'] ?? null,
                'check_out_longitude' => $data['longitude'] ?? null,
            ]);

            // 2. Proses Tugas
            foreach ($data['selected_jobdesks'] as $jobdeskId) {
                $isFinished = in_array($jobdeskId, $data['finished_jobdesks']);

                $report = JobdeskReport::create([
                    'attendance_id' => $attendance->id,
                    'jobdesk_id' => $jobdeskId,
                    'status_at_report' => $isFinished ? 'review' : 'on_progress', // Jika finish, status jadi review
                ]);

                $report->details()->create(['content' => $data['note']]);

                if (!empty($data['attachments'])) {
                    foreach ($data['attachments'] as $file) {
                        $filePath = $file->store('reports/proofs', 'public');
                        $report->attachments()->create([
                            'file_path' => $filePath,
                            'file_name' => $file->getClientOriginalName(),
                            'file_type' => $file->getClientMimeType(),
                            'uploader_id' => auth()->id(),
                        ]);
                    }
                }

                $jobdesk = Jobdesk::find($jobdeskId);

                if ($isFinished) {
                    // [LOGIC BARU: HITUNG KETERLAMBATAN]
                    $now = now();
                    $deadline = $jobdesk->deadline_task;
                    $latenessMinutes = 0;

                    if ($deadline && $now->gt($deadline)) {
                        $latenessMinutes = $deadline->diffInMinutes($now);
                    }

                    $jobdesk->update([
                        'status' => 'review',
                        'submitted_at' => $now, // Catat waktu submit staf
                        'lateness_minutes' => $latenessMinutes // Catat durasi telat untuk KPI
                    ]);

                    RevisionThread::create([
                        'jobdesk_id' => $jobdeskId,
                        'user_id' => auth()->id(),
                        'content' => "Task marked as DONE via Checkout. " . ($latenessMinutes > 0 ? "[LATE SUBMISSION: {$jobdesk->late_duration_text}]" : "") . "\nNote: " . $data['note'],
                        'is_staff_reply' => true,
                    ]);
                } else {
                    if ($jobdesk->status == 'pending')
                        $jobdesk->update(['status' => 'on_progress']);
                }
            }
        });
    }
}
d
