<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CryptoPaymentOrder extends Model
{
    const STATUS_PENDING = 'pending';
    const STATUS_PAID = 'paid';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_EXPIRED = 'expired';

    protected $fillable = [
        'order_id',
        'transaction_id',
        'gateway_name',
        'wallet_address',
        'original_amount',
        'fingerprint_amount',
        'fingerprint_code',
        'original_currency',
        'payment_currency',
        'network',
        'customer_email',
        'customer_name',
        'customer_phone',
        'description',
        'metadata',
        'items',
        'return_url',
        'cancel_url',
        'payment_url',
        'status',
        'expires_at',
        'paid_at',
        'completed_at',
        'gateway_transaction_id',
        'gateway_response',
        'webhook_attempts',
        'webhook_sent_at',
        'api_client_id',
    ];

    protected $casts = [
        'original_amount' => 'decimal:8',
        'fingerprint_amount' => 'decimal:8',
        'metadata' => 'array',
        'items' => 'array',
        'gateway_response' => 'array',
        'expires_at' => 'datetime',
        'paid_at' => 'datetime',
        'completed_at' => 'datetime',
        'webhook_sent_at' => 'datetime',
    ];

    /**
     * Generate a unique order ID
     */
    public static function generateOrderId(): string
    {
        do {
            $orderId = 'TG_' . strtoupper(uniqid()) . '_' . Str::random(4);
        } while (self::where('order_id', $orderId)->exists());

        return $orderId;
    }

    /**
     * Check if the payment order is expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast() && $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if the payment order is awaiting payment
     */
    public function isAwaitingPayment(): bool
    {
        return $this->status === self::STATUS_PENDING && !$this->isExpired();
    }

    /**
     * Mark the payment as received
     */
    public function markAsPaid(string $gatewayTransactionId = null, array $gatewayResponse = null): void
    {
        $this->update([
            'status' => self::STATUS_PAID,
            'paid_at' => now(),
            'gateway_transaction_id' => $gatewayTransactionId,
            'gateway_response' => $gatewayResponse,
        ]);
    }

    /**
     * Mark the payment as completed
     */
    public function markAsCompleted(): void
    {
        $this->update([
            'status' => self::STATUS_COMPLETED,
            'completed_at' => now(),
        ]);
    }

    /**
     * Mark the payment as failed
     */
    public function markAsFailed(array $gatewayResponse = null): void
    {
        $this->update([
            'status' => self::STATUS_FAILED,
            'gateway_response' => $gatewayResponse,
        ]);
    }

    /**
     * Mark the payment as expired
     */
    public function markAsExpired(): void
    {
        $this->update([
            'status' => self::STATUS_EXPIRED,
        ]);
    }

    /**
     * Find payment order by fingerprint amount and wallet address
     */
    public static function findByFingerprintAndWallet(float $amount, string $walletAddress): ?self
    {
        return self::where('fingerprint_amount', $amount)
            ->where('wallet_address', $walletAddress)
            ->where('status', self::STATUS_PENDING)
            ->first();
    }

    /**
     * Relationships
     */
    public function apiClient(): BelongsTo
    {
        return $this->belongsTo(ApiClient::class);
    }

    /**
     * Scopes
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<', now())
            ->where('status', self::STATUS_PENDING);
    }

    public function scopeAwaitingPayment($query)
    {
        return $query->where('status', self::STATUS_PENDING)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            });
    }

    /**
     * Get formatted fingerprint amount for display
     */
    public function getFormattedFingerprintAmountAttribute(): string
    {
        return number_format($this->fingerprint_amount, 8) . ' ' . $this->payment_currency;
    }

    /**
     * Get formatted original amount for display
     */
    public function getFormattedOriginalAmountAttribute(): string
    {
        return number_format($this->original_amount, 2) . ' ' . $this->original_currency;
    }
}
