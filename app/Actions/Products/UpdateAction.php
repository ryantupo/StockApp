<?php

namespace App\Actions\Products;

use App\DTOs\Products\UpdateProductData;
use App\Models\Product;

class UpdateAction
{
    public function execute(Product $product, UpdateProductData $data): Product
    {
        $product->update($data->toArray());

        return $product;
    }
}
