<?php

namespace App\Actions\Attendance\Admin;

use App\Models\Attendance;
use App\Models\JobdeskReport;
use App\Models\ReportDetail;
use Illuminate\Support\Facades\DB;

class CreateAttendanceAction
{
    public function execute(array $data)
    {
        return DB::transaction(function () use ($data) {
            $attendance = Attendance::create([
                'user_id' => $data['user_id'],
                'check_in' => $data['check_in'],
                'check_out' => $data['check_out'],
            ]);

            foreach ($data['reports'] as $item) {
                $report = JobdeskReport::create([
                    'attendance_id' => $attendance->id,
                    'jobdesk_id' => $item['jobdesk_id'],
                    'revision_thread_id' => $item['revision_thread_id'] ?? null,
                    'status_at_report' => $item['status_at_report'],
                ]);

                ReportDetail::create([
                    'jobdesk_report_id' => $report->id,
                    'content' => $item['content'],
                ]);

                if (!empty($item['new_files'])) {
                    foreach ($item['new_files'] as $file) {
                        $path = $file->store('reports', 'public');
                        $report->attachments()->create([
                            'file_path' => $path,
                            'file_name' => $file->getClientOriginalName(),
                            'file_type' => $file->getClientMimeType(),
                            'uploader_id' => auth()->id(),
                        ]);
                    }
                }
            }
            return $attendance;
        });
    }
}
