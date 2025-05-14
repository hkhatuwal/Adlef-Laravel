<?php

namespace App\Jobs;

use App\Models\Currency;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UpdateCurrencyPrices implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
            $response = Http::get('https://api.coincap.io/v2/assets');
            echo  "updating currency";
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

                Log::info('Currency prices updated successfully');
            } else {
                Log::error('Failed to fetch currency prices from CoinCap API');
            }
        } catch (\Exception $e) {
            Log::error('Error updating currency prices: ' . $e->getMessage());
        }
    }
}
