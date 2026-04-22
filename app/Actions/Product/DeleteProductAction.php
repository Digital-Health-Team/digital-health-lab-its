<?php

namespace App\Actions\Product;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class DeleteProductAction
{
    public function execute(Product $product): void
    {
        // Hapus file fisik dari storage secara masal
        foreach ($product->attachments as $attachment) {
            if (Storage::disk('public')->exists($attachment->file_url)) {
                Storage::disk('public')->delete($attachment->file_url);
            }
            $attachment->delete();
        }

        $product->delete();
    }
}
