<?php

namespace App\Actions\Product;

use App\DTOs\Product\ProductData;
use App\Models\Product;
use App\Models\Attachment;
use Illuminate\Support\Facades\DB;

class CreateProductAction
{
    public function execute(ProductData $data): Product
    {
        return DB::transaction(function () use ($data) {
            $product = Product::create([
                'creator_id' => $data->creator_id,
                'name' => $data->name,
                'description' => $data->description,
                'price_min' => $data->price_min,
                'price_max' => $data->price_max,
                'is_active' => $data->is_active,
            ]);

            // Looping dan simpan multi-foto
            foreach ($data->new_photos as $index => $photo) {
                $path = $photo->store('products', 'public');

                Attachment::create([
                    'attachable_type' => Product::class,
                    'attachable_id' => $product->id,
                    'file_url' => $path,
                    'file_type' => $photo->getClientMimeType(),
                    'is_primary' => $index === 0, // Foto yang pertama diunggah otomatis jadi Thumbnail
                    'sort_order' => $index,
                    'uploaded_by' => $data->creator_id,
                ]);
            }

            return $product;
        });
    }
}
