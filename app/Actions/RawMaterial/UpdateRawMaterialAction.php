<?php

namespace App\Actions\RawMaterial;

use App\DTOs\RawMaterial\RawMaterialData;
use App\Models\RawMaterial;

class UpdateRawMaterialAction
{
    public function execute(RawMaterial $material, RawMaterialData $data): RawMaterial
    {
        $material->update([
            'brand_id' => $data->brand_id,
            'name' => $data->name,
            'unit' => $data->unit,
        ]);

        return $material;
    }
}
