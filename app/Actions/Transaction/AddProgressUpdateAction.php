<?php

namespace App\Actions\Transaction;

use App\DTOs\Transaction\ProgressUpdateData;
use App\Enums\BookingStatus;
use App\Models\Attachment;
use App\Models\ServiceBooking;
use App\Models\ServiceProgressUpdate;
use App\Notifications\OrderProgressUpdated;

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

        if (! empty($data->attachments)) {
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

        // Sinkronisasi status Booking — hanya sub-tahap produksi yang valid,
        // dan tetap melalui guard transisi (gerbang DP, larangan cancel).
        $booking = ServiceBooking::find($data->service_booking_id);
        $target = BookingStatus::tryFrom($data->status_label);

        if ($target !== null && $target !== $booking->current_status) {
            app(TransitionBookingStatusAction::class)->execute($booking, $target);
        }

        // Beri tahu pemilik order via bell icon (database) + live (broadcast)
        $booking->user?->notify(new OrderProgressUpdated($progress));

        return $progress;
    }
}
