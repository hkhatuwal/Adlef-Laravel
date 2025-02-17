<?php

namespace App\Utils;

use App\Models\Currency;

class CurrencyConverter
{
    /**
     * Calculate the conversion between two currencies using USD prices
     *
     * @param float $amount Amount to convert
     * @param Currency $from Source currency
     * @param Currency $to Target currency
     * @return float
     */
    public function convert(float $amount, Currency $from, Currency $to): float
    {
        // If converting between the same currency
        if ($from->id === $to->id) {
            return $amount;
        }

        // First convert amount to USD value
        $amountInUSD = $amount * $from->price_usd;
        
        // If target is USD, return the USD amount
        if ($to->isUSD()) {
            return $amountInUSD;
        }
        
        // Convert USD amount to target currency
        return $amountInUSD / $to->price_usd;
    }

    /**
     * Get the effective exchange rate between two currencies
     *
     * @param Currency $from Source currency
     * @param Currency $to Target currency
     * @return float
     */
    public function getExchangeRate(Currency $from, Currency $to): float
    {
        if ($from->id === $to->id) {
            return 1.0;
        }

        // Calculate exchange rate using USD prices
        return $from->price_usd / $to->price_usd;
    }

    /**
     * Convert any currency to USD
     *
     * @param float $amount Amount to convert
     * @param Currency $from Source currency
     * @return float
     */
    public function convertToUSD(float $amount, Currency $from): float
    {
        if ($from->isUSD()) {
            return $amount;
        }
        
        return $amount * $from->price_usd;
    }
} 