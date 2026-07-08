<?php

namespace App\Actions\Transaction;

use App\DTOs\Transaction\MaterialMovementData;
use App\Models\ItemStock;
use App\Models\RawMaterial;
use App\Models\RawMaterialMovement;
use Illuminate\Support\Facades\DB;

class RecordMaterialMovementAction
{
    public function execute(MaterialMovementData $data): void
    {
        DB::transaction(function () use ($data) {
            // 1. Catat histori pergerakan
            RawMaterialMovement::create([
                'raw_material_id' => $data->raw_material_id,
                'service_booking_id' => $data->service_booking_id,
                'type' => $data->movement_type,
                'quantity' => $data->quantity,
                'notes' => $data->notes,
                'created_by' => auth()->id(), // <--- TAMBAHKAN BARIS INI
            ]);

            // 2. Potong stok fisik dari item_stocks (greedy: row terbanyak dulu)
            if ($data->movement_type === 'out') {
                $material = RawMaterial::findOrFail($data->raw_material_id);
                $totalStock = ItemStock::where('raw_material_id', $data->raw_material_id)->sum('quantity');

                if ($totalStock < $data->quantity) {
                    throw new \Exception(__('Insufficient stock for material: ').$material->name);
                }

                $remaining = $data->quantity;
                foreach (ItemStock::where('raw_material_id', $data->raw_material_id)->orderByDesc('quantity')->get() as $stock) {
                    if ($remaining <= 0) {
                        break;
                    }
                    $deduct = min($stock->quantity, $remaining);
                    $stock->decrement('quantity', $deduct);
                    $remaining -= $deduct;
                }
            }
            // 'in' movements go through RestockMaterialAction which carries color/lab context
        });
    }
}
