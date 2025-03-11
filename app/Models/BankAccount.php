<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BankAccount extends Model
{

    const  TYPE_OWN="Own";
    const  TYPE_THIRD_PARTY="ThirdParty";
    const NOTIFICATION_TYPE_BANK_ACCOUNT_VERIFIED="bank_account_verified";
    const NOTIFICATION_TYPE_BANK_ACCOUNT_UNVERIFIED="bank_account_unverified";


    protected $fillable = [
        'user_id',
        'account_type',
        'account_holder_name',
        'account_number',
        'bank_name',
        'swift',
        'branch_code',
        'shortcode',
        'is_verified'
    ];

    protected $casts = [
        'is_verified' => 'boolean'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function thirdPartyAccount(): BelongsTo
    {
        return $this->belongsTo(ThirdPartyAccount::class);
    }


}
