<?php

namespace Tests\Feature\Products;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class IndexControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    public function test_can_list_products(): void
    {
        Product::factory()->count(3)->create();

        $response = $this->get(route('products.index'));

        $response
            ->assertOk()
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                    ->component('Products/Index')
                    ->has('products.data', 3)
            );
    }

    public function test_can_search_products_by_name(): void
    {
        Product::factory()->create(['name' => 'Widget Alpha']);
        Product::factory()->create(['name' => 'Gadget Beta']);

        $response = $this->get(route('products.index', ['filter' => ['search' => 'Widget']]));

        $response->assertInertia(
            fn (AssertableInertia $page) => $page
                ->has('products.data', 1)
                ->where('products.data.0.name', 'Widget Alpha')
        );
    }

    public function test_can_search_products_by_sku(): void
    {
        Product::factory()->create(['sku' => 'AB-12345']);
        Product::factory()->create(['sku' => 'ZZ-99999']);

        $response = $this->get(route('products.index', ['filter' => ['search' => 'AB-12345']]));

        $response->assertInertia(
            fn (AssertableInertia $page) => $page
                ->has('products.data', 1)
        );
    }

    public function test_can_filter_products_below_threshold(): void
    {
        Product::factory()->belowThreshold()->count(2)->create();
        Product::factory()->count(3)->create(['quantity' => 500, 'reorder_threshold' => 10]);

        $response = $this->get(route('products.index', ['filter' => ['below_threshold' => true]]));

        $response->assertInertia(
            fn (AssertableInertia $page) => $page
                ->has('products.data', 2)
        );
    }

    public function test_can_sort_products_by_quantity(): void
    {
        Product::factory()->create(['name' => 'Low', 'quantity' => 5, 'reorder_threshold' => 1]);
        Product::factory()->create(['name' => 'High', 'quantity' => 500, 'reorder_threshold' => 1]);

        $response = $this->get(route('products.index', ['sort' => 'quantity']));

        $response->assertInertia(
            fn (AssertableInertia $page) => $page
                ->where('products.data.0.name', 'Low')
        );
    }
}
