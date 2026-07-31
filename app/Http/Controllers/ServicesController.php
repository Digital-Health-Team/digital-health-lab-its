<?php

namespace App\Http\Controllers;

use App\Models\Color;
use App\Models\FilamentType;
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

        // Filament types and colors are only relevant for the printing service.
        $filaments = [];
        $colors = [];

        if ($service === 'printing') {
            $filaments = FilamentType::active()
                ->get()
                ->map(fn (FilamentType $f) => [
                    'code' => $f->code,
                    'name' => $f->name,
                    'scientificName' => $f->scientific_name,
                    'pricePerGram' => $f->price_per_gram,
                    'priceLabel' => 'Rp '.number_format($f->price_per_gram, 0, ',', '.'),
                    'description' => $f->description,
                ])
                ->values()
                ->all();

            $colors = Color::orderBy('name')
                ->get()
                ->map(fn (Color $c) => [
                    'id' => $c->id,
                    'name' => $c->name,
                    'hex' => $c->hex,
                ])
                ->values()
                ->all();
        }

        return Inertia::render('Features/Services/Pages/ServiceRequestPage', [
            'service' => $service,
            'serviceId' => $svc->id,
            'isAuthenticated' => auth()->check(),
            'filaments' => $filaments,
            'colors' => $colors,
        ]);
    }
}
