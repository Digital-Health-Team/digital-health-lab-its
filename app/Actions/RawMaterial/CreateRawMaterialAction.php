<?php

namespace App\Actions\RawMaterial;

use App\DTOs\RawMaterial\RawMaterialData;
use App\Models\RawMaterial;

class CreateRawMaterialAction
{
    public function execute(RawMaterialData $data): RawMaterial
    {
        return RawMaterial::create([
            'brand_id' => $data->brand_id,
            'name' => $data->name,
            'unit' => $data->unit,
            'created_by' => $data->created_by,
        ]);
    }
}
