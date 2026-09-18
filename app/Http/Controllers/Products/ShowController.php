<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Inertia\Inertia;
use Inertia\Response;

class ShowController extends Controller
{
    public function __invoke(Product $product): Response
    {
        $product->load('stockMovements');

        return Inertia::render('Products/Show', [
            'product' => new ProductResource($product),
        ]);
    }
}
