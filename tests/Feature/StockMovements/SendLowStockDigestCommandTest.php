<?php

namespace Tests\Feature\StockMovements;

use App\Models\Product;
use App\Models\User;
use App\Notifications\LowStockDigest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class SendLowStockDigestCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_sends_digest_when_products_are_below_threshold(): void
    {
        Notification::fake();

        $manager = User::factory()->create(['email' => config('stock.manager_email')]);
        Product::factory()->belowThreshold()->count(3)->create();
        Product::factory()->count(2)->create(['quantity' => 500, 'reorder_threshold' => 10]);

        $this->artisan('stock:digest');

        Notification::assertSentTo(
            $manager,
            LowStockDigest::class,
            fn (LowStockDigest $notification) => $notification->products->count() === 3
        );
    }

    public function test_sends_nothing_when_no_products_are_below_threshold(): void
    {
        Notification::fake();

        User::factory()->create(['email' => config('stock.manager_email')]);
        Product::factory()->count(5)->create(['quantity' => 500, 'reorder_threshold' => 10]);

        $this->artisan('stock:digest');

        Notification::assertNothingSent();
    }
}
