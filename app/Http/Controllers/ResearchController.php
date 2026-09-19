<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use Inertia\Inertia;
use Inertia\Response;

class ResearchController extends Controller
{
    /** The merged "Research" page: projects catalogue + publications list. */
    public function index(): Response
    {
        $publications = Publication::approved()
            ->latest('published_at')
            ->get()
            ->map(fn (Publication $p) => $p->toListArray());

        return Inertia::render('Features/Research/Pages/ResearchPage', compact('publications'));
    }
}
