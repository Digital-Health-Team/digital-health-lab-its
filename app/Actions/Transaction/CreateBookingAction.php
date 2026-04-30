<?php

namespace App\Actions\Transaction;

use App\DTOs\Transaction\CreateBookingData;
use App\Models\Transaction;
use App\Models\ServiceBooking;
use Illuminate\Support\Facades\DB;

class CreateBookingAction
{
    public function execute(CreateBookingData $data): ServiceBooking
    {
        return DB::transaction(function () use ($data) {
            // 1. Buat Induk Transaksi
            $transaction = Transaction::create([
                'user_id' => $data->user_id,
                'total_amount' => 0,
                'payment_status' => 'unpaid', // <--- SOLUSI ERROR NYA DI SINI
            ]);

            // 2. Buat Detail Layanan
            $booking = ServiceBooking::create([
                'transaction_id' => $transaction->id,
                'user_id' => $data->user_id, // <--- Relasi ke user
                'service_id' => $data->service_id,
                'brief_description' => 'Pesanan manual via Admin',
                'current_status' => $data->status, // <--- Sesuai DB
            ]);

            return $booking;
        });
    }
}
