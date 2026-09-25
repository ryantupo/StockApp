<?php

namespace App\Actions\StockMovements;

use App\Models\Product;
use App\Models\User;
use App\Notifications\LowStockDigest;
use Illuminate\Support\Facades\Notification;

class SendLowStockDigestAction
{
    public function execute(): void
    {
        $products = Product::belowThreshold()->get();

        if ($products->isEmpty()) {
            return;
        }

        Notification::send(
            User::all(),
            new LowStockDigest($products)
        );
    }
}
