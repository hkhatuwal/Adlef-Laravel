<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThirdPartyAccount extends Model
{
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

    public function individual()
    {
        return $this->belongsTo(Individual::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
