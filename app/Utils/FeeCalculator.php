<?php

namespace App\Utils;

use App\Models\Currency;
use App\Models\Commission;
use App\Models\User;

class FeeCalculator
{
    public static function calculateTransferFee(float $amount, string $currency = 'USD'): array
    {
        // Base fee calculation (1% with minimum $1)
        $feePercentage = 0.01; // 1%
        $minimumFee = 1.00;

        // Calculate fee
        $calculatedFee = $amount * $feePercentage;
        $fee = max($calculatedFee, $minimumFee);

        // Calculate total
        $total = $amount + $fee;

        return [
            'amount' => round($amount, 2),
            'fee' => round($fee, 2),
            'total' => round($total, 2),
            'currency' => $currency
        ];
    }
    /**
     * Calculate exchange rate, fees, and commission for currency conversion.
     *
     * Example return value:
     * [
     *     'original_amount' => 100.00,      // The initial amount to be converted
     *     'converted_amount' => 85.00,      // Amount after conversion but before fees
     *     'fee' => 5.00,                    // Platform fee (1% with $5 minimum)
     *     'commission_rate' => 0.5,         // User's commission rate in percentage
     *     'commission_amount' => 0.43,      // Calculated commission amount
     *     'rate' => 0.85,                   // Effective exchange rate between currencies
     *     'total' => 90.43,                 // Final amount including fees and commission
     *     'from' => Currency,               // Source currency object
     *     'to' => Currency                  // Target currency object
     * ]
     *
     * @param float $amount Amount to convert
     * @param Currency $from Source currency
     * @param Currency $to Target currency
     * @param User|null $user User for commission calculation (optional)
     * @return array{
     *     original_amount: float,
     *     converted_amount: float,
     *     fee: float,
     *     commission_rate: float,
     *     commission_amount: float,
     *     rate: float,
     *     total: float,
     *     from: Currency,
     *     to: Currency
     * }
     */
    public static function calculateExchangeRateAndFee(float $amount, Currency $from, Currency $to, ?User $user = null): array
    {
        // Base fee calculation (1% with minimum $5)
        $feePercentage = 1; // 1%
        $minimumFee = 5.00;

        // Get user's commission rate if available
        $commissionRate = 0;
        if ($user) {
            $commission = Commission::where('user_id', $user->id)
                ->where('currency_id', $to->id)
                ->first();
            
            if ($commission) {
                $commissionRate = $commission->commission_rate;
            }
        }

        // Calculate converted amount based on USD as base currency
        $convertedAmount = $amount;
        
        // If converting from non-USD currency to USD
        if (!$from->isUSD() && $to->isUSD()) {
            $convertedAmount = $amount / $from->conversion_rate;
        }
        // If converting from USD to another currency
        else if ($from->isUSD() && !$to->isUSD()) {
            $convertedAmount = $amount * $to->conversion_rate;
        }
        // If converting between two non-USD currencies
        else if (!$from->isUSD() && !$to->isUSD()) {
            // First convert to USD, then to target currency
            $usdAmount = $amount / $from->conversion_rate;
            $convertedAmount = $usdAmount * $to->conversion_rate;
        }

        // Calculate fee based on the converted amount
        $calculatedFee = $convertedAmount * ($feePercentage / 100);
        $fee = max($calculatedFee, $minimumFee);

        // Calculate commission
        $commissionAmount = $convertedAmount * ($commissionRate / 100);

        // Calculate total including fee and commission
        $total = $convertedAmount + $fee + $commissionAmount;

        // Calculate the effective exchange rate
        $effectiveRate = $from->isUSD() ? $to->conversion_rate : 
                        ($to->isUSD() ? (1 / $from->conversion_rate) : 
                        ($to->conversion_rate / $from->conversion_rate));

        return [
            'original_amount' => round($amount, 2),
            'converted_amount' => round($convertedAmount, 2),
            'fee' => round($fee, 2),
            'commission_rate' => $commissionRate,
            'commission_amount' => round($commissionAmount, 2),
            'rate' => $effectiveRate,
            'total' => round($total, 2),
            'from' => $from,
            'to' => $to
        ];
    }
}
