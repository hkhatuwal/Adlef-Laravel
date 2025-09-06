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
        Schema::create('payment_gateway_wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('gateway_name'); // e.g., 'payop', 'ngenius', etc.
            $table->decimal('balance_usd', 15, 2)->default(0.00); // Balance in USD
            $table->timestamps();

            // Ensure one wallet per user per gateway
            $table->unique(['user_id', 'gateway_name']);
            
            // Index for faster queries
            $table->index(['user_id', 'gateway_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_gateway_wallets');
    }
};