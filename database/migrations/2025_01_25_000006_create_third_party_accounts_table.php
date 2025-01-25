<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('third_party_accounts', function (Blueprint $table) {
            $table->id();
            $table->enum('third_party_type', ['Individual', 'Company']);
            $table->foreignId('bank_account_id')->unique()->constrained()->onDelete('cascade');
            $table->foreignId('individual_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('third_party_accounts');
    }
};
