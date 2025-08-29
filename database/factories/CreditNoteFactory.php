<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CreditNote>
 */
class CreditNoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $amount = fake()->randomFloat(2, 25, 500);
        $appliedAmount = fake()->randomFloat(2, 0, $amount);
        $refundedAmount = fake()->randomFloat(2, 0, $amount - $appliedAmount);

        return [
            'credit_note_number' => 'CN-' . fake()->unique()->numerify('######'),
            'customer_id' => Customer::factory(),
            'invoice_id' => fake()->optional()->passthrough(Invoice::factory()),
            'issue_date' => fake()->dateTimeBetween('-3 months', 'now'),
            'amount' => $amount,
            'currency' => 'USD',
            'reason' => fake()->randomElement(['refund', 'adjustment', 'cancellation', 'discount', 'other']),
            'description' => fake()->sentence(),
            'status' => fake()->randomElement(['pending', 'applied', 'refunded']),
            'applied_amount' => $appliedAmount,
            'refunded_amount' => $refundedAmount,
        ];
    }

    /**
     * Indicate that the credit note is for a refund.
     */
    public function refund(): static
    {
        return $this->state(fn (array $attributes) => [
            'reason' => 'refund',
            'status' => 'refunded',
            'refunded_amount' => $attributes['amount'],
            'applied_amount' => 0,
        ]);
    }

    /**
     * Indicate that the credit note is applied.
     */
    public function applied(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'applied',
            'applied_amount' => $attributes['amount'],
            'refunded_amount' => 0,
        ]);
    }
}