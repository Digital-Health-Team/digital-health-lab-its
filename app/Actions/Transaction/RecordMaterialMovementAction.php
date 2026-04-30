<?php

namespace App\Actions\Transaction;

use App\DTOs\Transaction\MaterialMovementData;
use App\Models\RawMaterialMovement;
use App\Models\RawMaterial;
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

            // 2. Potong atau tambah stok fisik di Master Data
            $material = RawMaterial::findOrFail($data->raw_material_id);

            if ($data->movement_type === 'out') {
                if ($material->current_stock < $data->quantity) {
                    throw new \Exception(__('Insufficient stock for material: ') . $material->name);
                }
                $material->decrement('current_stock', $data->quantity);
            } else {
                $material->increment('current_stock', $data->quantity);
            }
        });
    }
}
