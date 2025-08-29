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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->string('payment_reference')->unique();
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('USD');
            $table->enum('payment_method', ['mobile_money', 'card', 'bank_transfer', 'cash', 'check']);
            $table->enum('status', ['pending', 'completed', 'failed', 'cancelled'])->default('pending');
            $table->string('gateway_provider')->nullable()->comment('e.g., ClickPesa');
            $table->string('gateway_transaction_id')->nullable();
            $table->json('gateway_response')->nullable();
            $table->datetime('payment_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index('payment_reference');
            $table->index('customer_id');
            $table->index('status');
            $table->index('payment_method');
            $table->index('payment_date');
            $table->index('gateway_transaction_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};