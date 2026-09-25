<?php

namespace App\Actions\StockMovements;

use App\DTOs\StockMovements\CreateStockMovementData;
use App\Models\Product;
use App\Models\StockMovement;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CreateAction
{
    public function __construct(
        private readonly NotifyLowStockAction $notifyLowStock,
    ) {}

    public function execute(Product $product, CreateStockMovementData $data): StockMovement
    {
        return DB::transaction(function () use ($product, $data) {
            /** @var StockMovement $movement */
            $movement = $product->stockMovements()->create([
                'quantity_change' => $data->quantityChange,
                'reason' => $data->reason,
            ]);

            $wasBelow = $product->is_below_threshold;

            $product->quantity += $data->quantityChange;

            $nowBelow = $product->quantity < $product->reorder_threshold;

            if ($nowBelow && ! $wasBelow) {
                $product->low_stock_alerted_at = Carbon::now();
                $this->notifyLowStock->execute($product);
            } elseif (! $nowBelow && $wasBelow) {
                $product->low_stock_alerted_at = null;
            }

            $product->save();

            return $movement;
        });
    }
}
