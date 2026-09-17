<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'sku' => $this->sku,
            'quantity' => $this->quantity,
            'reorder_threshold' => $this->reorder_threshold,
            'is_below_threshold' => $this->is_below_threshold,
            'stock_movements' => new StockMovementCollection(
                $this->whenLoaded('stockMovements')
            ),
        ];
    }
}