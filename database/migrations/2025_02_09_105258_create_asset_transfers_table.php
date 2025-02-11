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
        Schema::create('asset_transfers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('to_account_id');
            $table->unsignedBigInteger('from_account_id')->nullable();
            $table->foreignIdFor(\App\Models\Currency::class)->constrained()->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->string('status')->default('pending');
            $table->enum('transfer_type', [\App\Models\AssetTransfer::TYPE_IN, \App\Models\AssetTransfer::TYPE_OUT,\App\Models\AssetTransfer::TYPE_THIRD_PARTY]);
            $table->timestamps();
//            $table->foreign('from_account_id')->references('id')->on('asset_accounts')->onDelete('cascade');
//            $table->foreign('to_account_id')->references('id')->on('asset_accounts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_transfers');
    }
};
