<?php

namespace App\Actions\RawMaterial;

use App\DTOs\RawMaterial\RawMaterialData;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Lab;
use App\Models\MaterialCategory;
use App\Models\RawMaterial;

class UpdateRawMaterialAction
{
    /**
     * Resolve string attributes to master table IDs via firstOrCreate(),
     * then update the raw material with new FK references.
     */
    public function execute(RawMaterial $material, RawMaterialData $data): RawMaterial
    {
        $lab = Lab::firstOrCreate(['name' => $data->lab]);
        $category = MaterialCategory::firstOrCreate(['name' => $data->category]);
        $brand = Brand::firstOrCreate(['name' => $data->brand]);
        $color = Color::firstOrCreate(['name' => $data->color]);

        $material->update([
            'lab_id' => $lab->id,
            'material_category_id' => $category->id,
            'brand_id' => $brand->id,
            'color_id' => $color->id,
            'unit' => $data->unit,
        ]);

        return $material;
    }
}
