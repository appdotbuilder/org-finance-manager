<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->date('issue_date');
            $table->date('due_date');
            $table->enum('status', ['draft', 'issued', 'partially_paid', 'fully_paid', 'overdue', 'cancelled'])->default('draft');
            $table->decimal('subtotal', 12, 2);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2);
            $table->decimal('paid_amount', 12, 2)->default(0);
            $table->decimal('balance_due', 12, 2);
            $table->string('currency', 3)->default('USD');
            $table->text('notes')->nullable();
            $table->boolean('is_recurring')->default(false);
            $table->enum('recurring_frequency', ['monthly', 'quarterly', 'yearly'])->nullable();
            $table->date('next_recurring_date')->nullable();
            $table->json('billing_address')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index('invoice_number');
            $table->index('customer_id');
            $table->index('status');
            $table->index('due_date');
            $table->index('issue_date');
            $table->index(['status', 'due_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};