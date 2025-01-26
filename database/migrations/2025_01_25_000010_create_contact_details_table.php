<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('contact_details', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('country_code', 6);
            $table->string('phone', 20);
            $table->boolean('is_phone_verified')->default(false);
            $table->boolean('is_email_verified')->default(false);
            $table->enum('phone_type', ['Home','Office','Mobile']);
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('contact_details');
    }
};
