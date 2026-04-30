<?php

namespace App\Actions\RawMaterial;

use App\Models\RawMaterial;
use App\Models\RawMaterialMovement;
use Illuminate\Support\Facades\DB;

class RestockMaterialAction
{
    public function execute(RawMaterial $material, int $quantity, string $notes): void
    {
        DB::transaction(function () use ($material, $quantity, $notes) {
            // 1. Catat log pergerakan masuk (IN)
            RawMaterialMovement::create([
                'raw_material_id' => $material->id,
                'type' => 'in',
                'quantity' => $quantity,
                'notes' => $notes,
                'created_by' => auth()->id(), // Mencatat admin siapa yang melakukan restock
            ]);

            // 2. Tambahkan stok ke Master Material
            $material->increment('current_stock', $quantity);
        });
    }
}
