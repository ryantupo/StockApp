<?php

namespace App\Actions\StockMovements;

use App\Models\Product;
use App\Models\User;
use App\Notifications\LowStockAlert;
use Illuminate\Support\Facades\Notification;

class NotifyLowStockAction
{
    public function execute(Product $product): void
    {
        Notification::send(
            User::all(),
            new LowStockAlert($product)
        );
    }
}
