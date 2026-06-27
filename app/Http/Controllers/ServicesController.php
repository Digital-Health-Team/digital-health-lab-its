<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Inertia\Inertia;
use Inertia\Response;

class ServicesController extends Controller
{
    public function index(): Response
    {
        $dbServices = Service::whereIn('service_type', ['design', 'printing', 'scanning'])
            ->get()
            ->map(fn (Service $s) => [
                'id' => $s->id,
                'name' => $s->name,
                'description' => $s->description,
                'service_type' => $s->service_type,
                'priceLabel' => 'Rp '.number_format($s->base_price, 0, ',', '.'),
            ]);

        return Inertia::render('Features/Services/Pages/ServicesPage', [
            'dbServices' => $dbServices,
        ]);
    }

    public function show(string $service): Response
    {
        abort_unless(in_array($service, ['design', 'printing', 'scanning'], true), 404);

        $svc = Service::where('service_type', $service)->firstOrFail();

        return Inertia::render('Features/Services/Pages/ServiceRequestPage', [
            'service' => $service,
            'serviceId' => $svc->id,
            'isAuthenticated' => auth()->check(),
        ]);
    }
}
