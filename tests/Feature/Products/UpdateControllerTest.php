<?php

namespace Tests\Feature\Products;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    public function test_can_update_a_product(): void
    {
        $product = Product::factory()->create(['name' => 'Old Name']);

        $response = $this->put(route('products.update', $product), [
            'name' => 'New Name',
            'sku' => $product->sku,
            'quantity' => $product->quantity,
            'reorder_threshold' => $product->reorder_threshold,
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'New Name',
        ]);
    }

    public function test_can_keep_its_own_sku_unchanged(): void
    {
        $product = Product::factory()->create(['sku' => 'TW-00001']);

        $response = $this->put(route('products.update', $product), [
            'name' => $product->name,
            'sku' => 'TW-00001',
            'quantity' => $product->quantity,
            'reorder_threshold' => $product->reorder_threshold,
        ]);

        $response->assertSessionHasNoErrors();
    }

    public function test_rejects_a_sku_already_used_by_another_product(): void
    {
        Product::factory()->create(['sku' => 'TAKEN-001']);
        $product = Product::factory()->create(['sku' => 'TW-00001']);

        $response = $this->put(route('products.update', $product), [
            'name' => $product->name,
            'sku' => 'TAKEN-001',
            'quantity' => $product->quantity,
            'reorder_threshold' => $product->reorder_threshold,
        ]);

        $response->assertSessionHasErrors('sku');
    }
}
