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
        'amount',
        'status',
        'transfer_type',
    ];

    //
}
