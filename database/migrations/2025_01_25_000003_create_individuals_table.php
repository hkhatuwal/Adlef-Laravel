<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('individuals', function (Blueprint $table) {
            $table->id();
            $table->string('fname', 100);
            $table->string('lname', 100);
            $table->date('dob');
            $table->enum('gender', ['Male', 'Female', 'Other'])->nullable();
            $table->string('email');
            $table->string('contact', 20)->nullable();
            $table->string('country_of_origin', 100)->nullable();
            $table->enum('relationship', config('constants.relationship'));
            $table->string('document_id_number')->nullable();
            $table->string('document_issued_country', 100)->nullable();
            $table->string('document_url')->nullable();
            $table->foreignId('third_party_account_id')->unique()->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('individuals');
    }
};
