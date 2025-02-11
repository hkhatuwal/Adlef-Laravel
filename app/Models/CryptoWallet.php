<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CryptoWallet extends Model
{
    protected $fillable = [
        'currency_id',
        'wallet_address',
        'alias',
        'user_id',
        'is_verified'
    ];

    protected $casts = [
        'is_verified' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function formattedAddress(): string
    {
        $formatted = (strlen($this->wallet_address) > 10) ? '...' . substr($this->wallet_address, -10) : $this->wallet_address;

        return strtolower($formatted);
    }
    public function currency(){
        return $this->belongsTo(Currency::class);
    }
}
