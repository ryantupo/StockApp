<?php

namespace App\Http\QueryBuilder\Filters;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class ProductSearchFilter implements Filter
{
    public function __invoke(Builder $query, mixed $value, string $property): void
    {
        $ids = Product::search($value)->keys();

        $query->whereIn('id', $ids);
    }
}
