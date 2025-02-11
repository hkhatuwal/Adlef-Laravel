<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Individual extends Model
{

    const  TYPE="individual";
    protected $fillable = [
        'fname',
        'lname',
        'dob',
        'gender',
        'email',
        'contact',
        'country_of_origin',
        'relationship',
        'document_id_number',
        'document_issued_country',
        'document_url',
        'third_party_account_id'
    ];

    protected $casts = [
        'dob' => 'date'
    ];

    public function thirdPartyAccount()
    {
        return $this->belongsTo(ThirdPartyAccount::class);
    }

    public function fullName(): string
    {
        return $this->fname.''. $this->lname;
    }

    public function address()
    {
        return $this->hasOne(Address::class);
    }
}
