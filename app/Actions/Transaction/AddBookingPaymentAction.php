<?php

namespace App\Actions\Transaction;

use App\DTOs\Transaction\BookingPaymentData;
use App\Models\BookingPayment;

class AddBookingPaymentAction
{
    public function execute(BookingPaymentData $data): BookingPayment
    {
        return BookingPayment::create([
            'service_booking_id' => $data->service_booking_id,
            'termin_name' => $data->termin_name,
            'amount' => $data->amount,
            'status' => 'pending',
        ]);
    }
}
