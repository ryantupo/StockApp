<?php

namespace App\Http\Controllers\Products;

use App\Actions\Products\UpdateAction;
use App\DTOs\Products\UpdateProductData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Products\UpdateRequest;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;

class UpdateController extends Controller
{
    public function __invoke(UpdateRequest $request, Product $product, UpdateAction $action): RedirectResponse
    {
        $action->execute($product, UpdateProductData::fromRequest($request));

        return to_route('products.show', $product);
    }
}
