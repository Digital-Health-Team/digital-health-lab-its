<?php

namespace App\Actions\Transaction;

use App\DTOs\Transaction\UpdateBookingData;
use App\Models\ServiceBooking;
use Illuminate\Support\Facades\DB;

class UpdateBookingAction
{
    public function execute(ServiceBooking $booking, UpdateBookingData $data): ServiceBooking
    {
        DB::transaction(function () use ($booking, $data) {
            $booking->update([
                'service_id' => $data->service_id,
                'current_status' => $data->status,
                'final_price' => $data->final_price,
            ]);

            // Update status transaksi induk mengikuti status booking
            if ($booking->transaction) {
                $booking->transaction->update(['status' => $data->status]);
            }
        });

        return $booking;
    }
}
