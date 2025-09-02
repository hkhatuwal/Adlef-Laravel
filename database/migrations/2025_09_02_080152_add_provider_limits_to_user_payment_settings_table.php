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
        Schema::table('user_payment_settings', function (Blueprint $table) {
            // Remove old global limits
            $table->dropColumn(['daily_limit', 'monthly_limit']);
            
            // Add provider-specific limits
            $table->json('provider_limits')->nullable()->after('allowed_payment_providers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_payment_settings', function (Blueprint $table) {
            // Restore old global limits
            $table->decimal('daily_limit', 15, 2)->default(10000.00);
            $table->decimal('monthly_limit', 15, 2)->default(100000.00);
            
            // Remove provider-specific limits
            $table->dropColumn('provider_limits');
        });
    }
};
