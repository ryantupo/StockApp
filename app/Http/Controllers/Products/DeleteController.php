<?php

namespace App\Http\Controllers\Products;

use App\Actions\Products\DeleteAction;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;

class DeleteController extends Controller
{
    public function __invoke(Product $product, DeleteAction $action): RedirectResponse
    {
        $action->execute($product);

        return to_route('products.index');
    }
}
