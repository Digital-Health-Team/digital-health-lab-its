<?php

namespace App\Actions\Transaction;

use App\DTOs\Transaction\SlicerCalculationData;
use App\Models\ServiceBooking;

class UpdateBookingCalculationAction
{
    public function execute(SlicerCalculationData $data): ServiceBooking
    {
        $booking = ServiceBooking::findOrFail($data->booking_id);

        $booking->update([
            'slicer_weight_grams' => $data->slicer_weight_grams,
            'slicer_print_time_minutes' => $data->slicer_print_time_minutes,
            'agreed_price' => $data->final_price, // <--- Sesuai DB
            'current_status' => 'processing', // <--- Sesuai DB
        ]);

        return $booking;
    }
}
