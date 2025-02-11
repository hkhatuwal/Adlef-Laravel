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
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g., Bitcoin, USDT
            $table->string('symbol')->unique(); // e.g., BTC, USDT
            $table->string('icon'); // e.g., BTC, USDT
            $table->decimal('conversion_rate', 16, 8)->default(1.00000000); // Conversion rate to USD
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('currencies');
    }
};
