<?php

namespace App\Console;

use App\Jobs\UpdateCryptoPrices;
use App\Jobs\UpdateFiatCurrencyPrices;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // ... existing code ...
        $schedule->call(function (){
            UpdateFiatCurrencyPrices::dispatchSync();
            UpdateCryptoPrices::dispatchSync();
        })->hourly();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
