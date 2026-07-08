<?php

namespace App\Actions\RawMaterial;

use App\DTOs\RawMaterial\RestockMaterialData;
use App\Models\Attachment;
use App\Models\ItemStock;
use App\Models\RawMaterial;
use App\Models\RawMaterialMovement;
use App\Models\Reimbursement;
use Illuminate\Support\Facades\DB;

class RestockMaterialAction
{
    /**
     * Atomic restock: Reimbursement → Attachment → Movement → stock increment on item_stocks.
     */
    public function execute(RestockMaterialData $data): void
    {
        DB::transaction(function () use ($data) {
            $material = RawMaterial::with('brand')->findOrFail($data->raw_material_id);

            // Keep brand_colors in sync with the material form: restocking a
            // color links it to the brand if it isn't already.
            $material->brand->colors()->syncWithoutDetaching([$data->color_id]);

            $reimbursement = Reimbursement::create([
                'user_id' => auth()->id(),
                'title' => $data->title ?? __('Restock — :brand :name', ['brand' => $material->brand->name, 'name' => $material->name]),
                'total_amount' => $data->total_amount,
                'status' => 'pending',
            ]);

            $path = $data->payment_proof->store('reimbursements', 'public');

            Attachment::create([
                'attachable_type' => Reimbursement::class,
                'attachable_id' => $reimbursement->id,
                'file_url' => $path,
                'file_type' => $data->payment_proof->getClientMimeType(),
                'is_primary' => true,
                'uploaded_by' => auth()->id(),
            ]);

            RawMaterialMovement::create([
                'raw_material_id' => $data->raw_material_id,
                'type' => 'in',
                'quantity' => $data->quantity,
                'reimbursement_id' => $reimbursement->id,
                'notes' => $data->notes,
                'created_by' => auth()->id(),
            ]);

            // Increment (or create) the stock entry for this item+color+lab combination.
            $stock = ItemStock::firstOrCreate(
                [
                    'raw_material_id' => $data->raw_material_id,
                    'color_id' => $data->color_id,
                    'lab_id' => $data->lab_id,
                ],
                ['quantity' => 0]
            );
            $stock->increment('quantity', $data->quantity);
        });
    }
}
