<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Models\Product;

class ProductCollection extends ResourceCollection
{
    public $collects = ProductResource::class;

    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection,
        ];
    }

    public function with(Request $request): array
    {
        return [
            'meta' => [
                'total_below_threshold' => Product::belowThreshold()->count(),
            ],
        ];
    }
}