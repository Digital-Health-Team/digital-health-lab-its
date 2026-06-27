<?php

namespace App\Actions\Transaction;

use App\DTOs\Transaction\CreateBookingData;
use App\Models\ServiceBooking;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class CreateBookingAction
{
    public function execute(CreateBookingData $data): ServiceBooking
    {
        return DB::transaction(function () use ($data) {
            $transaction = Transaction::create([
                'user_id' => $data->user_id,
                'total_amount' => 0,
                'payment_status' => 'unpaid',
            ]);

            $booking = ServiceBooking::create([
                'transaction_id' => $transaction->id,
                'user_id' => $data->user_id,
                'service_id' => $data->service_id,
                'brief_description' => $data->brief_description ?? 'Pesanan manual via Admin',
                'reference_photo_path' => $data->reference_photo_path,
                'model_file_path' => $data->model_file_path,
                'material_preference' => $data->material_preference,
                'filament_width' => $data->filament_width,
                'scan_purpose' => $data->scan_purpose,
                'object_dimensions' => $data->object_dimensions,
                'current_status' => $data->status,
            ]);

            return $booking;
        });
    }
}
