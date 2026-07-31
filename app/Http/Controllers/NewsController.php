<?php

namespace App\Http\Controllers;

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
                'body' => $article['body'],
            ]),
            'related' => $articles
                ->where('slug', '!=', $slug)
                ->take(3)
                ->map(fn (array $a) => $this->listShape($a))
                ->values(),
        ]);
    }

    private function listShape(array $a): array
    {
        return [
            'slug' => $a['slug'],
            'title' => $a['title'],
            'category' => $a['category'],
            'date' => $a['date'],
            'excerpt' => $a['excerpt'],
            'image' => $a['image'],
            'imageAlt' => $a['image_alt'],
            'href' => route('news.show', $a['slug']),
        ];
    }
}
