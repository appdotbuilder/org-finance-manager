<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subtotal = fake()->randomFloat(2, 100, 5000);
        $taxAmount = $subtotal * 0.18; // 18% tax
        $discountAmount = fake()->randomFloat(2, 0, $subtotal * 0.1);
        $totalAmount = $subtotal + $taxAmount - $discountAmount;
        $paidAmount = fake()->randomFloat(2, 0, $totalAmount);
        
        return [
            'invoice_number' => 'INV-' . fake()->unique()->numerify('######'),
            'customer_id' => Customer::factory(),
            'issue_date' => fake()->dateTimeBetween('-3 months', 'now'),
            'due_date' => fake()->dateTimeBetween('now', '+2 months'),
            'status' => fake()->randomElement(['draft', 'issued', 'partially_paid', 'fully_paid', 'overdue']),
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'discount_amount' => $discountAmount,
            'total_amount' => $totalAmount,
            'paid_amount' => $paidAmount,
            'balance_due' => $totalAmount - $paidAmount,
            'currency' => 'USD',
            'notes' => fake()->optional()->paragraph(),
            'is_recurring' => fake()->boolean(20),
            'recurring_frequency' => fake()->optional()->randomElement(['monthly', 'quarterly', 'yearly']),
            'next_recurring_date' => fake()->optional()->dateTimeBetween('now', '+1 year'),
            'billing_address' => [
                'name' => fake()->company(),
                'address' => fake()->address(),
                'city' => fake()->city(),
                'postal_code' => fake()->postcode(),
                'country' => fake()->country(),
            ],
        ];
    }

    /**
     * Indicate that the invoice is overdue.
     */
    public function overdue(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'overdue',
            'due_date' => fake()->dateTimeBetween('-2 months', '-1 day'),
        ]);
    }

    /**
     * Indicate that the invoice is fully paid.
     */
    public function paid(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'fully_paid',
                'paid_amount' => $attributes['total_amount'],
                'balance_due' => 0,
            ];
        });
    }

    /**
     * Indicate that the invoice is recurring.
     */
    public function recurring(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_recurring' => true,
            'recurring_frequency' => fake()->randomElement(['monthly', 'quarterly', 'yearly']),
            'next_recurring_date' => fake()->dateTimeBetween('now', '+1 year'),
        ]);
    }
}