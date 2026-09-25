<?php

namespace App\Console\Commands;

use App\Actions\StockMovements\SendLowStockDigestAction;
use Illuminate\Console\Command;

class SendLowStockDigestCommand extends Command
{
    protected $signature = 'stock:digest';

    protected $description = 'Email the stock manager a digest of products currently below their reorder threshold';

    public function handle(SendLowStockDigestAction $action): int
    {
        $action->execute();

        return self::SUCCESS;
    }
}
