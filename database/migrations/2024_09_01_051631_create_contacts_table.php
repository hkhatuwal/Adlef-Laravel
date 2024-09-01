<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('business_name')->nullable();
            $table->string('job_title')->nullable();
            $table->string('business_email');
            $table->string('phone')->nullable();
            $table->boolean('fx_services')->default(false);
            $table->boolean('asset_servicing')->default(false);
            $table->boolean('settlement_clearing')->default(false);
            $table->boolean('treasury_services')->default(false);
            $table->boolean('other')->default(false);
            $table->text('project_description')->nullable();
            $table->string('how_did_you_hear')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contacts');
    }
}
