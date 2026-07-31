<?php

namespace App\Notifications;

use App\Models\ServiceProgressUpdate;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class OrderProgressUpdated extends Notification
{
    use Queueable;

    public function __construct(public ServiceProgressUpdate $progress) {}

    /**
     * `database` powers the bell icon; `broadcast` pushes it live over Echo.
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    /**
     * Payload stored in the `notifications` table (read by NavbarNotifications).
     */
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

    /**
     * @return array<string, mixed>
     */
    protected function payload(): array
    {
        $bookingId = $this->progress->service_booking_id;

        return [
            'title' => __('Order Progress Updated'),
            'message' => __(':percentage% — :status (Order #:id)', [
                'percentage' => $this->progress->percentage,
                'status' => $this->progress->status_label,
                'id' => $bookingId,
            ]),
            'icon' => 'o-rocket-launch',
            'url' => route('orders.show', $bookingId),
            'booking_id' => $bookingId,
            'status_label' => $this->progress->status_label,
            'percentage' => $this->progress->percentage,
        ];
    }
}
