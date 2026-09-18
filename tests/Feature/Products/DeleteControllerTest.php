<?php

namespace Tests\Feature\Products;

use App\Models\Product;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    public function test_can_delete_a_product(): void
    {
        $product = Product::factory()->create();

        $response = $this->delete(route('products.delete', $product));

        $response->assertRedirect(route('products.index'));

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_deleting_a_product_cascades_its_stock_movements(): void
    {
        $product = Product::factory()->create();
        StockMovement::factory()->for($product)->create();

        $this->delete(route('products.delete', $product));

        $this->assertDatabaseMissing('stock_movements', ['product_id' => $product->id]);
    }
}
