<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class GeneralNotification extends Notification
{
    use Queueable;

    public array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    // 1. Kirim ke database dan siarkan (broadcast) via Pusher
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    // 2. Format untuk disimpan ke tabel `notifications`
    public function toDatabase(object $notifiable): array
    {
        return $this->data;
    }

    // 3. Format untuk dikirim ke JS (Browser)
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'title' => $this->data['title'] ?? 'Pemberitahuan',
            'message' => $this->data['message'] ?? '',
            'url' => $this->data['url'] ?? null,
        ]);
    }
}
