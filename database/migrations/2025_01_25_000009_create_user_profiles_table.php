<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            // Personal Information
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('first_name', 100);
            $table->string('middle_name', 100)->nullable();
            $table->string('last_name', 100);
            $table->string('alias', 100)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('place_of_birth', 100)->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->enum('marital_status', ['Single', 'Married', 'Divorced', 'Widowed'])->nullable();
            $table->unsignedInteger('citizenship_id')->nullable();
            $table->boolean('has_dual_citizenship')->default(false);

            // Economic Profile
            $table->string('current_occupation', 200)->nullable();
            $table->enum('annual_income_range', [
                'Under US$250k',
                'US$250k - US$500k',
                'US$500k - US$1mil',
                'US$1mil - US$5mil',
                'Over US$5mil'
            ])->nullable();
            $table->set('account_purpose', [
                'Custody',
                'Asset Servicing',
                'Escrow',
                'Investments',
                'Treasury Services',
                'Other'
            ])->nullable();
            $table->set('funds_source', [
                'Salary',
                'Inheritance',
                'Divorce Settlement',
                'Pension/SavingsFromEmployment',
                'Sale Of Property',
                'Interest Income',
                'Capital Gain/Dividends',
                'Gambling',
                'Gift',
                'Other'
            ])->nullable();
            $table->set('wealth_source', [
                'Salary',
                'Inheritance',
                'Divorce Settlement',
                'Pension/SavingsFromEmployment',
                'Sale Of Property',
                'Interest Income',
                'Capital Gain/Dividends',
                'Gambling',
                'Gift',
                'Other'
            ])->nullable();
            $table->enum('anticipated_asset_class', [
                'Custody Asset Servicing',
                'Escrow',
                'Investments',
                'Business Transactions',
                'Other'
            ])->nullable();
            $table->boolean('third_party_contributions')->nullable();


            // Tax Residency
            $table->boolean('is_hong_kong_tax_resident')->nullable();
            $table->string('tax_identification_number', 50)->nullable();
            $table->enum('tin_not_provided_reason', array_keys(config('constants.tin_reasons')))->nullable();
            $table->unsignedInteger('secondary_tax_country_id')->nullable();

            // Consent and Tracking
            $table->boolean('agreement_accepted')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_profiles');
    }
};
