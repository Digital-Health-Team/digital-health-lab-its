<?php

namespace App\Actions\Transaction;

use App\Enums\BookingStatus;
use App\Models\ServiceBooking;
use Illuminate\Support\Facades\DB;

class SetBookingPriceAction
{
    public const DP_TERMIN_NAME = 'Down Payment (30%)';

    public const DP_RATE = 0.30;

    /**
     * Set the estimated price for a booking and enforce the mandatory
     * 30% down-payment termin. The booking moves to Awaiting DP; production
     * is unlocked only once that termin is verified.
     */
    public function execute(ServiceBooking $booking, int $estimatedPrice): ServiceBooking
    {
        return DB::transaction(function () use ($booking, $estimatedPrice) {
            $booking->update(['agreed_price' => $estimatedPrice]);

            if ($booking->current_status !== BookingStatus::AwaitingDp) {
                app(TransitionBookingStatusAction::class)->execute($booking, BookingStatus::AwaitingDp);
            }

            $booking->transaction?->update(['total_amount' => $estimatedPrice]);

            $dpAmount = (int) round($estimatedPrice * self::DP_RATE);

            $dp = $booking->payments()->firstOrCreate(
                ['termin_name' => self::DP_TERMIN_NAME],
                ['amount' => $dpAmount, 'status' => 'pending'],
            );

            // Re-pricing before the customer acts adjusts the DP; once the
            // proof is uploaded or verified the termin is locked.
            if ($dp->status === 'pending' && $dp->amount !== $dpAmount) {
                $dp->update(['amount' => $dpAmount]);
            }

            return $booking->refresh();
        });
    }
}
