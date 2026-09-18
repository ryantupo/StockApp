<?php

namespace Tests\Feature\Products;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    public function test_can_create_a_product(): void
    {
        $response = $this->post(route('products.create'), [
            'name' => 'Test Widget',
            'sku' => 'TW-00001',
            'quantity' => 100,
            'reorder_threshold' => 10,
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('products', [
            'name' => 'Test Widget',
            'sku' => 'TW-00001',
        ]);
    }

    public function test_requires_a_name(): void
    {
        $response = $this->post(route('products.create'), [
            'sku' => 'TW-00001',
            'quantity' => 100,
            'reorder_threshold' => 10,
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_requires_a_unique_sku(): void
    {
        Product::factory()->create(['sku' => 'TW-00001']);

        $response = $this->post(route('products.create'), [
            'name' => 'Another Widget',
            'sku' => 'TW-00001',
            'quantity' => 100,
            'reorder_threshold' => 10,
        ]);

        $response->assertSessionHasErrors('sku');
    }

    public function test_rejects_negative_quantity(): void
    {
        $response = $this->post(route('products.create'), [
            'name' => 'Test Widget',
            'sku' => 'TW-00001',
            'quantity' => -5,
            'reorder_threshold' => 10,
        ]);

        $response->assertSessionHasErrors('quantity');
    }
}
