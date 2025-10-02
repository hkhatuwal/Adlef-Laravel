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
        Schema::create('oppwa_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id')->unique(); // Our internal transaction ID
            $table->string('external_order_id')->nullable(); // External order ID from client
            $table->string('oppwa_checkout_id')->nullable(); // OPPWA checkout session ID
            $table->string('oppwa_payment_id')->nullable(); // OPPWA payment ID after completion
            $table->string('api_client_id')->nullable(); // API client who initiated the transaction
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3);
            $table->string('payment_type', 10)->default('DB'); // DB = Debit, PA = Preauthorization
            $table->text('description')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            $table->string('payment_url')->nullable(); // URL to redirect user for payment
            $table->string('result_url')->nullable(); // URL to redirect after payment completion
            $table->string('callback_url')->nullable(); // Webhook callback URL
            $table->enum('status', [
                'pending',
                'processing', 
                'completed',
                'failed',
                'cancelled',
                'expired'
            ])->default('pending');
            $table->string('oppwa_status')->nullable(); // OPPWA specific status
            $table->text('oppwa_response')->nullable(); // Raw OPPWA response
            $table->text('failure_reason')->nullable();
            $table->json('metadata')->nullable(); // Additional data
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('expires_at')->nullable(); // Checkout session expiration
            $table->timestamps();

            // Indexes
            $table->index(['transaction_id']);
            $table->index(['external_order_id']);
            $table->index(['oppwa_checkout_id']);
            $table->index(['api_client_id']);
            $table->index(['status']);
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oppwa_transactions');
    }
};
