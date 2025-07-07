<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TGTemporaryWallet extends Model
{
    protected $fillable=[
        'public_key',
        'private_key',
        'wallet_address_hex',
        'wallet_address',
        'raw_response',
    ];
    protected $casts = [
        'raw_response' => 'array'
    ];

    protected $table='tg_temporary_wallets';
    //
}
