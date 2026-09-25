<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Resources\StockMovementCollection;
use App\Models\Product;
use Inertia\Inertia;
use Inertia\Response;

class ShowController extends Controller
{
    public function __invoke(Product $product): Response
    {
        $movements = $product->stockMovements()
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Products/Show', [
            'product' => new ProductResource($product),
            'movements' => new StockMovementCollection($movements),
        ]);
    }
}
