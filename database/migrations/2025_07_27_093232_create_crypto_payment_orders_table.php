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
        Schema::create('crypto_payment_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->unique()->index(); // Our internal order ID
            $table->string('transaction_id')->nullable()->index(); // External transaction ID when payment is made
            $table->string('gateway_name')->default('trongrid'); // Payment gateway name
            $table->string('wallet_address')->index(); // The wallet address payment should be sent to
            $table->decimal('original_amount', 20, 8); // Original requested amount
            $table->decimal('fingerprint_amount', 20, 8); // Amount with fingerprint for identification
            $table->string('fingerprint_code', 10); // The fingerprint code (e.g., "0023" for $100.0023)
            $table->string('original_currency', 10); // Original currency requested
            $table->string('payment_currency', 10)->default('USDT'); // Currency to be paid (USDT for TronGrid)
            $table->string('network', 20)->default('TRON'); // Blockchain network
            
            // Customer information
            $table->string('customer_email')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            
            // Order details
            $table->text('description')->nullable();
            $table->json('metadata')->nullable(); // Additional data
            $table->json('items')->nullable(); // Order items
            
            // URLs
            $table->string('return_url')->nullable();
            $table->string('cancel_url')->nullable();
            $table->string('payment_url')->nullable(); // Our checkout page URL
            
            // Status and tracking
            $table->enum('status', ['pending', 'paid', 'completed', 'failed', 'cancelled', 'expired'])->default('pending');
            $table->timestamp('expires_at')->nullable(); // When the payment expires (30 min default)
            $table->timestamp('paid_at')->nullable(); // When payment was received
            $table->timestamp('completed_at')->nullable(); // When order was completed
            
            // Webhook and external tracking
            $table->string('gateway_transaction_id')->nullable(); // Transaction ID from blockchain
            $table->json('gateway_response')->nullable(); // Raw webhook/API response
            $table->integer('webhook_attempts')->default(0);
            $table->timestamp('webhook_sent_at')->nullable();
            
            // Relations
            $table->foreignId('api_client_id')->nullable()->constrained()->onDelete('cascade');
            
            $table->timestamps();
            
            // Indexes for efficient queries
            $table->index(['status', 'created_at']);
            $table->index(['expires_at', 'status']);
            $table->index(['fingerprint_amount', 'wallet_address']); // For matching payments
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crypto_payment_orders');
    }
};
