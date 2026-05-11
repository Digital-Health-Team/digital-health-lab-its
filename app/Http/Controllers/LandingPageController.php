<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class LandingPageController extends Controller
{
    public function __invoke()
    {
        return static::index();
    }

    public static function index()
    {
        return Inertia::render("Features/Landing/Pages/LandingPage");
    }
}
