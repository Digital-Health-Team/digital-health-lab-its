<?php

namespace App\Http\Controllers;

use Illuminate\Support\Carbon;
use Inertia\Inertia;
use Inertia\Response;

class NewsController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Features/News/Pages/NewsIndex', [
            'articles' => collect(config('lab-news.articles'))
                ->map(fn (array $a) => $this->listShape($a))
                ->values(),
        ]);
    }

    public function show(string $slug): Response
    {
        $articles = collect(config('lab-news.articles'));
        $article = $articles->firstWhere('slug', $slug);

        abort_if(! $article, 404);

        return Inertia::render('Features/News/Pages/NewsShow', [
            'article' => array_merge($this->listShape($article), [
                'body' => static::localised($article, 'body'),
            ]),
            'related' => $articles
                ->where('slug', '!=', $slug)
                ->take(3)
                ->map(fn (array $a) => $this->listShape($a))
                ->values(),
        ]);
    }

    /**
     * The `<key>_en` variant when the locale is English, else the Indonesian base.
     *
     * Mirrors LandingPageController::landingContentForLocale() — an absent or empty
     * `_en` falls back to Indonesian rather than rendering blank, so a half-translated
     * entry degrades to the original copy instead of leaving a hole in the page.
     */
    public static function localised(array $article, string $key): mixed
    {
        if (app()->getLocale() === 'en') {
            return ($article[$key.'_en'] ?? null) ?: $article[$key];
        }

        return $article[$key];
    }

    /** "12 June 2025" / "12 Juni 2025" from one stored ISO date. */
    public static function formatDate(string $iso): string
    {
        return Carbon::parse($iso)
            ->locale(app()->getLocale())
            ->isoFormat('D MMMM Y');
    }

    private function listShape(array $a): array
    {
        return [
            'slug' => $a['slug'],
            'title' => static::localised($a, 'title'),
            'category' => static::localised($a, 'category'),
            'date' => static::formatDate($a['date']),
            'excerpt' => static::localised($a, 'excerpt'),
            'image' => $a['image'],
            'imageAlt' => static::localised($a, 'image_alt'),
            'href' => route('news.show', $a['slug']),
        ];
    }
}
