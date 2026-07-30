<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use Inertia\Inertia;
use Inertia\Response;

class PublicationsController extends Controller
{
    public function index(): Response
    {
        $publications = Publication::latest('published_at')
            ->get()
            ->map(fn (Publication $p) => $this->listShape($p));

        return Inertia::render('Features/Publications/Pages/PublicationsPage', compact('publications'));
    }

    public function show(string $publication): Response
    {
        $pub = Publication::where('slug', $publication)->firstOrFail();
        $pub->increment('view_count');

        $related = Publication::where('category', $pub->category)
            ->where('id', '!=', $pub->id)
            ->latest('published_at')
            ->take(4)
            ->get()
            ->map(fn (Publication $p) => $this->listShape($p));

        return Inertia::render('Features/Publications/Pages/PublicationDetailPage', [
            'publication' => array_merge($this->listShape($pub), [
                'abstract' => $pub->localized('abstract'),
                'description' => $pub->localized('description') ?? [],
                'keywords' => $pub->localized('keywords') ?? [],
                'doi' => $pub->doi,
                'pdfUrl' => $pub->pdf_url,
                'fileSize' => $pub->pdf_file_size,
                'related' => $related,
            ]),
        ]);
    }

    private function listShape(Publication $p): array
    {
        return [
            'id' => (string) $p->id,
            'title' => $p->localized('title'),
            'slug' => $p->slug,
            'author' => $p->author,
            'category' => $p->category,
            'thumbnailUrl' => $p->thumbnail_url ?? '',
            'publishedAt' => $p->published_at?->toISOString() ?? $p->created_at->toISOString(),
            'viewCount' => $p->view_count,
            'href' => route('publications.show', $p->slug),
            'journal' => $p->journal,
            'pmid' => $p->pmid,
            'isFreeAccess' => $p->is_free_access,
        ];
    }
}
