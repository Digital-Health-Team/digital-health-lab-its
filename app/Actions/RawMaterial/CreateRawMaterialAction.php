<?php

namespace App\Actions\RawMaterial;

use App\DTOs\RawMaterial\RawMaterialData;
use App\Models\RawMaterial;

class CreateRawMaterialAction
{
    public function execute(RawMaterialData $data): RawMaterial
    {
        return RawMaterial::create([
            'name' => $data->name,
            'category' => $data->category,
            'unit' => $data->unit,
            'current_stock' => $data->current_stock,
        ]);
    }
}
