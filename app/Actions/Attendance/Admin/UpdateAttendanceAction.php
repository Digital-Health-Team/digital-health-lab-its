<?php

namespace App\Actions\Attendance\Admin;

use App\Models\Attendance;
use App\Models\JobdeskReport;
use App\Models\ReportDetail;
use Illuminate\Support\Facades\DB;

class UpdateAttendanceAction
{
    public function execute(Attendance $attendance, array $data)
    {
        return DB::transaction(function () use ($attendance, $data) {
            $attendance->update([
                'user_id' => $data['user_id'],
                'check_in' => $data['check_in'],
                'check_out' => $data['check_out'],
            ]);

            // Sync Reports: Hapus yang tidak ada di form, Update/Create sisanya
            $existingIds = $attendance->reports()->pluck('id')->toArray();
            $submittedIds = array_filter(array_column($data['reports'], 'id'));

            $toDelete = array_diff($existingIds, $submittedIds);
            if (!empty($toDelete))
                JobdeskReport::destroy($toDelete);

            foreach ($data['reports'] as $item) {
                $report = JobdeskReport::updateOrCreate(
                    ['id' => $item['id'] ?? null],
                    [
                        'attendance_id' => $attendance->id,
                        'jobdesk_id' => $item['jobdesk_id'],
                        'revision_thread_id' => $item['revision_thread_id'] ?? null,
                        'status_at_report' => $item['status_at_report'],
                    ]
                );

                ReportDetail::updateOrCreate(
                    ['jobdesk_report_id' => $report->id],
                    ['content' => $item['content']]
                );

                // Upload File Baru (File lama dibiarkan)
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
