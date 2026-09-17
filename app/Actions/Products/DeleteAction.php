<?php

namespace App\Actions\Products;

use App\Models\Product;

class DeleteAction
{
    public function execute(Product $product): void
    {
        $product->delete();
    }
}