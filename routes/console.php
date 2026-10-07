<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;


Schedule::call(function () {
   \App\Jobs\UpdateFiatCurrencyPrices::dispatchSync();
   \App\Jobs\UpdateCryptoPrices::dispatchSync();
})->hourly()->evenInMaintenanceMode();

Schedule::call(function () {
    \App\Jobs\PollTronTransactions::dispatchSync();
})->everyThirtySeconds()->evenInMaintenanceMode();

Schedule::call(function () {
    \App\Jobs\PollOppwaTransactions::dispatchSync();
})->everyMinute()->evenInMaintenanceMode();


