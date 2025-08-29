<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PaymentAllocation>
 */
class PaymentAllocationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'payment_id' => Payment::factory(),
            'invoice_id' => Invoice::factory(),
            'allocated_amount' => fake()->randomFloat(2, 50, 1000),
            'allocated_at' => fake()->dateTimeBetween('-6 months', 'now'),
        ];
    }

    /**
     * Indicate that the allocation is recent.
     */
    public function recent(): static
    {
        return $this->state(fn (array $attributes) => [
            'allocated_at' => fake()->dateTimeBetween('-1 week', 'now'),
        ]);
    }
}