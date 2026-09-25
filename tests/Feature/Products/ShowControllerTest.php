<?php

namespace Tests\Feature\Products;

use App\Models\Product;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class ShowControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    public function test_can_view_a_product(): void
    {
        $product = Product::factory()->create();

        $response = $this->get(route('products.show', $product));

        $response
            ->assertOk()
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                    ->component('Products/Show')
                    ->where('product.data.id', $product->id)
            );
    }

    public function test_includes_stock_movements(): void
    {
        $product = Product::factory()->create();
        StockMovement::factory()->for($product)->count(2)->create();

        $response = $this->get(route('products.show', $product));

        $response->assertInertia(
            fn (AssertableInertia $page) => $page
                ->has('movements.data', 2)
        );
    }

    public function test_returns_404_for_nonexistent_product(): void
    {
        $response = $this->get(route('products.show', ['product' => (string) Str::uuid()]));

        $response->assertNotFound();
    }
}
