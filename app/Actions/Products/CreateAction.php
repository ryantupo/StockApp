<?php

namespace App\Actions\Products;

use App\DTOs\Products\CreateProductData;
use App\Models\Product;

class CreateAction
{
    public function execute(CreateProductData $data): Product
    {
        return Product::create($data->toArray());
    }
}
