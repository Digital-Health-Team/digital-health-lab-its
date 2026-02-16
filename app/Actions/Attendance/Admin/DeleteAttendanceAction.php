<?php

namespace App\Actions\Attendance\Admin;

use App\Models\Attendance;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DeleteAttendanceAction
{
    public function execute(Attendance $attendance)
    {
        DB::transaction(function () use ($attendance) {
            // Hapus file fisik dari storage
            foreach ($attendance->reports as $rep) {
                foreach ($rep->attachments as $att) {
                    Storage::disk('public')->delete($att->file_path);
                }
            }
            $attendance->delete();
        });
    }
}
