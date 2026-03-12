<?php

namespace App\Livewire;

use Livewire\Component;

class NavbarNotifications extends Component
{
    public $unreadCount = 0;
    public $notifications = [];
    public bool $showWelcomeModal = false;

    // Mendengarkan sinyal real-time agar memuat ulang data otomatis
    public function getListeners()
    {
        if (!auth()->check()) {
            return [];
        }

        // 1. Simpan ID ke dalam variabel terlebih dahulu
        $userId = auth()->id();

        // 2. Gunakan variabel tersebut ke dalam string dengan {$userId}
        return [
            "echo-private:App.Models.User.{$userId},.Illuminate\\Notifications\\Events\\BroadcastNotificationCreated" => 'loadNotifications',
        ];
    }

    public function mount()
    {
        $this->loadNotifications();

        // Munculkan modal otomatis SAAT BARU LOGIN jika ada notif yang belum dibaca
        if ($this->unreadCount > 0 && !session()->has('has_seen_welcome_notif')) {
            $this->showWelcomeModal = true;
            session()->put('has_seen_welcome_notif', true);
        }
    }

    public function loadNotifications()
    {
        $user = auth()->user();
        if ($user) {
            $this->unreadCount = $user->unreadNotifications()->count();
            $this->notifications = $user->notifications()->take(10)->get();
        }
    }

    public function markAsRead($id, $url = null)
    {
        $notification = auth()->user()->notifications()->find($id);
        if ($notification) {
            $notification->markAsRead();
            $this->loadNotifications();
        }
        if ($url)
            return redirect($url);
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        $this->loadNotifications();
        $this->showWelcomeModal = false;
    }

    public function render()
    {
        return view('components.navbar-notifications');
    }
}
