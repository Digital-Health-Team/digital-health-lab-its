<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class PameranController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Features/Pameran/Pages/PameranPage');
    }
}
