<?php

namespace App\Actions\Product;

use App\Models\Product;

class ToggleProductStatusAction
{
    public function execute(Product $product): void
    {
        $product->update(['is_active' => !$product->is_active]);
    }
}
