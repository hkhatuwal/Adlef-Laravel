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
        Schema::create('user_payment_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('max_api_clients')->default(5);
            $table->json('allowed_payment_providers')->nullable();
            $table->decimal('daily_limit', 15, 2)->default(10000.00);
            $table->decimal('monthly_limit', 15, 2)->default(100000.00);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->unique('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_payment_settings');
    }
};
