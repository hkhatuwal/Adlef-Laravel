<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserActivity extends Model
{
    // Activity Types
    const TYPE_ASSET_TRANSFER = 'asset_transfer';
    const TYPE_OTC_TRADE = 'otc_trade';
    const TYPE_CRYPTO_WITHDRAWAL = 'crypto_withdrawal';
    const TYPE_FIAT_WITHDRAWAL = 'fiat_withdrawal';
    const TYPE_DEPOSIT = 'deposit';
    
    // Actions
    const ACTION_TRANSFER_IN = 'transfer_in';
    const ACTION_TRANSFER_OUT = 'transfer_out';
    const ACTION_BUY = 'buy';
    const ACTION_SELL = 'sell';
    const ACTION_WITHDRAW = 'withdraw';
    const ACTION_DEPOSIT = 'deposit';
    
    // Statuses
    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';
    const STATUS_CANCELLED = 'cancelled';
    
    protected $fillable = [
        'user_id',
        'activity_type',
        'action',
        'subject_type',
        'subject_id',
        'status',
        'amount',
        'currency_symbol',
        'reference_number',
        'metadata',
        'description'
    ];

    protected $casts = [
        'metadata' => 'array',
        'amount' => 'decimal:8'
    ];

    /**
     * Get the user that owns the activity
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the subject model of the activity
     */
    public function subject()
    {
        return $this->morphTo();
    }

    /**
     * Get the route for this activity
     */
    public function getRouteAttribute()
    {
        return match($this->activity_type) {
            self::TYPE_ASSET_TRANSFER => route('client.transfer.show', $this->subject_id),
            self::TYPE_OTC_TRADE => route('client.otc.show', $this->subject_id),
            self::TYPE_CRYPTO_WITHDRAWAL => route('client.withdrawal.crypto.show', $this->subject_id),
            self::TYPE_FIAT_WITHDRAWAL => route('client.withdrawal.fiat.show', $this->subject_id),
            self::TYPE_DEPOSIT => route('client.deposit.show', $this->subject_id),
            default => '#'
        };
    }

    /**
     * Get the icon class for this activity type
     */
    public function getIconClassAttribute()
    {
        return match($this->activity_type) {
            self::TYPE_ASSET_TRANSFER => 'fa-right-left',
            self::TYPE_OTC_TRADE => 'fa-arrows-rotate',
            self::TYPE_CRYPTO_WITHDRAWAL => 'fa-arrow-up-right-from-square',
            self::TYPE_FIAT_WITHDRAWAL => 'fa-money-bill-transfer',
            self::TYPE_DEPOSIT => 'fa-arrow-down-to-arc',
            default => 'fa-circle-info'
        };
    }

    /**
     * Get the color class for this activity's status
     */
    public function getStatusColorClassAttribute()
    {
        return match($this->status) {
            self::STATUS_COMPLETED => 'text-emerald-600',
            self::STATUS_PENDING => 'text-amber-600',
            self::STATUS_FAILED => 'text-red-600',
            self::STATUS_CANCELLED => 'text-slate-600',
            default => 'text-slate-600'
        };
    }

    /**
     * Scope a query to only include activities of a specific type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('activity_type', $type);
    }

    /**
     * Scope a query to only include activities with a specific status
     */
    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }
} 