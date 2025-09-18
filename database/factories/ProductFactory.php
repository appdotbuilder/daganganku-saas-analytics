<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $costPrice = fake()->randomFloat(2, 5000, 50000);
        $markup = fake()->randomFloat(2, 1.2, 2.5); // 20% to 150% markup
        
        return [
            'tenant_id' => Tenant::factory(),
            'category_id' => Category::factory(),
            'name' => fake()->randomElement([
                'Nasi Goreng', 'Mie Ayam', 'Gado-gado', 'Soto Ayam', 'Bakso',
                'Es Teh', 'Kopi', 'Jus Jeruk', 'Air Mineral', 'Teh Botol',
                'Kerupuk', 'Kacang', 'Permen', 'Coklat', 'Biskuit',
                'Roti Tawar', 'Donat', 'Croissant', 'Muffin', 'Kue Tart'
            ]),
            'sku' => fake()->unique()->bothify('SKU-###-???'),
            'description' => fake()->sentence(),
            'cost_price' => $costPrice,
            'selling_price' => $costPrice * $markup,
            'stock_quantity' => fake()->numberBetween(0, 100),
            'minimum_stock' => fake()->numberBetween(5, 20),
            'unit' => fake()->randomElement(['pcs', 'kg', 'gram', 'liter', 'box', 'pack']),
            'is_active' => fake()->boolean(90),
        ];
    }

    /**
     * Indicate that the product has low stock.
     */
    public function lowStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock_quantity' => fake()->numberBetween(0, 5),
            'minimum_stock' => fake()->numberBetween(10, 20),
        ]);
    }

    /**
     * Indicate that the product is out of stock.
     */
    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock_quantity' => 0,
        ]);
    }

    /**
     * Indicate that the product is premium priced.
     */
    public function premium(): static
    {
        return $this->state(function (array $attributes) {
            $costPrice = fake()->randomFloat(2, 50000, 200000);
            return [
                'cost_price' => $costPrice,
                'selling_price' => $costPrice * fake()->randomFloat(2, 1.5, 3),
            ];
        });
    }
}