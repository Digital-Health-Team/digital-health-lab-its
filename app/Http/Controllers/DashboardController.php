<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\OpenSourceProject;
use App\Models\Product;
use App\Models\Service;
use Illuminate\Support\Facades\Storage;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $products = Product::where('is_active', true)
            ->with([
                'attachments' => fn ($q) => $q->where('is_primary', true),
                'creator:id,name',
            ])
            ->latest('id')
            ->take(6)
            ->get()
            ->map(fn ($p) => [
                'id' => (string) $p->id,
                'title' => $p->name,
                'priceLabel' => self::formatPrice($p->price_min, $p->price_max),
                'coverUrl' => $p->attachments->first()?->file_url
                    ? Storage::disk('public')->url($p->attachments->first()->file_url)
                    : null,
                'rating' => null,
                'seller' => $p->creator?->name ?? 'IDIG Lab',
                'href' => '/shop/'.$p->id,
            ]);

        $services = Service::take(6)->get()
            ->map(fn ($s) => [
                'id' => (string) $s->id,
                'title' => $s->name,
                'priceLabel' => 'Rp '.number_format($s->base_price, 0, ',', '.'),
                'coverUrl' => null,
                'rating' => null,
                'seller' => 'IDIG Lab',
                'href' => '/services/'.$s->id,
            ]);

        $openSourceProjects = OpenSourceProject::where('status', 'approved')
            ->with(['attachments' => fn ($q) => $q->where('is_primary', true)])
            ->latest('id')
            ->take(6)
            ->get()
            ->map(fn ($p) => [
                'id' => (string) $p->id,
                'title' => $p->title,
                'coverUrl' => self::resolveCoverUrl($p->attachments->first()?->file_url),
                'category' => self::labelCategory($p->category),
                'href' => '/projects/'.$p->id,
                'publishedAt' => $p->created_at->toDateString(),
            ]);

        $activeEventModel = Event::where('is_active', true)->withCount('teams')->first();
        $activeEvent = $activeEventModel ? [
            'id' => $activeEventModel->id,
            'name' => $activeEventModel->name,
            'year' => $activeEventModel->year,
            'themeTitle' => $activeEventModel->theme_title,
            'teamsCount' => $activeEventModel->teams_count,
        ] : null;

        return inertia('Features/Dashboard/Pages/DashboardPage', compact('products', 'services', 'openSourceProjects', 'activeEvent'));
    }

    private static function formatPrice(int $min, int $max): string
    {
        $fmt = fn (int $n): string => 'Rp '.number_format($n, 0, ',', '.');

        return $min === $max ? $fmt($min) : $fmt($min).' – '.$fmt($max);
    }

    private static function resolveCoverUrl(?string $url): ?string
    {
        if ($url === null) {
            return null;
        }

        if (str_starts_with($url, 'http')) {
            return $url;
        }

        return Storage::disk('public')->url($url);
    }

    private static function labelCategory(string $key): string
    {
        return match ($key) {
            '3d_model' => '3D Model',
            'iot_system' => 'IoT System',
            'medical_device' => 'Medical Device',
            'software' => 'Software',
            default => $key,
        };
    }
}
