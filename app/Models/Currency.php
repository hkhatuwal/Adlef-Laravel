<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Currency extends Model
{
    protected $fillable=["name","symbol","conversion_rate","icon"];


    public function commission(): HasMany
    {
        return $this->hasMany(Commission::class);
    }
    public function isUSD():bool{
        return  $this->symbol === "USD";
    }
}
