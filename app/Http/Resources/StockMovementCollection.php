<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class StockMovementCollection extends ResourceCollection
{
    public $collects = StockMovementResource::class;

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->values(),
            'links' => $this->resource->toArray()['links'] ?? [],
        ];
    }
}
