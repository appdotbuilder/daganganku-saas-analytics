<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StockMovement>
 */
class StockMovementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement(['in', 'out', 'adjustment']);
        $stockBefore = fake()->numberBetween(0, 100);
        $quantity = fake()->numberBetween(1, 50);
        
        $stockAfter = match($type) {
            'in' => $stockBefore + $quantity,
            'out' => max(0, $stockBefore - $quantity),
            'adjustment' => fake()->numberBetween(0, 100),
            default => $stockBefore,
        };
        
        return [
            'product_id' => Product::factory(),
            'user_id' => User::factory(),
            'type' => $type,
            'quantity' => $quantity,
            'stock_before' => $stockBefore,
            'stock_after' => $stockAfter,
            'reference_type' => fake()->randomElement(['transaction', 'adjustment', 'restock']),
            'reference_id' => fake()->numberBetween(1, 1000),
            'notes' => fake()->boolean(30) ? fake()->sentence() : null,
        ];
    }

    /**
     * Indicate that the stock movement is incoming.
     */
    public function stockIn(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'in',
        ]);
    }

    /**
     * Indicate that the stock movement is outgoing.
     */
    public function stockOut(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'out',
        ]);
    }
}