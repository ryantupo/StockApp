<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Http\QueryBuilder\Filters\ProductSearchFilter;
use App\Http\Resources\ProductCollection;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class IndexController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $products = QueryBuilder::for(Product::class)
            ->allowedFilters(
                AllowedFilter::custom('search', new ProductSearchFilter),
                AllowedFilter::scope('below_threshold', 'belowThreshold'),
            )
            ->allowedSorts(
                AllowedSort::field('quantity'),
                AllowedSort::field('name'),
            )
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Products/Index', [
            'products' => new ProductCollection($products),
            'filters' => [
                'filter' => [
                    'search' => $request->input('filter.search'),
                    'below_threshold' => $request->input('filter.below_threshold'),
                ],
                'sort' => $request->input('sort'),
            ],
        ]);
    }
}
