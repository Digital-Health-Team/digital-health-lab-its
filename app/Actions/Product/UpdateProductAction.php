<?php

namespace App\Actions\Product;

use App\DTOs\Product\ProductData;
use App\Models\Product;
use App\Models\Attachment;
use Illuminate\Support\Facades\DB;

class UpdateProductAction
{
    public function execute(Product $product, ProductData $data): Product
    {
        return DB::transaction(function () use ($product, $data) {
            $product->update([
                'name' => $data->name,
                'description' => $data->description,
                'price_min' => $data->price_min,
                'price_max' => $data->price_max,
            ]);

            $existingPrimary = $product->attachments()->where('is_primary', true)->exists();
            $currentSortOrder = $product->attachments()->max('sort_order') ?? 0;

            foreach ($data->new_photos as $index => $photo) {
                $path = $photo->store('products', 'public');

                Attachment::create([
                    'attachable_type' => Product::class,
                    'attachable_id' => $product->id,
                    'file_url' => $path,
                    'file_type' => $photo->getClientMimeType(),
                    'is_primary' => !$existingPrimary && $index === 0, // Jadikan primary jika belum ada foto sama sekali
                    'sort_order' => $currentSortOrder + $index + 1,
                    'uploaded_by' => $data->creator_id,
                ]);
            }

            return $product;
        });
    }
}
