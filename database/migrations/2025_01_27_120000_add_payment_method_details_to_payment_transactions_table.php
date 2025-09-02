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
        // This migration is now obsolete as payment method details are handled in a separate table
        // The payment_methods table migration handles all payment method details
        // This migration is kept for reference but does nothing
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration is now obsolete as payment method details are handled in a separate table
        // No rollback needed as no columns were added
    }
};
