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
        Schema::create('admin_deposit_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('admin_deposit_account_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_deposit_account_id')
                  ->constrained('admin_deposit_accounts')
                  ->onDelete('cascade');
            $table->string('field_name');
            $table->string('field_value');
            $table->string('field_type')->default('text'); // text, number, date, etc.
            $table->boolean('is_required')->default(false);
            $table->integer('display_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_deposit_account_fields');
        Schema::dropIfExists('admin_deposit_accounts');
    }
};
