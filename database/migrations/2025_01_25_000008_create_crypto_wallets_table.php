<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('crypto_wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Currency::class);
            $table->string('wallet_address');
            $table->string('alias', 100)->nullable();
            $table->foreignIdFor(\App\Models\User::class)->constrained()->onDelete('cascade');
            $table->boolean('is_verified')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('crypto_wallets');
    }
};
