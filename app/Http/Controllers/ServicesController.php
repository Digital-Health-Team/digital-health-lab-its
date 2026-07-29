<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Models\BookingMessage;
use App\Models\Color;
use App\Models\FilamentType;
use App\Models\Service;
use App\Models\ServiceBooking;
use Inertia\Inertia;
use Inertia\Response;

class ServicesController extends Controller
{
    public function index(): Response
    {
        $dbServices = Service::whereIn('service_type', ['design', 'printing', 'scanning', 'consultation'])
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

    /**
     * Standing consultation thread — one per user, reusing the booking chat
     * stack (same table, action, endpoint and broadcast channel as an order
     * chat). The admin replies from the Order Center chat tab.
     */
    public function consultation(): Response
    {
        // ponytail: firstOrCreate instead of a migration — Service::$timestamps
        // is false, so this is a plain idempotent insert that also self-heals
        // databases seeded before the consultation service existed.
        $service = Service::firstOrCreate(
            ['service_type' => 'consultation'],
            [
                'name' => 'Consultation',
                'description' => 'Chat with the lab team about your idea before placing an order.',
                'base_price' => 0,
            ],
        );

        $booking = ServiceBooking::firstOrCreate(
            ['user_id' => auth()->id(), 'service_id' => $service->id],
            [
                'brief_description' => 'General consultation',
                'current_status' => BookingStatus::Consultation,
            ],
        );

        // Mark admin messages as read now that the customer is viewing the thread.
        $booking->messages()
            ->whereNull('read_at')
            ->where('sender_id', '!=', auth()->id())
            ->update(['read_at' => now()]);

        $booking->load([
            'messages' => fn ($q) => $q->oldest('id'),
            'messages.sender',
        ]);

        return Inertia::render('Features/Services/Pages/ConsultationPage', [
            // Prop name must stay 'order' — OrderChat partial-reloads only: ['order'].
            'order' => [
                'id' => $booking->id,
                'messages' => $booking->messages->map(fn (BookingMessage $m) => [
                    'id' => $m->id,
                    'body' => $m->body,
                    'senderId' => $m->sender_id,
                    'senderName' => $m->sender?->name,
                    'isMine' => $m->sender_id === auth()->id(),
                    'createdAt' => $m->created_at?->toDateTimeString(),
                ]),
            ],
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
