<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Currency extends Model
{

    const TYPE_FIAT="fiat";
    const TYPE_CRYPTO="crypto";
    protected $fillable=["name","symbol","conversion_rate","icon","type","price_usd","usd_change_percent_24_hour"];

    protected $casts=[
        'conversion_rate'=>'double'
    ];


    public function commission(): HasMany
    {
        return $this->hasMany(Commission::class);
    }
    public function isUSD():bool{
        return  $this->symbol === "USD";
    }
    public function getMyAssetAccount(){
        return AssetAccount::where(["user_id"=>auth()->user()->id,"currency_id"=>$this->id])->first();
    }
}
