<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'company_name',
        'country',
        'registration_date',
        'registration_number',
        'email',
        'contact',
        'relationship',
        'registration_proof',
        'third_party_account_id'
    ];

    protected $casts = [
        'registration_date' => 'date'
    ];

    public function thirdPartyAccount()
    {
        return $this->belongsTo(ThirdPartyAccount::class);
    }

    public function address()
    {
        return $this->hasOne(Address::class);
    }
}
