<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class DevDocumentationController extends Controller
{
    public function __invoke(): View
    {
        return view('dev.documentation');
    }
}
