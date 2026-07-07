<?php

namespace App\Actions\Transaction;

use App\DTOs\Transaction\UpdateBookingData;
use App\Enums\BookingStatus;
use App\Models\ServiceBooking;
use Illuminate\Support\Facades\DB;

class UpdateBookingAction
{
    public function execute(ServiceBooking $booking, UpdateBookingData $data): ServiceBooking
    {
        DB::transaction(function () use ($booking, $data) {
            $booking->update([
                'service_id' => $data->service_id,
            ]);

            // Status changes go through the guarded transition (DP gate,
            // no cancellation once production has started).
            $target = BookingStatus::from($data->status);
            if ($target !== $booking->current_status) {
                app(TransitionBookingStatusAction::class)->execute($booking, $target);
            }
        });

        return $booking;
    }
}
