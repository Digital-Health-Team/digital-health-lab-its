<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class ServicesController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Features/Services/Pages/ServicesPage');
    }

    public function show(string $service): Response
    {
        abort_unless(in_array($service, ['design', 'printing', 'scanning'], true), 404);

        return Inertia::render('Features/Services/Pages/ServiceRequestPage', [
            'service' => $service,
        ]);
    }
}
