<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Product;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Create customers
        $customers = Customer::factory(20)->create();
        
        // Create products
        $products = collect([
            Product::factory()->create([
                'name' => 'Web Development Services',
                'type' => 'service',
                'price' => 150.00,
                'tax_rate' => 18,
            ]),
            Product::factory()->create([
                'name' => 'Graphic Design Package',
                'type' => 'service',
                'price' => 89.99,
                'tax_rate' => 18,
            ]),
            Product::factory()->create([
                'name' => 'Premium Software License',
                'type' => 'product',
                'price' => 299.99,
                'tax_rate' => 18,
            ]),
            Product::factory()->create([
                'name' => 'Consulting Hours',
                'type' => 'service',
                'price' => 120.00,
                'tax_rate' => 18,
            ]),
            Product::factory()->create([
                'name' => 'Mobile App Development',
                'type' => 'service',
                'price' => 2500.00,
                'tax_rate' => 18,
            ]),
        ]);

        // Add more random products
        Product::factory(15)->create();

        // Create invoices with items
        $customers->each(function (Customer $customer) use ($products) {
            // Create 2-5 invoices per customer
            $invoiceCount = random_int(2, 5);
            
            for ($i = 0; $i < $invoiceCount; $i++) {
                $invoice = Invoice::factory()->create([
                    'customer_id' => $customer->id,
                    'invoice_number' => 'INV-' . str_pad((string)(Invoice::count() + 1), 6, '0', STR_PAD_LEFT),
                    'status' => fake()->randomElement(['draft', 'issued', 'partially_paid', 'fully_paid', 'overdue']),
                ]);

                // Create 1-4 items per invoice
                $itemCount = random_int(1, 4);
                
                for ($j = 0; $j < $itemCount; $j++) {
                    $product = $products->random();
                    $quantity = random_int(1, 5);
                    $unitPrice = $product->price;
                    $lineTotal = $quantity * $unitPrice;
                    $taxAmount = $lineTotal * ($product->tax_rate / 100);
                    $discountAmount = fake()->boolean(30) ? $lineTotal * (fake()->randomFloat(2, 5, 15) / 100) : 0;

                    InvoiceItem::create([
                        'invoice_id' => $invoice->id,
                        'product_id' => $product->id,
                        'description' => $product->name,
                        'quantity' => $quantity,
                        'unit_price' => $unitPrice,
                        'line_total' => $lineTotal,
                        'tax_rate' => $product->tax_rate,
                        'tax_amount' => $taxAmount,
                        'discount_rate' => $discountAmount > 0 ? ($discountAmount / $lineTotal) * 100 : 0,
                        'discount_amount' => $discountAmount,
                    ]);
                }

                // Recalculate invoice totals
                $invoice->refresh();
                $subtotal = $invoice->items->sum('line_total');
                $totalTax = $invoice->items->sum('tax_amount');
                $totalDiscount = $invoice->items->sum('discount_amount');
                $totalAmount = $subtotal + $totalTax - $totalDiscount;
                
                $paidAmount = 0;
                if ($invoice->status === 'fully_paid') {
                    $paidAmount = $totalAmount;
                } elseif ($invoice->status === 'partially_paid') {
                    $paidAmount = $totalAmount * fake()->randomFloat(2, 0.2, 0.8);
                }

                $invoice->update([
                    'subtotal' => $subtotal,
                    'tax_amount' => $totalTax,
                    'discount_amount' => $totalDiscount,
                    'total_amount' => $totalAmount,
                    'paid_amount' => $paidAmount,
                    'balance_due' => $totalAmount - $paidAmount,
                ]);
            }
        });
    }
}