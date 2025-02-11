<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThirdPartyAccount extends Model
{


const TYPE_INDIVIDUAL="Individual";
const TYPE_COMPANY="Company";
    protected $fillable = [
        'third_party_type',
        'bank_account_id',
        'individual_id',
        'company_id'
    ];

    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class);
    }

    public function isCompany(): bool
    {
        return $this->third_party_type === self::TYPE_COMPANY;
    }

    public function individual()
    {
        return $this->belongsTo(Individual::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
