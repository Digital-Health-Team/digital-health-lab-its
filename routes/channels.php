<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('booking.{bookingId}', function ($user, $bookingId) {
    $booking = \App\Models\ServiceBooking::find($bookingId);

    return $booking && ((int) $user->id === (int) $booking->user_id || $user->isAdmin());
});
