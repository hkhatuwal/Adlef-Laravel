<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransferOutPayment extends Model
{
    // Payment status constants
    const STATUS_PENDING = 'pending';
    const STATUS_SENT = 'sent';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_FAILED = 'failed';

    protected $fillable = [
        'asset_transfer_id',
        'transaction_id',
        'payment_status',
        'sent_at',
        'confirmed_at',
        'payment_reference',
        'failure_reason',
        'payment_metadata',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'payment_metadata' => 'array',
    ];

    /**
     * Get the asset transfer that owns this payment record.
     */
    public function assetTransfer(): BelongsTo
    {
        return $this->belongsTo(AssetTransfer::class);
    }

    /**
     * Check if payment is confirmed.
     */
    public function isConfirmed(): bool
    {
        return $this->payment_status === self::STATUS_CONFIRMED;
    }

    /**
     * Check if payment is pending.
     */
    public function isPending(): bool
    {
        return $this->payment_status === self::STATUS_PENDING;
    }

    /**
     * Check if payment was sent.
     */
    public function isSent(): bool
    {
        return $this->payment_status === self::STATUS_SENT;
    }

    /**
     * Check if payment has failed.
     */
    public function isFailed(): bool
    {
        return $this->payment_status === self::STATUS_FAILED;
    }

    /**
     * Mark payment as sent.
     */
    public function markAsSent(string $reference = null): bool
    {
        return $this->update([
            'payment_status' => self::STATUS_SENT,
            'sent_at' => now(),
            'payment_reference' => $reference,
        ]);
    }

    /**
     * Mark payment as confirmed.
     */
    public function markAsConfirmed(): bool
    {
        return $this->update([
            'payment_status' => self::STATUS_CONFIRMED,
            'confirmed_at' => now(),
        ]);
    }

    /**
     * Mark payment as failed.
     */
    public function markAsFailed(string $reason = null): bool
    {
        return $this->update([
            'payment_status' => self::STATUS_FAILED,
            'failure_reason' => $reason,
        ]);
    }

    /**
     * Generate a unique transaction ID.
     */
    public static function generateTransactionId(): string
    {
        do {
            $transactionId = 'TXN-' . strtoupper(uniqid());
        } while (self::where('transaction_id', $transactionId)->exists());

        return $transactionId;
    }
}
