<?php

namespace App\Http\Controllers;

use App\Models\OpenSourceProject;
use App\Models\Product;
use App\Models\Publication;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GlobalSearchController extends Controller
{
    private const MAX_RESULTS_PER_TYPE = 4;

    public function index(Request $request): JsonResponse
    {
        $query = trim($request->get('q', ''));

        if (strlen($query) < 2) {
            return response()->json(['query' => $query, 'results' => []]);
        }

        $results = array_merge(
            $this->searchPublications($query),
            $this->searchProjects($query),
            $this->searchProducts($query),
            $this->searchServices($query),
        );

        return response()->json(['query' => $query, 'results' => $results]);
    }

    private function searchPublications(string $query): array
    {
        return Publication::where(function ($q) use ($query) {
            $q->where('title', 'like', "%{$query}%")
                ->orWhere('author', 'like', "%{$query}%")
                ->orWhere('abstract', 'like', "%{$query}%");
        })
            ->latest('published_at')
            ->take(self::MAX_RESULTS_PER_TYPE)
            ->get()
            ->map(fn ($p) => [
                'type' => 'publication',
                'title' => $p->title,
                'subtitle' => $p->author.' · '.$p->category,
                'href' => route('publications.show', $p->slug),
            ])
            ->all();
    }

    private function searchProjects(string $query): array
    {
        return OpenSourceProject::where('status', 'approved')
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                    ->orWhere('caption', 'like', "%{$query}%");
            })
            ->latest()
            ->take(self::MAX_RESULTS_PER_TYPE)
            ->get()
            ->map(fn ($p) => [
                'type' => 'project',
                'title' => $p->title,
                'subtitle' => $p->caption ?? '',
                'href' => '/projects/'.$p->id,
            ])
            ->all();
    }

    private function searchProducts(string $query): array
    {
        return Product::where('is_active', true)
            ->where('name', 'like', "%{$query}%")
            ->with(['attachments' => fn ($q) => $q->where('is_primary', true)])
            ->latest()
            ->take(self::MAX_RESULTS_PER_TYPE)
            ->get()
            ->map(fn ($p) => [
                'type' => 'product',
                'title' => $p->name,
                'subtitle' => self::formatPrice($p->price_min, $p->price_max),
                'href' => route('products.show', $p->id),
            ])
            ->all();
    }

    private function searchServices(string $query): array
    {
        return Service::where('name', 'like', "%{$query}%")
            ->take(self::MAX_RESULTS_PER_TYPE)
            ->get()
            ->map(fn ($s) => [
                'type' => 'service',
                'title' => $s->name,
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
