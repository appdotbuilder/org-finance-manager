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
        Schema::create('credit_notes', function (Blueprint $table) {
            $table->id();
            $table->string('credit_note_number')->unique();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('invoice_id')->nullable()->constrained()->onDelete('set null');
            $table->date('issue_date');
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('USD');
            $table->enum('reason', ['refund', 'adjustment', 'cancellation', 'discount', 'other']);
            $table->text('description');
            $table->enum('status', ['pending', 'applied', 'refunded'])->default('pending');
            $table->decimal('applied_amount', 12, 2)->default(0);
            $table->decimal('refunded_amount', 12, 2)->default(0);
            $table->timestamps();
            
            // Indexes
            $table->index('credit_note_number');
            $table->index('customer_id');
            $table->index('invoice_id');
            $table->index('status');
            $table->index('issue_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_notes');
    }
};