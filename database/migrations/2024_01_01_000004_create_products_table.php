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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('sku')->unique()->nullable();
            $table->decimal('price', 10, 2);
            $table->enum('type', ['product', 'service'])->default('product');
            $table->decimal('tax_rate', 5, 2)->default(0)->comment('Tax rate as percentage');
            $table->boolean('is_active')->default(true);
            $table->json('metadata')->nullable()->comment('Additional product information');
            $table->timestamps();
            
            // Indexes
            $table->index('name');
            $table->index('sku');
            $table->index('type');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};