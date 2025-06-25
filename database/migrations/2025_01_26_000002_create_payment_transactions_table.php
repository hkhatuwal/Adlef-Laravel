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
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('api_client_id')->constrained()->onDelete('cascade');
            $table->string('transaction_id', 100)->unique(); // Our internal transaction ID
            $table->string('gateway_transaction_id')->nullable(); // Gateway's transaction ID
            $table->string('gateway_name', 50); // payop, stripe, etc.
            $table->string('client_order_id')->nullable(); // Client's order reference
            
            // Payment details
            $table->decimal('amount', 15, 2);
            $table->string('currency', 3);
            $table->string('description')->nullable();
            
            // Customer details
            $table->string('customer_email')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            
            // Payment URLs
            $table->text('payment_url')->nullable();
            $table->text('return_url')->nullable();
            $table->text('cancel_url')->nullable();
            
            // Status and tracking
            $table->enum('status', ['pending', 'processing', 'completed', 'failed', 'cancelled', 'refunded'])->default('pending');
            $table->string('gateway_status')->nullable(); // Raw status from gateway
            $table->json('gateway_response')->nullable(); // Full response from gateway
            $table->text('failure_reason')->nullable();
            
            // Fees and amounts
            $table->decimal('gateway_fee', 15, 2)->nullable();
            $table->decimal('our_fee', 15, 2)->nullable();
            $table->decimal('net_amount', 15, 2)->nullable();
            
            // Webhook tracking
            $table->integer('webhook_attempts')->default(0);
            $table->timestamp('webhook_sent_at')->nullable();
            $table->json('webhook_responses')->nullable(); // Track webhook delivery attempts
            
            // Additional metadata
            $table->json('metadata')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            
            $table->timestamps();

            // Indexes
            $table->index(['api_client_id', 'status']);
            $table->index(['gateway_name', 'gateway_transaction_id']);
            $table->index(['transaction_id', 'status']);
            $table->index('client_order_id');
            $table->index(['created_at', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
}; 