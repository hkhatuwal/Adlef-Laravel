<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Currency::query()->delete();
        Currency::create( ['name' => 'US Dollar', 'symbol' => 'USD', 'conversion_rate' => 1,"icon"=>"logo/usd.svg"]);
        Currency::create( ['name' => 'Bitcoin', 'symbol' => 'BTC', 'conversion_rate' => 1,"icon"=>"logo/bitcoin.svg"]);
        Currency::create( ['name' => 'Etherium', 'symbol' => 'ETH', 'conversion_rate' => 1,"icon"=>"logo/eth.svg"]);
        Currency::create( ['name' => 'Tether', 'symbol' => 'USDT', 'conversion_rate' => 1,"icon"=>"logo/tether.svg"]);
    }
}
