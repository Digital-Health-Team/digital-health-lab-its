<?php

namespace App\Actions\RawMaterial;

use App\DTOs\RawMaterial\RestockMaterialData;
use App\Models\RawMaterial;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class AddInitialStockAction
{
    /**
     * Initial stock follows the same flow as a restock: each color with a
     * quantity produces a Reimbursement + payment proof Attachment + Movement
     * + ItemStock increment. Colors without a quantity are only linked to
     * the brand.
     *
     * @param  array<int, array{quantity: int, amount: int, notes: string, proof: ?UploadedFile}>  $colorRows  keyed by color_id
     */
    public function execute(RawMaterial $material, array $colorRows, ?int $labId): void
    {
        DB::transaction(function () use ($material, $colorRows, $labId) {
            $material->brand->colors()->syncWithoutDetaching(array_keys($colorRows));

            if (! $labId) {
                return;
            }

            $title = __('Initial Stock — :brand :name', [
                'brand' => $material->brand->name,
                'name' => $material->name,
            ]);

            foreach ($colorRows as $colorId => $row) {
                if ($row['quantity'] <= 0) {
                    continue;
                }

                app(RestockMaterialAction::class)->execute(new RestockMaterialData(
                    raw_material_id: $material->id,
                    color_id: $colorId,
                    lab_id: $labId,
                    quantity: $row['quantity'],
                    total_amount: $row['amount'],
                    notes: $row['notes'],
                    payment_proof: $row['proof'],
                    title: $title,
                ));
            }
        });
    }
}
