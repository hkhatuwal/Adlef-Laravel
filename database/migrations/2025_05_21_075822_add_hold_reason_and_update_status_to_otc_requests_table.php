<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('otc_requests', function (Blueprint $table) {
            // Add the hold_reason field
            $table->text('hold_reason')->nullable()->after('failure_reason');
            
            // Need to modify the enum values in a database-specific way
            // MySQL approach
            DB::statement("ALTER TABLE otc_requests MODIFY COLUMN status ENUM('pending', 'completed', 'failed', 'cancelled', 'on-hold') NOT NULL DEFAULT 'pending'");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('otc_requests', function (Blueprint $table) {
            // Remove the hold_reason field
            $table->dropColumn('hold_reason');
            
            // Revert the enum values in a database-specific way
            // MySQL approach
            DB::statement("ALTER TABLE otc_requests MODIFY COLUMN status ENUM('pending', 'completed', 'failed', 'cancelled') NOT NULL DEFAULT 'pending'");
        });
    }
};
