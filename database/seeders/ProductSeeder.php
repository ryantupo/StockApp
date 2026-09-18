<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::factory()
            ->count(180)
            ->create()
            ->each(
                fn (Product $product) => StockMovement::factory()
                    ->count(rand(5, 30))
                    ->for($product)
                    ->create()
            );

        Product::factory()
            ->belowThreshold()
            ->count(20)
            ->create()
            ->each(
                fn (Product $product) => StockMovement::factory()
                    ->count(rand(5, 30))
                    ->for($product)
                    ->create()
            );
    }
}
