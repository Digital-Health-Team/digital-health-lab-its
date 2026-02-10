<?php

namespace App\Livewire\Admin;

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\News;
use App\Models\User;
use App\Models\Category;
use Carbon\Carbon;

#[Layout('layouts.app.layout')]
class Index extends Component
{
    public function render()
    {
        // 1. Statistik Cards
        $stats = [
            'total_news' => News::count(),
            'published' => News::where('status', 'published')->count(),
            'draft' => News::where('status', 'draft')->count(),
            'total_views' => News::sum('views_count'), // Total views semua berita
            'total_users' => User::count(),
        ];

        // 2. Berita Terbaru (5 item) - Eager load author & category biar ringan
        $latestNews = News::with(['author', 'category'])
            ->latest()
            ->take(5)
            ->get();

        // 3. Berita Paling Populer (Top 5 by Views)
        $popularNews = News::where('status', 'published')
            ->orderBy('views_count', 'desc')
            ->take(5)
            ->get();

        // 4. User Terbaru (5 item)
        $latestUsers = User::latest()
            ->take(5)
            ->get();

        return view('livewire.admin.index', [
            'stats' => $stats,
            'latestNews' => $latestNews,
            'popularNews' => $popularNews,
            'latestUsers' => $latestUsers,
            'greeting' => $this->getGreeting(),
        ]);
    }

    // Helper sederhana untuk ucapan selamat
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
