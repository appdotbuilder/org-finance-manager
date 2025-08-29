<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'email' => fake()->unique()->companyEmail(),
            'phone' => fake()->phoneNumber(),
            'company' => fake()->company(),
            'billing_address' => fake()->address(),
            'shipping_address' => fake()->address(),
            'tax_number' => fake()->optional()->numerify('TAX###########'),
            'status' => fake()->randomElement(['active', 'inactive']),
            'contact_details' => [
                'contact_person' => fake()->name(),
                'website' => fake()->optional()->domainName(),
                'industry' => fake()->optional()->word(),
            ],
        ];
    }

    /**
     * Indicate that the customer is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'inactive',
        ]);
    }

    /**
     * Indicate that the customer is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }
}