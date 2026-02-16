<?php

namespace App\Actions\Staff;

use App\Models\Attendance;
use Illuminate\Support\Facades\Storage;

class SubmitCheckInAction
{
    public function execute(string $photoBase64): void
    {
        // 1. Decode & Simpan Selfie
        $imageName = 'checkin_' . auth()->id() . '_' . time() . '.webp';
        $path = 'selfies/' . $imageName;

        // Bersihkan string base64
        $image = str_replace('data:image/webp;base64,', '', $photoBase64);
        $image = str_replace(' ', '+', $image);

        Storage::disk('public')->put($path, base64_decode($image));

        // 2. Create Attendance Record
        Attendance::create([
            'user_id' => auth()->id(),
            'check_in' => now(),
            'selfie_check_in' => $path,
        ]);
    }
}
