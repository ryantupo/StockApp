<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'name' => ucfirst(fake()->words(3, true)),
            'sku' => strtoupper(fake()->unique()->bothify('??-#####')),
            'quantity' => fake()->numberBetween(0, 500),
            'reorder_threshold' => fake()->numberBetween(10, 50),
        ];
    }

    public function belowThreshold(): static
    {
        return $this->state(fn(array $attributes) => [
            'quantity' => fake()->numberBetween(0, $attributes['reorder_threshold'] - 1),
            'low_stock_alerted_at' => now(),
        ]);
    }
}
