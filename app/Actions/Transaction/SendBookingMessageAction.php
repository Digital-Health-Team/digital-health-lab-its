<?php

namespace App\Actions\Transaction;

use App\DTOs\Transaction\SendMessageData;
use App\Events\BookingMessageSent;
use App\Models\BookingMessage;
use App\Models\User;
use App\Notifications\NewChatMessage;
use Illuminate\Support\Facades\Notification;

class SendBookingMessageAction
{
    public function execute(SendMessageData $data): BookingMessage
    {
        $message = BookingMessage::create([
            'service_booking_id' => $data->service_booking_id,
            'sender_id' => auth()->id(),
            'body' => $data->body,
        ]);

        $message->load(['sender', 'booking.user']);
        broadcast(new BookingMessageSent($message))->toOthers();

        $sender = $message->sender;
        if ($sender?->isAdmin()) {
            $message->booking->user?->notify(new NewChatMessage($message, recipientIsAdmin: false));
        } else {
            $admins = User::whereIn('role_id', [1, 2])->get();
            Notification::send($admins, new NewChatMessage($message, recipientIsAdmin: true));
        }

        return $message;
    }
}
