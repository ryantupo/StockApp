<?php

namespace App\Http\Controllers\Products;

use App\Actions\Products\CreateAction;
use App\DTOs\Products\CreateProductData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Products\CreateRequest;
use Illuminate\Http\RedirectResponse;

class CreateController extends Controller
{
    public function __invoke(CreateRequest $request, CreateAction $action): RedirectResponse
    {
        $product = $action->execute(CreateProductData::fromRequest($request));

        return to_route('products.show', $product);
    }
}
