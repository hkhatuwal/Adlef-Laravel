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
        Schema::create('transfer_out_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_transfer_id')->constrained('asset_transfers')->onDelete('cascade');
            $table->string('transaction_id')->unique(); // The actual transaction ID from payment processor/bank
            $table->string('payment_status')->default('pending'); // pending, sent, confirmed, failed
            $table->timestamp('sent_at')->nullable(); // When payment was sent
            $table->timestamp('confirmed_at')->nullable(); // When payment was confirmed
            $table->text('payment_reference')->nullable(); // Bank/processor reference
            $table->text('failure_reason')->nullable(); // Reason if failed
            $table->json('payment_metadata')->nullable(); // Additional payment processor data
            $table->timestamps();
            
            // Indexes
            $table->index('asset_transfer_id');
            $table->index('transaction_id');
            $table->index('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfer_out_payments');
    }
};
