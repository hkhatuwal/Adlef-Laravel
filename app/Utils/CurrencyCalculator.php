<?php

namespace App\Utils;

use App\Models\Currency;
use App\Models\Commission;
use App\Models\User;

class CurrencyCalculator
{

    public static function convertToUSD($amount,Currency $from){
        $usd=Currency::where(["symbol"=>"USD"])->first();
       return FeeCalculator::calculateExchangeRateAndFee($amount,$from,$usd);
    }
}
