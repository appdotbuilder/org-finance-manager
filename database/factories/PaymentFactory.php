<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'payment_reference' => 'PAY-' . fake()->unique()->numerify('##########'),
            'amount' => fake()->randomFloat(2, 50, 2000),
            'currency' => 'USD',
            'payment_method' => fake()->randomElement(['mobile_money', 'card', 'bank_transfer', 'cash']),
            'status' => fake()->randomElement(['pending', 'completed', 'failed', 'cancelled']),
            'gateway_provider' => fake()->optional()->randomElement(['ClickPesa', 'Stripe', 'PayPal']),
            'gateway_transaction_id' => fake()->optional()->uuid(),
            'gateway_response' => [
                'transaction_id' => fake()->uuid(),
                'status_code' => '200',
                'message' => 'Payment processed successfully',
                'fee' => fake()->randomFloat(2, 1, 10),
            ],
            'payment_date' => fake()->dateTimeBetween('-6 months', 'now'),
            'notes' => fake()->optional()->sentence(),
        ];
    }

    /**
     * Indicate that the payment is completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'payment_date' => fake()->dateTimeBetween('-3 months', 'now'),
        ]);
    }

    /**
     * Indicate that the payment is via mobile money.
     */
    public function mobileMoney(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_method' => 'mobile_money',
            'gateway_provider' => 'ClickPesa',
            'gateway_response' => [
                'transaction_id' => fake()->uuid(),
                'mobile_number' => fake()->phoneNumber(),
                'operator' => fake()->randomElement(['Vodacom', 'Airtel', 'Tigo']),
                'status_code' => '200',
                'message' => 'Mobile money payment successful',
            ],
        ]);
    }
}