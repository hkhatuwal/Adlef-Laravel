<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{

    const  TYPE_OWN="Own";
    const  TYPE_THIRD_PARTY="ThirdParty";
    protected $fillable = [
        'account_type',
        'account_holder_name',
        'bank_name',
        'swift',
        'account_number',
        'shortcode',
        'branch_code',
        'user_id',
        'is_verified'
    ];

    protected $casts = [
        'is_verified' => 'boolean'
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function thirdPartyAccount(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(ThirdPartyAccount::class);
    }


}
