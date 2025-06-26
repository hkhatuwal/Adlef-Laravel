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
        Schema::table('payment_transactions', function (Blueprint $table) {
            // Make gateway_name nullable since it's not selected initially
            $table->string('gateway_name', 50)->nullable()->change();
            
            // Add checkout URL for custom checkout page
            $table->text('checkout_url')->nullable()->after('payment_url');
            
            // Add checkout session for tracking
            $table->string('checkout_session_id')->nullable()->after('transaction_id');
            
            // Add available payment methods (JSON array)
            $table->json('available_gateways')->nullable()->after('gateway_name');
            
            // Update status enum to include checkout_pending
            $table->enum('status', [
                'checkout_pending',    // Initial request, waiting for gateway selection
                'pending',            // Gateway selected, payment initiated
                'processing', 
                'completed', 
                'failed', 
                'cancelled', 
                'refunded'
            ])->default('checkout_pending')->change();
            
            // Add stage tracking
            $table->enum('stage', ['checkout', 'gateway_processing', 'completed'])->default('checkout')->after('status');
            
            // Add index for checkout_session_id
            $table->index('checkout_session_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_transactions', function (Blueprint $table) {
            // Revert gateway_name to not nullable
            $table->string('gateway_name', 50)->nullable(false)->change();
            
            // Drop new columns
            $table->dropColumn([
                'checkout_url',
                'checkout_session_id', 
                'available_gateways',
                'stage'
            ]);
            
            // Revert status enum
            $table->enum('status', ['pending', 'processing', 'completed', 'failed', 'cancelled', 'refunded'])->default('pending')->change();
            
            // Drop index
            $table->dropIndex(['checkout_session_id']);
        });
    }
}; 