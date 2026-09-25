<?php

namespace App\Actions\StockMovements;

use App\Models\Product;
use App\Models\User;
use App\Notifications\LowStockDigest;

class SendLowStockDigestAction
{
    public function execute(): void
    {
        $products = Product::belowThreshold()->get();

        if ($products->isEmpty()) {
            return;
        }

        $manager = User::where('email', config('stock.manager_email'))->first();

        if (! $manager) {
            return;
        }

        $manager->notify(new LowStockDigest($products));
    }
}
