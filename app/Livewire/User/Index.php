<?php

namespace App\Livewire\User;

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Services\News\NewsService;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

#[Layout('layouts.app.layout')]
class Index extends Component
{
    public function render(NewsService $service)
    {
        $user = Auth::user();

        // 1. Ambil Statistik Global User (Total, Draft, Published, Views)
        // Kita reuse method getStats yg sudah ada, karena logic-nya sudah filter by author_id
        $stats = $service->getStats($user);

        // 2. Ambil 5 Berita Terbaru (Untuk tabel status)
        $recentNews = $service->getUserRecentNews($user->id);

        // 3. Ambil 5 Berita Terpopuler (Untuk sidebar)
        $popularNews = $service->getUserPopularNews($user->id);

        return view('livewire.user.index', [
            'stats' => $stats,
            'recentNews' => $recentNews,
            'popularNews' => $popularNews,
            'greeting' => $this->getGreeting(),
        ]);
    }

    private function getGreeting()
    {
        $hour = Carbon::now()->hour;
        if ($hour < 12)
            return 'Selamat Pagi';
        if ($hour < 15)
            return 'Selamat Siang';
        if ($hour < 18)
            return 'Selamat Sore';
        return 'Selamat Malam';
    }
}
