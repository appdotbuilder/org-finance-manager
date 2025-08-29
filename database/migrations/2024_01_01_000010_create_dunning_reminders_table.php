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
        Schema::create('dunning_reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['email', 'sms', 'both']);
            $table->integer('reminder_level')->default(1)->comment('1=first reminder, 2=second, etc.');
            $table->datetime('sent_at');
            $table->enum('status', ['sent', 'delivered', 'failed']);
            $table->text('message_content')->nullable();
            $table->json('delivery_response')->nullable();
            $table->datetime('next_reminder_at')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index('invoice_id');
            $table->index('sent_at');
            $table->index('status');
            $table->index('next_reminder_at');
            $table->index(['invoice_id', 'reminder_level']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dunning_reminders');
    }
};