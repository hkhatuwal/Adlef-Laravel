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
        Schema::create('tg_temporary_wallets', function (Blueprint $table) {
            $table->id();
            $table->string('wallet_address')->index();
            $table->string('wallet_address_hex');
            $table->string('public_key');
            $table->string('private_key');
            $table->json('raw_response');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tg_temporary_wallets');
    }
};
