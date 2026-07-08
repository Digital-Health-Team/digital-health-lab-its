<?php

namespace App\Actions\Warehouse;

use App\Models\ServiceBooking;
use App\Models\User;
use App\Notifications\MaterialVerified;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;

class VerifyBookingMaterialAction
{
    /**
     * Warehouse admin confirms materials are available for the order,
     * unlocking the transition past check_material. Clears any prior flag.
     */
    public function execute(ServiceBooking $booking, int $verifiedBy): ServiceBooking
    {
        if (! $booking->current_status->isAtOrBeforeMaterialCheck()) {
            throw ValidationException::withMessages([
                'booking' => __('This order is already past the material check stage.'),
            ]);
        }

        $booking->update([
            'material_verified_at' => now(),
            'material_verified_by' => $verifiedBy,
            'material_flagged_at' => null,
            'material_flag_note' => null,
        ]);

        Notification::send(
            User::whereIn('role_id', [1, 2])->get(),
            new MaterialVerified($booking)
        );

        return $booking;
    }
}
