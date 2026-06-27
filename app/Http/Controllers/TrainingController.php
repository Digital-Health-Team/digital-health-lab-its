<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class TrainingController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Features/Training/Pages/TrainingPage');
    }

    public function show(string $training): Response
    {
        return Inertia::render('Features/Training/Pages/TrainingDetailPage', [
            'trainingSlug' => $training,
        ]);
    }
}
