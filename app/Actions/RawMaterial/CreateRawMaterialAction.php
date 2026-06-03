<?php

namespace App\Actions\RawMaterial;

use App\DTOs\RawMaterial\RawMaterialData;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Lab;
use App\Models\MaterialCategory;
use App\Models\RawMaterial;

class CreateRawMaterialAction
{
    /**
     * Resolve string attributes to master table IDs via firstOrCreate(),
     * then create the raw material with FK references.
     */
    public function execute(RawMaterialData $data): RawMaterial
    {
        $lab = Lab::firstOrCreate(['name' => $data->lab]);
        $category = MaterialCategory::firstOrCreate(['name' => $data->category]);
        $brand = Brand::firstOrCreate(['name' => $data->brand]);
        $color = Color::firstOrCreate(['name' => $data->color]);

        return RawMaterial::create([
            'lab_id' => $lab->id,
            'material_category_id' => $category->id,
            'brand_id' => $brand->id,
            'color_id' => $color->id,
            'unit' => $data->unit,
            'current_stock' => $data->current_stock,
        ]);
    }
}
