<?php

namespace Database\Factories;

use App\Models\Tenant;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subtotal = fake()->randomFloat(2, 10000, 500000);
        $taxRate = fake()->randomFloat(2, 0, 0.11); // 0-11% tax
        $taxAmount = $subtotal * $taxRate;
        $discountAmount = fake()->boolean(30) ? fake()->randomFloat(2, 0, $subtotal * 0.2) : 0;
        $totalAmount = $subtotal + $taxAmount - $discountAmount;
        
        return [
            'tenant_id' => Tenant::factory(),
            'user_id' => User::factory(),
            'transaction_number' => Transaction::generateTransactionNumber(),
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'discount_amount' => $discountAmount,
            'total_amount' => $totalAmount,
            'payment_method' => fake()->randomElement(['cash', 'transfer', 'card', 'ewallet']),
            'customer_name' => fake()->boolean(70) ? fake()->name() : null,
            'notes' => fake()->boolean(20) ? fake()->sentence() : null,
        ];
    }

    /**
     * Indicate that the transaction is cash payment.
     */
    public function cash(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_method' => 'cash',
        ]);
    }

    /**
     * Indicate that the transaction has no discount.
     */
    public function noDiscount(): static
    {
        return $this->state(fn (array $attributes) => [
            'discount_amount' => 0,
        ]);
    }

    /**
     * Indicate that the transaction is from today.
     */
    public function today(): static
    {
        return $this->state(fn (array $attributes) => [
            'created_at' => fake()->dateTimeBetween('today', 'now'),
        ]);
    }
}