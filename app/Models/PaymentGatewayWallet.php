<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentGatewayWallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'gateway_name',
        'balance_usd',
    ];

    protected $casts = [
        'balance_usd' => 'decimal:2',
    ];

    /**
     * Get the user that owns the wallet
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Add amount to wallet balance
     */
    public function addBalance(float $amount): void
    {
        $this->increment('balance_usd', $amount);
    }

    /**
     * Subtract amount from wallet balance
     */
    public  function subtractBalance(float $amount): bool
    {
        if ($this->balance_usd < $amount) {
            return false; // Insufficient balance
        }

        $this->decrement('balance_usd', $amount);
        return true;
    }

    /**
     * Get or create wallet for user and gateway
     */
    public static function getOrCreateWallet(int $userId, string $gatewayName): self
    {
        return static::firstOrCreate(
            [
                'user_id' => $userId,
                'gateway_name' => $gatewayName,
            ],
            [
                'balance_usd' => 0.00,
            ]
        );
    }

    /**
     * Get wallet balance for a specific gateway
     */
    public static function getBalanceForGateway(int $userId, string $gatewayName): float
    {
        $wallet = static::where('user_id', $userId)
            ->where('gateway_name', $gatewayName)
            ->first();

        return $wallet ? $wallet->balance_usd : 0.00;
    }

    /**
     * Get all wallets for a user
     */
    public static function getWalletsForUser(int $userId): \Illuminate\Database\Eloquent\Collection
    {
        return static::where('user_id', $userId)->get();
    }

    /**
     * Get total balance across all gateways for a user
     */
    public static function getTotalBalanceForUser(int $userId): float
    {
        return static::where('user_id', $userId)->sum('balance_usd');
    }
}
