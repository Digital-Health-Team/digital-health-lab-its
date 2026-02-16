<?php

namespace App\Actions\Jobdesk;

use App\Models\Jobdesk;
use App\Models\JobdeskRevision;

class ReviseJobdeskAction
{
    public function execute(Jobdesk $jobdesk, int $pmId, string $notes): void
    {
        // 1. Update Status Jobdesk menjadi 'revision'
        $jobdesk->update([
            'status' => 'revision'
        ]);

        // 2. Simpan History Revisi ke tabel 'jobdesk_revisions'
        JobdeskRevision::create([
            'jobdesk_id' => $jobdesk->id,
            'pm_id' => $pmId, // ID PM yang dipilih oleh Super Admin
            'notes' => $notes,
            // 'created_at' otomatis terisi timestamp
        ]);
    }
}
