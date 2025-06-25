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
        Schema::create('api_clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('company_name')->nullable();
            $table->string('api_key', 64)->unique();
            $table->string('secret_key', 64);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_sandbox')->default(true);
            $table->json('allowed_ips')->nullable(); // Array of allowed IP addresses
            $table->json('webhook_urls')->nullable(); // Array of webhook URLs
            $table->json('allowed_currencies')->nullable(); // Array of allowed currencies
            $table->decimal('daily_limit', 15, 2)->nullable();
            $table->decimal('monthly_limit', 15, 2)->nullable();
            $table->decimal('daily_used', 15, 2)->default(0);
            $table->decimal('monthly_used', 15, 2)->default(0);
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['api_key', 'is_active']);
            $table->index('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_clients');
    }
}; 