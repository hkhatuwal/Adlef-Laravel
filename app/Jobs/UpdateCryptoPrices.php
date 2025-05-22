<?php

namespace App\Jobs;

use App\Models\Currency;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UpdateCryptoPrices implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $this->updateCryptoPrices();
            Log::info('All crypto prices updated successfully at '.Carbon::now()->toDateTimeLocalString());

        } catch (\Exception $e) {
            Log::error("Error updating crypto Prices ".$e->getMessage());
        }
    }

    /**
     * Update cryptocurrency prices from CoinCap API
     */
    protected function updateCryptoPrices(): void
    {
        try {
            $response = Http::get('https://rest.coincap.io/v3/assets?apiKey=1118c9946709f67957144d2e5c1c64cd9753df62e0828ac9c6568a1c3599e69e');
            echo "Updating crypto currencies\n";

            if ($response->successful()) {
                $data = $response->json()['data'];
                foreach ($data as $cryptoData) {
                    Currency::where('symbol', strtoupper($cryptoData['symbol']))
                        ->where('type', Currency::TYPE_CRYPTO)
                        ->update([
                            'price_usd' => $cryptoData['priceUsd'],
                            'usd_change_percent_24_hour' => $cryptoData['changePercent24Hr']
                        ]);
                }

                Log::info('Crypto currency prices updated successfully');
            } else {
                Log::error('Failed to fetch crypto prices from CoinCap API');
            }
        } catch (\Exception $e) {
            Log::error('Error updating crypto prices: ' . $e->getMessage());
            throw $e;
        }
    }

}
