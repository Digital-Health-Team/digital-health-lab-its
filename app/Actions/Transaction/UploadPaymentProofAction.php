<?php

namespace App\Actions\Transaction;

use App\Models\BookingPayment;
use Illuminate\Http\UploadedFile;

class UploadPaymentProofAction
{
    /**
     * Store transfer proof for a termin and flag it for admin verification.
     * Admins may also call this on the user's behalf.
     */
    public function execute(BookingPayment $payment, UploadedFile $proof): BookingPayment
    {
        $path = $proof->store('payments', 'public');

        $payment->update([
            'payment_proof' => $path,
            'status' => 'awaiting_verification',
        ]);

        return $payment;
    }
}
