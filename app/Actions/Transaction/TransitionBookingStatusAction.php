<?php

namespace App\Actions\Transaction;

use App\Enums\BookingStatus;
use App\Models\ServiceBooking;
use Illuminate\Validation\ValidationException;

class TransitionBookingStatusAction
{
    /**
     * Single choke point for booking status changes. Enforces:
     * - materials must be verified by the warehouse before leaving check_material,
     * - production cannot start before the mandatory 30% DP is verified,
     * - a booking in production can no longer be cancelled.
     */
    public function execute(ServiceBooking $booking, BookingStatus $target): ServiceBooking
    {
        $current = $booking->current_status;

        // The current-status guard makes the gate non-retroactive: bookings
        // already past check_material are never blocked by it.
        if ($target->isBeyondMaterialCheck()
            && $current->isAtOrBeforeMaterialCheck()
            && ! $booking->isMaterialVerified()) {
            throw ValidationException::withMessages([
                'status' => __('Materials must be verified by the warehouse before this order can proceed.'),
            ]);
        }

        if ($target->isProduction() && ! $current->isProduction()) {
            $dpPaid = $booking->payments()
                ->where('termin_name', SetBookingPriceAction::DP_TERMIN_NAME)
                ->where('status', 'paid')
                ->exists();

            throw_unless($dpPaid, ValidationException::withMessages([
                'status' => __('Production cannot start before the 30% down payment is verified.'),
            ]));
        }

        if ($target === BookingStatus::Cancelled && ! $current->isCancellable()) {
            throw ValidationException::withMessages([
                'status' => __('This order has entered production and can no longer be cancelled.'),
            ]);
        }

        $booking->update(['current_status' => $target]);

        return $booking;
    }
}
