<?php

namespace App\Actions\Transaction;

use App\DTOs\Transaction\SendMessageData;
use App\Events\BookingMessageSent;
use App\Models\BookingMessage;

class SendBookingMessageAction
{
    public function execute(SendMessageData $data): BookingMessage
    {
        $message = BookingMessage::create([
            'service_booking_id' => $data->service_booking_id,
            'sender_id' => auth()->id(),
            'body' => $data->body,
        ]);

        broadcast(new BookingMessageSent($message->load('sender')))->toOthers();

        return $message;
    }
}
