<?php

namespace App\Actions\Staff;

use App\Models\Attendance;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SubmitCheckInAction
{
    // Terima array data yang berisi foto dan koordinat
    public function execute(array $data): void
    {
        DB::transaction(function () use ($data) {
            // 1. Create Attendance dengan Koordinat
            $attendance = Attendance::create([
                'user_id' => auth()->id(),
                'check_in' => now(),
                'check_in_latitude' => $data['latitude'] ?? null,
                'check_in_longitude' => $data['longitude'] ?? null,
            ]);

            // 2. Simpan Foto (Sama seperti sebelumnya)
            $imageName = 'checkin_' . $attendance->id . '_' . time() . '.webp';
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
        });
    }
}
