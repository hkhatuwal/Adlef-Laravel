<?php

namespace App\Utils;

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
}
