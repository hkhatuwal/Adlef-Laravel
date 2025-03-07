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
        Schema::create('user_admin_deposit_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\User::class)->constrained()->onDelete('cascade')   ;
            $table->foreignIdFor(\App\Models\AdminDepositAccount::class)->constrained()->onDelete('cascade')   ;
            $table->timestamps();

            // Prevent duplicate assignments
            $table->unique(['user_id', 'admin_deposit_account_id'], 'user_admin_deposit_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_admin_deposit_accounts');
    }
};
