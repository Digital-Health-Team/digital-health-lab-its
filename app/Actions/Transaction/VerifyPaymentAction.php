<?php

namespace App\Actions\Transaction;

use App\Models\BookingPayment;
use App\Notifications\PaymentStatusUpdated;

class VerifyPaymentAction
{
    /**
     * Approve or reject an uploaded payment termin.
     */
    public function execute(BookingPayment $payment, bool $approved): BookingPayment
    {
        $payment->update([
            'status' => $approved ? 'paid' : 'rejected',
            'paid_at' => $approved ? now() : null,
            'verified_by' => auth()->id(),
        ]);

        $payment->load('booking.user');
        $payment->booking->user?->notify(new PaymentStatusUpdated($payment, $approved));

        return $payment;
    }
}
