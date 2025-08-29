<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InvoiceItem>
 */
class InvoiceItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $quantity = fake()->numberBetween(1, 10);
        $unitPrice = fake()->randomFloat(2, 10, 500);
        $lineTotal = $quantity * $unitPrice;
        $taxRate = fake()->randomElement([0, 5, 10, 15, 18, 20]);
        $taxAmount = $lineTotal * ($taxRate / 100);
        $discountRate = fake()->randomElement([0, 0, 0, 5, 10, 15]);
        $discountAmount = $lineTotal * ($discountRate / 100);

        return [
            'invoice_id' => Invoice::factory(),
            'product_id' => Product::factory(),
            'description' => fake()->sentence(4),
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'line_total' => $lineTotal,
            'tax_rate' => $taxRate,
            'tax_amount' => $taxAmount,
            'discount_rate' => $discountRate,
            'discount_amount' => $discountAmount,
        ];
    }

    /**
     * Indicate that the item has no discount.
     */
    public function noDiscount(): static
    {
        return $this->state(fn (array $attributes) => [
            'discount_rate' => 0,
            'discount_amount' => 0,
        ]);
    }

    /**
     * Indicate that the item has high tax.
     */
    public function highTax(): static
    {
        return $this->state(function (array $attributes) {
            $taxRate = 25;
            $taxAmount = $attributes['line_total'] * ($taxRate / 100);
            
            return [
                'tax_rate' => $taxRate,
                'tax_amount' => $taxAmount,
            ];
        });
    }
}