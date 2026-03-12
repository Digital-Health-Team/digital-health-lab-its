<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow; // Gunakan ini agar instan
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class GlobalNotification extends Notification implements ShouldBroadcastNow
{
    use Queueable;

    public function __construct(
        public string $title,
        public string $message
    ) {
    }

    // Jalankan via database dan broadcast (Pusher)
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    // Format data untuk disimpan di tabel `notifications`
    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
        ];
    }

    // Format data yang dikirim ke Pusher
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'title' => $this->title,
            'message' => $this->message,
        ]);
    }
}
