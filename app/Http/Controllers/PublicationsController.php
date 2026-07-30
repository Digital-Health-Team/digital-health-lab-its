<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use Inertia\Inertia;
use Inertia\Response;

class PublicationsController extends Controller
{
    public function show(string $publication): Response
    {
        $pub = Publication::where('slug', $publication)->firstOrFail();
        $pub->increment('view_count');

        $related = Publication::where('category', $pub->category)
            ->where('id', '!=', $pub->id)
            ->latest('published_at')
            ->take(4)
            ->get()
            ->map(fn (Publication $p) => $p->toListArray());

        return Inertia::render('Features/Publications/Pages/PublicationDetailPage', [
            'publication' => array_merge($pub->toListArray(), [
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
}
