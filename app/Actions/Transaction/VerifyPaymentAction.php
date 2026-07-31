<?php

namespace App\Actions\Transaction;

use App\Enums\BookingStatus;
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

        // Production starts only after the mandatory DP is verified.
        if ($approved
            && $payment->termin_name === SetBookingPriceAction::DP_TERMIN_NAME
            && $payment->booking->current_status === BookingStatus::AwaitingDp) {
            $payment->booking->update(['current_status' => BookingStatus::Printing]);
        }

        $payment->booking->user?->notify(new PaymentStatusUpdated($payment, $approved));

        return $payment;
    }
}
