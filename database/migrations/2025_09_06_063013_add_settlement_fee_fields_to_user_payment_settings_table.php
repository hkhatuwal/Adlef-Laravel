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
            $table->enum('fee_type', ['percentage', 'fixed'])->default('percentage')->after('is_active');
            $table->decimal('fee_percentage', 5, 2)->default(0.00)->after('fee_type');
            $table->decimal('fee_fixed', 15, 2)->default(0.00)->after('fee_percentage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_payment_settings', function (Blueprint $table) {
            $table->dropColumn(['fee_type', 'fee_percentage', 'fee_fixed']);
        });
    }
};
