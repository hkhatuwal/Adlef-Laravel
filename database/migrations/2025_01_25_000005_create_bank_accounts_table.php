<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->enum('account_type', ['Own', 'ThirdParty']);
            $table->string('account_holder_name');
            $table->string('bank_name');
            $table->string('swift')->nullable();
            $table->string('account_number', 50);
            $table->string('shortcode', 20)->nullable();
            $table->string('branch_code', 20)->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('is_verified')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bank_accounts');
    }
};
