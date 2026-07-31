<?php

namespace App\Actions\Warehouse;

use App\Models\ServiceBooking;
use App\Models\User;
use App\Notifications\MaterialUnavailable;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;

class FlagBookingMaterialAction
{
    /**
     * Warehouse admin flags the order as blocked on materials. The order
     * stays in its current stage; lab admins are notified with the note.
     */
    public function execute(ServiceBooking $booking, string $note, int $flaggedBy): ServiceBooking
    {
        if (! $booking->current_status->isAtOrBeforeMaterialCheck()) {
            throw ValidationException::withMessages([
                'booking' => __('This order is already past the material check stage.'),
            ]);
        }

        $booking->update([
            'material_flagged_at' => now(),
            'material_flag_note' => $note,
            'material_verified_at' => null,
            'material_verified_by' => null,
        ]);

        Notification::send(
            User::whereIn('role_id', [1, 2])->get(),
            new MaterialUnavailable($booking)
        );

        return $booking;
    }
}
