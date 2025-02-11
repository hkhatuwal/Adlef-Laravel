<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetAccount extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'currency_id',
        'user_id',
        'balance',
        'account_number',
    ];

    /**
     * Get the currency associated with the asset account.
     */
    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    /**
     * Get the user that owns the asset account.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
