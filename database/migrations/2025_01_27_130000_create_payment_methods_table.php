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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_transaction_id')->constrained()->onDelete('cascade');
            
            // Payment method type (card, bank_transfer, wallet, etc.)
            $table->string('payment_method_type')->nullable();
            
            // Payment method subtype for more granular categorization
            $table->string('payment_method_subtype')->nullable();
            
            // Card-specific details
            $table->string('card_brand')->nullable(); // visa, mastercard, amex, etc.
            $table->string('card_type')->nullable(); // credit, debit, prepaid
            $table->string('card_last_four')->nullable();
            $table->string('card_exp_month')->nullable();
            $table->string('card_exp_year')->nullable();
            $table->string('card_country')->nullable();
            $table->string('card_issuer')->nullable();
            $table->string('card_funding')->nullable(); // credit, debit, prepaid, unknown
            
            // Bank transfer details
            $table->string('bank_name')->nullable();
            $table->string('bank_code')->nullable();
            $table->string('account_type')->nullable(); // checking, savings, business
            $table->string('account_last_four')->nullable();
            $table->string('routing_number')->nullable();
            $table->string('iban_last_four')->nullable();
            $table->string('swift_code')->nullable();
            
            // Digital wallet details
            $table->string('wallet_provider')->nullable(); // paypal, apple_pay, google_pay, etc.
            $table->string('wallet_account_id')->nullable();
            $table->string('wallet_email')->nullable();
            
            // Cryptocurrency details
            $table->string('crypto_currency')->nullable(); // BTC, ETH, USDT, etc.
            $table->string('crypto_network')->nullable(); // ethereum, tron, bitcoin, etc.
            $table->string('crypto_address')->nullable();
            $table->string('crypto_tx_hash')->nullable();
            
            // Mobile payment details
            $table->string('mobile_carrier')->nullable();
            $table->string('mobile_number')->nullable();
            
            // Alternative payment method details
            $table->string('alt_payment_provider')->nullable(); // qiwi, webmoney, etc.
            $table->string('alt_payment_account')->nullable();
            
            // General payment method information
            $table->json('payment_method_details')->nullable(); // For additional gateway-specific data
            $table->string('payment_country')->nullable();
            $table->boolean('is_recurring_capable')->default(false);
            $table->boolean('requires_authentication')->default(false); // 3DS, etc.
            
            // Risk and verification details
            $table->string('verification_status')->nullable(); // verified, unverified, failed
            $table->string('risk_score')->nullable();
            $table->json('fraud_checks')->nullable(); // Results from fraud detection
            
            $table->timestamps();
            
            // Add indexes for common queries
            $table->index(['payment_method_type', 'payment_transaction_id']);
            $table->index(['card_brand', 'card_type']);
            $table->index(['bank_name', 'account_type']);
            $table->index(['wallet_provider']);
            $table->index(['crypto_currency', 'crypto_network']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
