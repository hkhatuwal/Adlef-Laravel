<?php

namespace Database\Seeders;

use App\Models\AssetAccount;
use App\Models\Currency;
use App\Models\User;
use Illuminate\Database\Seeder;

class AssetAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all currencies
        AssetAccount::query()->delete();
        $currencies = Currency::all();
        // Create an asset account for each currency
        foreach ($currencies as $currency) {
            AssetAccount::create([
                'name' => $currency->name . ' Account',
                'currency_id' => $currency->id,
                'user_id' => 2,
                'balance' => rand(1000, 10000), // Default starting balance
                'account_number' => 'ACC-' . strtoupper(substr($currency->name, 0, 3)) . '-' . str_pad(2, 6, '0', STR_PAD_LEFT),
            ]);
        }
    }
}
