<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class PublicationsController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Features/Publications/Pages/PublicationsPage');
    }

    public function show(string $publication): Response
    {
        return Inertia::render('Features/Publications/Pages/PublicationDetailPage', [
            'publicationSlug' => $publication,
        ]);
    }
}
