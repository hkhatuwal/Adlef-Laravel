<?php

namespace App\Utils;

use App\Models\Currency;
use App\Models\User;

class AssetOperations
{
    private CurrencyConverter $currencyConverter;
    private FeeCalculator $feeCalculator;

    public function __construct(
        CurrencyConverter $currencyConverter = null,
        FeeCalculator $feeCalculator = null
    ) {
        $this->currencyConverter = $currencyConverter ?? new CurrencyConverter();
        $this->feeCalculator = $feeCalculator ?? new FeeCalculator();
    }

    /**
     * Calculate complete transfer details including conversion, fees, and commission
     *
     * @param float $amount Amount to transfer
     * @param Currency $from Source currency
     * @param Currency $to Target currency
     * @param User|null $user User for commission calculation
     * @param float $feePercentage Fee percentage
     * @param float $minimumFee Minimum fee amount
     * @return array Transfer details
     */
    /**
     * Calculate complete transfer details including conversion, fees, and commission
     *
     * Example output:
     * [
     *     'original_amount' => 100.00,
     *     'converted_amount' => 80.00,
     *     'fee' => 5.00,
     *     'commission_rate' => 0.05,
     *     'commission_amount' => 4.00,
     *     'rate' => 0.8,
     *     'total' => 89.00,
     *     'from' => CurrencyObject,
     *     'to' => CurrencyObject
     * ]
     *

     */
    public function calculateTransferDetails(
        float $amount,
        Currency $from,
        Currency $to,
        ?User $user = null,
        float $feePercentage = 1.0,
        float $minimumFee = 5.0
    ): array {
        // Convert amount
        $convertedAmount = $this->currencyConverter->convert($amount, $from, $to);

        // Calculate fee
        $fee = $this->feeCalculator->calculateTransferFee($convertedAmount, $feePercentage, $minimumFee);

        // Calculate commission
        $commission = $this->feeCalculator->calculateCommission($convertedAmount, $user, $to);

        // Get exchange rate
        $rate = $this->currencyConverter->getExchangeRate($from, $to);

        // Calculate total
        $total = $this->feeCalculator->calculateTotal($convertedAmount, $fee, $commission);

        return [
            'original_amount' => round($amount, 2),
            'converted_amount' => round($convertedAmount, 2),
            'fee' => round($fee, 2),
            'commission_rate' => $user ? $this->getCommissionRate($user, $to) : 0,
            'commission_amount' => round($commission, 2),
            'rate' => $rate,
            'total' => round($total, 2),
            'from' => $from,
            'to' => $to
        ];
    }

    /**
     * Convert amount to USD with fees
     *
     * @param float $amount Amount to convert
     * @param Currency $from Source currency
     * @return array Conversion details
     */
    public function convertToUSD(float $amount, Currency $from): array
    {
        $usd = Currency::where('symbol', 'USD')->firstOrFail();
        return $this->calculateTransferDetails($amount, $from, $usd);
    }

    /**
     * Get commission rate for user and currency
     *
     * @param User $user User to get commission for
     * @param Currency $currency Currency for commission
     * @return float Commission rate
     */
    private function getCommissionRate(User $user, Currency $currency): float
    {
        $commission = \App\Models\Commission::where('user_id', $user->id)
            ->where('currency_id', $currency->id)
            ->first();

        return $commission ? $commission->commission_rate : 0;
    }
}
