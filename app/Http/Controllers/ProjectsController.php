<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class ProjectsController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Features/Projects/Pages/ProjectsPage');
    }

    public function show(string $project): Response
    {
        return Inertia::render('Features/Projects/Pages/ProjectDetailPage', [
            'projectSlug' => $project,
        ]);
    }
}
