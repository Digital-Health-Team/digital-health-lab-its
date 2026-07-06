<?php

namespace App\Actions\RawMaterial;

use App\Models\ItemStock;
use App\Models\RawMaterial;
use App\Models\RawMaterialMovement;
use Illuminate\Support\Facades\Auth;

class AddInitialStockAction
{
    public function execute(RawMaterial $material, int $colorId, int $labId, int $quantity): void
    {
        $stock = ItemStock::firstOrCreate(
            ['raw_material_id' => $material->id, 'color_id' => $colorId, 'lab_id' => $labId],
            ['quantity' => 0]
        );
        $stock->increment('quantity', $quantity);

        RawMaterialMovement::create([
            'raw_material_id' => $material->id,
            'type' => 'in',
            'quantity' => $quantity,
            'notes' => 'Initial stock setup',
            'created_by' => Auth::id(),
        ]);
    }
}
