<?php

namespace App\Http\Controllers\StockMovements;

use App\Actions\StockMovements\CreateAction;
use App\DTOs\StockMovements\CreateStockMovementData;
use App\Http\Controllers\Controller;
use App\Http\Requests\StockMovements\CreateRequest;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;

class CreateController extends Controller
{
    public function __invoke(CreateRequest $request, Product $product, CreateAction $action): RedirectResponse
    {
        $action->execute($product, CreateStockMovementData::fromRequest($request));

        return to_route('products.show', $product);
    }
}
