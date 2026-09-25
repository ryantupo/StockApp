<?php

namespace Tests\Feature\StockMovements;

use App\Models\Product;
use App\Models\User;
use App\Notifications\LowStockAlert;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class CreateControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    public function test_can_record_a_positive_movement(): void
    {
        $product = Product::factory()->create([
            'quantity' => 50,
            'reorder_threshold' => 10,
        ]);

        $response = $this->post(route('products.movements.create', $product), [
            'quantity_change' => 25,
            'reason' => 'Restock delivery',
        ]);

        $response->assertRedirect(route('products.index'));

        $this->assertDatabaseHas('stock_movements', [
            'product_id' => $product->id,
            'quantity_change' => 25,
            'reason' => 'Restock delivery',
        ]);

        $this->assertEquals(75, $product->fresh()->quantity);
    }

    public function test_can_record_a_negative_movement(): void
    {
        $product = Product::factory()->create([
            'quantity' => 50,
            'reorder_threshold' => 10,
        ]);

        $this->post(route('products.movements.create', $product), [
            'quantity_change' => -20,
            'reason' => 'Customer order',
        ]);

        $this->assertEquals(30, $product->fresh()->quantity);
    }

    public function test_rejects_a_zero_quantity_change(): void
    {
        $product = Product::factory()->create();

        $response = $this->post(route('products.movements.create', $product), [
            'quantity_change' => 0,
            'reason' => 'Nothing',
        ]);

        $response->assertSessionHasErrors('quantity_change');
    }

    public function test_requires_a_reason(): void
    {
        $product = Product::factory()->create();

        $response = $this->post(route('products.movements.create', $product), [
            'quantity_change' => 10,
        ]);

        $response->assertSessionHasErrors('reason');
    }

    public function test_sends_alert_to_all_users_when_crossing_below_threshold(): void
    {
        Notification::fake();

        $users = User::factory()->count(3)->create();

        $product = Product::factory()->create([
            'quantity' => 20,
            'reorder_threshold' => 10,
        ]);

        $this->post(route('products.movements.create', $product), [
            'quantity_change' => -15,
            'reason' => 'Large order',
        ]);

        foreach ($users as $user) {
            Notification::assertSentTo($user, LowStockAlert::class);
        }

        $this->assertNotNull($product->fresh()->low_stock_alerted_at);
    }

    public function test_does_not_resend_alert_on_subsequent_movements_while_still_below(): void
    {
        Notification::fake();

        $users = User::factory()->count(3)->create();

        $product = Product::factory()->create([
            'quantity' => 5,
            'reorder_threshold' => 10,
            'low_stock_alerted_at' => now(),
        ]);

        $this->post(route('products.movements.create', $product), [
            'quantity_change' => -2,
            'reason' => 'Another order',
        ]);

        Notification::assertNothingSent();

        foreach ($users as $user) {
            Notification::assertNotSentTo($user, LowStockAlert::class);
        }
    }

    public function test_can_alert_again_after_recovering_above_threshold(): void
    {
        Notification::fake();

        $users = User::factory()->count(3)->create();

        $product = Product::factory()->create([
            'quantity' => 5,
            'reorder_threshold' => 10,
            'low_stock_alerted_at' => now(),
        ]);

        // Recover above threshold - should clear the flag, no alert
        $this->post(route('products.movements.create', $product), [
            'quantity_change' => 20,
            'reason' => 'Restock',
        ]);

        $this->assertNull($product->fresh()->low_stock_alerted_at);
        Notification::assertNothingSent();

        // Dip below again - should alert all users
        $this->post(route('products.movements.create', $product), [
            'quantity_change' => -20,
            'reason' => 'Big order',
        ]);

        foreach ($users as $user) {
            Notification::assertSentTo($user, LowStockAlert::class);
        }
    }

    public function test_does_not_alert_when_staying_above_threshold(): void
    {
        Notification::fake();

        User::factory()->count(3)->create();

        $product = Product::factory()->create([
            'quantity' => 100,
            'reorder_threshold' => 10,
        ]);

        $this->post(route('products.movements.create', $product), [
            'quantity_change' => -5,
            'reason' => 'Small order',
        ]);

        Notification::assertNothingSent();
    }
}
