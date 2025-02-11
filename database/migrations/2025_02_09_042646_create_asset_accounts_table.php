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
        Schema::create('asset_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignIdFor(\App\Models\Currency::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(\App\Models\User::class)->constrained()->onDelete('cascade');
            $table->decimal('balance', 16, 2)->default(0.00); // Account balance
            $table->string('account_number')->nullable(); // Bank or wallet address
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_accounts');
    }
};
