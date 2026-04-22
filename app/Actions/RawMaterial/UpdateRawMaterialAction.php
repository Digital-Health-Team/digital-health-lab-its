<?php

namespace App\Actions\RawMaterial;

use App\DTOs\RawMaterial\RawMaterialData;
use App\Models\RawMaterial;

class UpdateRawMaterialAction
{
    public function execute(RawMaterial $material, RawMaterialData $data): RawMaterial
    {
        $material->update([
            'name' => $data->name,
            'category' => $data->category,
            'unit' => $data->unit,
            'current_stock' => $data->current_stock,
        ]);

        return $material;
    }
}
