<?php

namespace App\Http\Controllers;

use App\Models\RawMaterial;
use Inertia\Inertia;
use Inertia\Response;

class ScanMaterialController extends Controller
{
    public function __invoke(string $unique_code): Response
    {
        $material = RawMaterial::with(['brand', 'stocks.lab', 'stocks.color'])
            ->where('unique_code', $unique_code)
            ->firstOrFail();

        $movements = $material->movements()
            ->with(['creator', 'booking'])
            ->latest()
            ->limit(10)
            ->get()
            ->map(fn ($m) => [
                'id' => $m->id,
                'type' => $m->type,
                'quantity' => $m->quantity,
                'notes' => $m->notes,
                'actor' => $m->creator?->name,
                'created_at' => $m->created_at->format('d M Y, H:i'),
            ]);

        return Inertia::render('Features/Scan/Pages/ScanMaterialPage', [
            'material' => [
                'unique_code' => $material->unique_code,
                'name' => $material->name,
                'unit' => $material->unit,
                'brand' => $material->brand?->name,
                'stocks' => $material->stocks->map(fn ($s) => [
                    'lab' => $s->lab?->name,
                    'color' => $s->color?->name,
                    'quantity' => $s->quantity,
                ]),
            ],
            'movements' => $movements,
        ]);
    }
}
