<?php

namespace App\Http\Controllers;

use App\Actions\Articles\FetchPubMedFeedAction;
use App\Models\Event;
use App\Models\OpenSourceProject;
use App\Models\Product;
use App\Models\Publication;
use App\Models\Service;
use App\Models\Training;
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
                'coverUrl' => self::resolveCoverUrl($p->attachments->first()?->file_url),
                'rating' => null,
                'seller' => $p->creator?->name ?? 'IDIG Lab',
                'href' => route('products.show', $p->id),
            ]);

        $services = Service::with([
            'attachments' => fn ($q) => $q->where('is_primary', true),
        ])
            ->take(6)
            ->get()
            ->map(fn ($s) => [
                'id' => (string) $s->id,
                'title' => $s->name,
                'priceLabel' => 'Rp '.number_format($s->base_price, 0, ',', '.'),
                'coverUrl' => self::resolveCoverUrl($s->attachments->first()?->file_url),
                'rating' => null,
                'seller' => 'IDIG Lab',
                'href' => route('services.show', $s->id),
                'description' => $s->description,
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

        $featuredPublications = Publication::where('is_featured', true)
            ->latest('published_at')
            ->take(6)
            ->get()
            ->map(fn ($p) => [
                'id' => (string) $p->id,
                'title' => $p->title,
                'coverUrl' => $p->thumbnail_url,
                'thumbnailUrl' => $p->thumbnail_url,
                'author' => $p->author,
                'viewCount' => $p->view_count,
                'status' => 'verified',
                'category' => $p->category,
                'href' => route('publications.show', $p->slug),
                'publishedAt' => $p->published_at?->toISOString(),
            ]);

        $trendingPublications = Publication::orderBy('view_count', 'desc')
            ->take(5)
            ->get()
            ->map(fn ($p) => [
                'id' => (string) $p->id,
                'title' => $p->title,
                'thumbnailUrl' => $p->thumbnail_url,
                'author' => $p->author,
                'publishedAt' => $p->published_at?->toISOString(),
                'abstract' => $p->abstract,
                'tags' => array_values(array_filter([
                    $p->category,
                    $p->is_free_access ? 'Free Access' : null,
                    ...array_slice($p->keywords ?? [], 0, 2),
                ])),
                'href' => route('publications.show', $p->slug),
            ]);

        $pubmedArticles = (new FetchPubMedFeedAction)->execute();

        $trainings = Training::where('is_active', true)
            ->withCount('registrations')
            ->latest('id')
            ->take(12)
            ->get()
            ->map(fn ($t) => [
                'id' => $t->id,
                'slug' => $t->slug,
                'href' => route('training.show', $t->slug),
                'title' => $t->title,
                'thumbnailUrl' => $t->thumbnail_url,
                'level' => $t->level,
                'duration' => $t->duration,
                'rating' => (float) $t->rating,
                'ratingCount' => $t->rating_count,
                'category' => $t->category,
                'price' => $t->price,
                'isPaid' => $t->is_paid,
                'staffPick' => $t->is_featured,
                'students' => $this->formatCount($t->registrations_count),
                'instructor' => [
                    'name' => $t->instructor_name,
                    'avatarUrl' => $t->instructor_avatar_url,
                    'verified' => true,
                ],
            ]);

        return inertia('Features/Dashboard/Pages/DashboardPage', compact(
            'products',
            'services',
            'openSourceProjects',
            'activeEvent',
            'featuredPublications',
            'trendingPublications',
            'pubmedArticles',
            'trainings',
        ));
    }

    private function formatCount(int $n): string
    {
        if ($n >= 1000) {
            $k = $n / 1000;

            return rtrim(rtrim(number_format($k, 1), '0'), '.').'k';
        }

        return (string) $n;
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

        if (str_starts_with($url, 'assets/')) {
            return '/'.$url;
        }

        if (str_starts_with($url, '/assets/')) {
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
