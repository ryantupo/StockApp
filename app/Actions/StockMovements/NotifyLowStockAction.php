<?php

namespace App\Actions\StockMovements;

use App\Models\Product;
use App\Models\User;
use App\Notifications\LowStockAlert;

class NotifyLowStockAction
{
    public function execute(Product $product): void
    {
        $manager = User::where('email', config('stock.manager_email'))->first();

        if (! $manager) {
            return;
        }

        $manager->notify(new LowStockAlert($product));
    }
}
