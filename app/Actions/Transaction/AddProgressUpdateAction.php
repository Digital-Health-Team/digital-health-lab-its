<?php

namespace App\Actions\Transaction;

use App\DTOs\Transaction\ProgressUpdateData;
use App\Models\ServiceProgressUpdate;
use App\Models\ServiceBooking;
use App\Models\Attachment;

class AddProgressUpdateAction
{
    public function execute(ProgressUpdateData $data): ServiceProgressUpdate
    {
        $progress = ServiceProgressUpdate::create([
            'service_booking_id' => $data->service_booking_id,
            'status_label' => $data->status_label,
            'percentage' => $data->percentage,
            'notes' => $data->notes,
            'updated_by' => auth()->id(),
        ]);

        if (!empty($data->attachments)) {
            foreach ($data->attachments as $index => $file) {
                $path = $file->store('progress_updates', 'public');
                Attachment::create([
                    'attachable_type' => ServiceProgressUpdate::class,
                    'attachable_id' => $progress->id,
                    'file_url' => $path,
                    'file_type' => $file->getClientMimeType(),
                    'is_primary' => $index === 0,
                    'uploaded_by' => auth()->id(),
                ]);
            }
        }

        // Sinkronisasi status Booking
        ServiceBooking::where('id', $data->service_booking_id)->update([
            'current_status' => $data->status_label
        ]);

        return $progress;
    }
}
