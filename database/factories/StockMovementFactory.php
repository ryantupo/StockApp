<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Database\Eloquent\Factories\Factory;

class StockMovementFactory extends Factory
{
    protected $model = StockMovement::class;

    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'quantity_change' => fake()->randomElement([
                fake()->numberBetween(1, 100),   // stock in
                fake()->numberBetween(-100, -1), // stock out
            ]),
            'reason' => fake()->randomElement([
                'Restock delivery',
                'Customer order',
                'Damaged goods',
                'Stock take adjustment',
                'Return to supplier',
                'Warehouse transfer',
            ]),
            'created_at' => fake()->dateTimeBetween('-6 months', 'now'),
        ];
    }
}
