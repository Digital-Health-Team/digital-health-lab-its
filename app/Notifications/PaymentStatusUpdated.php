<?php

namespace App\Notifications;

use App\Models\BookingPayment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class PaymentStatusUpdated extends Notification
{
    use Queueable;

    public function __construct(
        public BookingPayment $payment,
        public bool $approved
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
        $payment = $this->payment->loadMissing('booking');
        $bookingId = $payment->service_booking_id;
        $amount = number_format($payment->amount, 0, ',', '.');

        return [
            'title' => $this->approved ? __('Payment Verified') : __('Payment Rejected'),
            'message' => $this->approved
                ? __('Your payment of Rp :amount for order #:id has been verified.', ['amount' => $amount, 'id' => $bookingId])
                : __('Your payment of Rp :amount for order #:id was rejected. Please re-upload a valid proof.', ['amount' => $amount, 'id' => $bookingId]),
            'icon' => $this->approved ? 'o-check-circle' : 'o-x-circle',
            'url' => route('orders.show', $bookingId),
            'booking_id' => $bookingId,
            'payment_id' => $payment->id,
            'approved' => $this->approved,
        ];
    }
}
