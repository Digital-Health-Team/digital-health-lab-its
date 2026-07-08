<?php

namespace App\Policies;

use App\Models\ServiceBooking;
use App\Models\User;

class ServiceBookingPolicy
{
    /**
     * Cancelling is only possible before production starts — for anyone.
     * Regular users may additionally only cancel their own booking.
     */
    public function cancel(User $user, ServiceBooking $booking): bool
    {
        if (! $booking->current_status->isCancellable()) {
            return false;
        }

        return $user->isAdmin() || $booking->user_id === $user->id;
    }

    /**
     * Material availability verification (verify or flag) — warehouse admins
     * only, and only while the order is still at the material check stage.
     */
    public function verifyMaterial(User $user, ServiceBooking $booking): bool
    {
        return in_array($user->activeRoleName(), ['super_admin', 'admin_gudang'])
            && $booking->current_status->isAtOrBeforeMaterialCheck();
    }
}
