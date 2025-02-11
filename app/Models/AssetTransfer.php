<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetTransfer extends Model
{

    const  TYPE_IN="in";
    const  TYPE_OUT="out";
    const  TYPE_THIRD_PARTY="third_party";
    protected $fillable = [
        'from_account_id',
        'to_account_id',
        'currency_id',
        'reference_number',
        'amount',
        'status',
        'fee',
        'transfer_type',
    ];

    public function currency(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    //
}
