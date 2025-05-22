<?php

namespace App\Jobs;

use App\Models\Currency;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UpdateFiatCurrencyPrices implements ShouldQueue
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
            // Update crypto currencies

            // Update fiat currencies
            $this->updateFiatPrices();

            Log::info('All currency prices updated successfully'.Carbon::now()->toDateTimeLocalString());
        } catch (\Exception $e) {
            Log::error('Error updating currency prices: ' . $e->getMessage());
        }
    }


    /**
     * Update fiat currency prices from Exchange Rate API
     *
     * @throws ConnectionException
     */
    protected function updateFiatPrices(): void
    {
        try {
            $response = Http::get('https://api.exchangerate-api.com/v4/latest/USD');
            echo "Updating fiat currencies\n";

            if ($response->successful()) {
                $data = $response->json()['rates'];
                foreach ($data as $key => $value) {
                    Currency::where('symbol', strtoupper($key))
                        ->where('type', Currency::TYPE_FIAT)
                        ->update([
                            'price_usd' => $value,
                            'usd_change_percent_24_hour' => 1
                        ]);
                }

                Log::info('Fiat currency prices updated successfully');
            } else {
                Log::error('Failed to fetch currency prices from exchangerate-api.com');
            }
        } catch (\Exception $e) {
            Log::error('Error updating fiat prices: ' . $e->getMessage());
            throw $e;
        }
    }
}
