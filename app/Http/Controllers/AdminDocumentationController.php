<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class AdminDocumentationController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.documentation');
    }
}
