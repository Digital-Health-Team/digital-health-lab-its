<?php

namespace App\Notifications;

use App\Models\BookingMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class NewChatMessage extends Notification
{
    use Queueable;

    public function __construct(
        public BookingMessage $message,
        public bool $recipientIsAdmin
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase(object $notifiable): array
    {
        return $this->payload();
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        $data = $this->payload();

        return new BroadcastMessage([
            'title' => $data['title'],
            'message' => $data['message'],
            'url' => $data['url'],
        ]);
    }

    protected function payload(): array
    {
        $bookingId = $this->message->service_booking_id;
        $preview = mb_strimwidth($this->message->body, 0, 100, '…');

        return [
            'title' => $this->recipientIsAdmin
                ? __('New message from customer')
                : __('New message on your order'),
            'message' => $preview,
            'icon' => 'o-chat-bubble-left-ellipsis',
            'url' => $this->recipientIsAdmin
                ? route('admin.order-center.show', $bookingId)
                : route('orders.show', $bookingId),
            'booking_id' => $bookingId,
            'message_id' => $this->message->id,
        ];
    }
}
