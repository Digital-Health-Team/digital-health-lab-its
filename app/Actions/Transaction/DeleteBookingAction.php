<?php

namespace App\Actions\Transaction;

use App\Models\ServiceBooking;
use Illuminate\Support\Facades\DB;

class DeleteBookingAction
{
    public function execute(ServiceBooking $booking): void
    {
        DB::transaction(function () use ($booking) {
            $transaction = $booking->transaction;

            // Hapus file attachment dari progress update jika ada (opsional, tergantung preferensi)

            // Hapus booking
            $booking->delete();

            // Jika transaksi induk tidak punya booking lain, hapus juga transaksinya
            if ($transaction && $transaction->serviceBookings()->count() === 0) {
                $transaction->delete();
            }
        });
    }
}
