<?php

namespace App\Actions\RawMaterial;

use App\DTOs\RawMaterial\RawMaterialData;
use App\Models\RawMaterial;

class UpdateRawMaterialAction
{
    public function execute(RawMaterial $material, RawMaterialData $data): RawMaterial
    {
        $material->update([
            'lab_id' => $data->lab_id,
            'material_category_id' => $data->category_id,
            'brand_id' => $data->brand_id,
            'color_id' => $data->color_id,
            'unit' => $data->unit,
        ]);

        return $material;
    }
}
