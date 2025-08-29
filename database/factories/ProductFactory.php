<?php

namespace Database\Factories;

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
        return [
            'name' => fake()->words(3, true),
            'description' => fake()->paragraph(),
            'sku' => fake()->unique()->regexify('[A-Z]{3}[0-9]{4}'),
            'price' => fake()->randomFloat(2, 10, 1000),
            'type' => fake()->randomElement(['product', 'service']),
            'tax_rate' => fake()->randomElement([0, 5, 10, 15, 20]),
            'is_active' => fake()->boolean(90),
            'metadata' => [
                'category' => fake()->word(),
                'unit' => fake()->randomElement(['piece', 'hour', 'kg', 'meter']),
                'weight' => fake()->optional()->randomFloat(2, 0.1, 50),
            ],
        ];
    }

    /**
     * Indicate that the product is a service.
     */
    public function service(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'service',
            'metadata' => [
                'category' => 'Service',
                'unit' => 'hour',
                'duration' => fake()->numberBetween(1, 8),
            ],
        ]);
    }

    /**
     * Indicate that the product is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}