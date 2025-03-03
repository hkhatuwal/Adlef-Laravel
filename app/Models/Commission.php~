<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{

    protected $fillable=[
        "currency_id",
        "user_id",
        "commission_rate",
    ];
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function currency(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }
}
