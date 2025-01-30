<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('country', 100);
            $table->date('registration_date');
            $table->string('registration_number')->nullable();
            $table->string('email')->nullable();
            $table->string('contact', 20)->nullable();
            $table->enum('relationship', );
            $table->string('registration_proof')->nullable();
            $table->foreignId('third_party_account_id')->unique()->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('companies');
    }
};
