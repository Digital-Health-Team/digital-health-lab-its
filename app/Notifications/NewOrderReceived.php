<?php

namespace App\Notifications;

use App\Models\ServiceBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class NewOrderReceived extends Notification
{
    use Queueable;

    public function __construct(public ServiceBooking $booking) {}

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
        $booking = $this->booking->loadMissing(['user', 'service']);

        return [
            'title' => __('New Order Received'),
            'message' => __('Order #:id from :name for :service', [
                'id' => $booking->id,
                'name' => $booking->user?->name ?? __('Unknown'),
                'service' => $booking->service?->name ?? __('Unknown Service'),
            ]),
            'icon' => 'o-shopping-bag',
            'url' => route('admin.order-center.show', $booking->id),
            'booking_id' => $booking->id,
        ];
    }
}
