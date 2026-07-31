<?php

namespace App\Http\Controllers;

use App\Models\OpenSourceProject;
use App\Models\Product;
use App\Models\Publication;
use App\Models\Service;
use App\Models\Training;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class GlobalSearchController extends Controller
{
    private const MAX_PER_TYPE_DROPDOWN = 4;

    private const MAX_PER_TYPE_PAGE = 20;

    public function index(Request $request): JsonResponse|InertiaResponse
    {
        $query = trim($request->get('q', ''));

        if ($request->wantsJson()) {
            if (mb_strlen($query) < 2) {
                return response()->json(['query' => $query, 'results' => []]);
            }

            return response()->json([
                'query' => $query,
                'results' => $this->searchAll($query, self::MAX_PER_TYPE_DROPDOWN),
            ]);
        }

        return Inertia::render('Features/Search/Pages/SearchPage', [
            'query' => $query,
            'results' => mb_strlen($query) >= 2
                ? $this->searchAll($query, self::MAX_PER_TYPE_PAGE)
                : [],
        ]);
    }

    private function searchAll(string $query, int $perType): array
    {
        return array_merge(
            $this->searchTrainings($query, $perType),
            $this->searchPublications($query, $perType),
            $this->searchProjects($query, $perType),
            $this->searchProducts($query, $perType),
            $this->searchServices($query, $perType),
        );
    }

    private function searchTrainings(string $query, int $limit): array
    {
        return Training::where(function ($q) use ($query) {
            $q->where('title', 'like', "%{$query}%")
                ->orWhere('subtitle', 'like', "%{$query}%")
                ->orWhere('description', 'like', "%{$query}%")
                ->orWhere('instructor_name', 'like', "%{$query}%");
        })
            ->latest()
            ->take($limit)
            ->get()
            ->map(fn ($t) => [
                'type' => 'training',
                'title' => $t->localized('title'),
                'subtitle' => $t->localized('subtitle') ?? $t->instructor_name ?? '',
                'href' => route('training.show', $t->slug),
            ])
            ->all();
    }

    private function searchPublications(string $query, int $limit): array
    {
        return Publication::where(function ($q) use ($query) {
            $q->where('title', 'like', "%{$query}%")
                ->orWhere('author', 'like', "%{$query}%")
                ->orWhere('abstract', 'like', "%{$query}%");
        })
            ->latest('published_at')
            ->take($limit)
            ->get()
            ->map(fn ($p) => [
                'type' => 'publication',
                'title' => $p->localized('title'),
                'subtitle' => $p->author.' · '.$p->category,
                'href' => route('publications.show', $p->slug),
            ])
            ->all();
    }

    private function searchProjects(string $query, int $limit): array
    {
        return OpenSourceProject::where('status', 'approved')
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                    ->orWhere('caption', 'like', "%{$query}%");
            })
            ->latest()
            ->take($limit)
            ->get()
            ->map(fn ($p) => [
                'type' => 'project',
                'title' => $p->localized('title'),
                'subtitle' => $p->localized('caption') ?? '',
                'href' => route('projects.show', $p->id),
            ])
            ->all();
    }

    private function searchProducts(string $query, int $limit): array
    {
        return Product::where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%");
            })
            ->with(['attachments' => fn ($q) => $q->where('is_primary', true)])
            ->take($limit)
            ->get()
            ->map(fn ($p) => [
                'type' => 'product',
                'title' => $p->localized('name'),
                'subtitle' => self::formatPrice($p->price_min, $p->price_max),
                'href' => route('products.show', $p->id),
            ])
            ->all();
    }

    private function searchServices(string $query, int $limit): array
    {
        return Service::where(function ($q) use ($query) {
            $q->where('name', 'like', "%{$query}%")
                ->orWhere('description', 'like', "%{$query}%");
        })
            ->orderBy('id')
            ->take($limit)
            ->get()
            ->map(fn ($s) => [
                'type' => 'service',
                'title' => $s->localized('name'),
                'subtitle' => 'Rp '.number_format($s->base_price, 0, ',', '.'),
                'href' => route('services.show', $s->id),
            ])
            ->all();
    }

    private static function formatPrice(int $min, int $max): string
    {
        $fmt = fn (int $n): string => 'Rp '.number_format($n, 0, ',', '.');

        return $min === $max ? $fmt($min) : $fmt($min).' – '.$fmt($max);
    }
}
