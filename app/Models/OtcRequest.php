<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Models\Currency;
use App\Models\Notification;

class OtcRequest extends Model
{
    protected $fillable = [
        'user_id',
        'from_currency_id',
        'to_currency_id',
        'from_amount',
        'to_amount',
        'exchange_rate',
        'network_fee',
        'status',
        'failure_reason',
    ];

    protected $casts = [
        'from_amount' => 'decimal:8',
        'to_amount' => 'decimal:8',
        'exchange_rate' => 'decimal:8',
        'network_fee' => 'decimal:8',
    ];

    protected static function booted(): void
    {
        // Create activity record when OTC request is created
        static::created(function ($otcRequest) {
            $otcRequest->recordActivity();
            $otcRequest->createNotification();
        });

        // Update activity record when OTC request status changes
        static::updated(function ($otcRequest) {
            if ($otcRequest->isDirty('status')) {
                $otcRequest->updateActivity();
                $otcRequest->updateNotification();
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function fromCurrency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'from_currency_id');
    }

    public function toCurrency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'to_currency_id');
    }

    public function activities()
    {
        return $this->morphMany(UserActivity::class, 'subject');
    }

    protected function recordActivity()
    {
        $description = $this->generateActivityDescription();
        
        UserActivity::create([
            'user_id' => $this->user_id,
            'activity_type' => UserActivity::TYPE_OTC_TRADE,
            'action' => UserActivity::ACTION_BUY,
            'subject_type' => self::class,
            'subject_id' => $this->id,
            'status' => $this->status,
            'amount' => $this->from_amount,
            'currency_symbol' => $this->fromCurrency->symbol,
            'reference_number' => $this->reference_number ?? null,
            'description' => $description,
            'metadata' => [
                'exchange_rate' => $this->exchange_rate,
                'network_fee' => $this->network_fee,
                'to_amount' => $this->to_amount,
                'to_currency' => $this->toCurrency->symbol,
                'from_currency' => $this->fromCurrency->symbol
            ]
        ]);
    }

    protected function updateActivity()
    {
        $this->activities()
            ->latest()
            ->first()
            ->update([
                'status' => $this->status,
                'description' => $this->generateActivityDescription()
            ]);
    }

    protected function generateActivityDescription()
    {
        $fromAmount = number_format($this->from_amount, 8) . ' ' . $this->fromCurrency->symbol;
        $toAmount = number_format($this->to_amount, 8) . ' ' . $this->toCurrency->symbol;
        $rate = number_format($this->exchange_rate, 8);

        return "Exchange {$fromAmount} to {$toAmount} at rate {$rate}";
    }

    protected function createNotification(): void
    {
        $fromAmount = number_format($this->from_amount, 8) . ' ' . $this->fromCurrency->symbol;
        $toAmount = number_format($this->to_amount, 8) . ' ' . $this->toCurrency->symbol;

        Notification::create([
            'user_id' => $this->user_id,
            'type' => Notification::TYPE_INFO,
            'title' => 'OTC Trade Initiated',
            'message' => "Your OTC trade to exchange {$fromAmount} for {$toAmount} has been initiated.",
            'notifiable_type' => self::class,
            'notifiable_id' => $this->id,
            'metadata' => [
                'from_amount' => $this->from_amount,
                'to_amount' => $this->to_amount,
                'from_currency' => $this->fromCurrency->symbol,
                'to_currency' => $this->toCurrency->symbol,
                'exchange_rate' => $this->exchange_rate,
                'status' => $this->status
            ]
        ]);
    }

    protected function updateNotification(): void
    {
        $fromAmount = number_format($this->from_amount, 8) . ' ' . $this->fromCurrency->symbol;
        $toAmount = number_format($this->to_amount, 8) . ' ' . $this->toCurrency->symbol;

        $type = match($this->status) {
            'completed' => Notification::TYPE_SUCCESS,
            'failed' => Notification::TYPE_ERROR,
            default => Notification::TYPE_INFO
        };

        $title = match($this->status) {
            'completed' => 'OTC Trade Completed',
            'failed' => 'OTC Trade Failed',
            'processing' => 'OTC Trade Processing',
            default => 'OTC Trade Status Updated'
        };

        $message = match($this->status) {
            'completed' => "Your OTC trade to exchange {$fromAmount} for {$toAmount} has been completed successfully.",
            'failed' => "Your OTC trade to exchange {$fromAmount} for {$toAmount} has failed. Please contact support.",
            'processing' => "Your OTC trade to exchange {$fromAmount} for {$toAmount} is being processed.",
            default => "Your OTC trade to exchange {$fromAmount} for {$toAmount} status has been updated to {$this->status}."
        };

        Notification::create([
            'user_id' => $this->user_id,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'notifiable_type' => self::class,
            'notifiable_id' => $this->id,
            'metadata' => [
                'from_amount' => $this->from_amount,
                'to_amount' => $this->to_amount,
                'from_currency' => $this->fromCurrency->symbol,
                'to_currency' => $this->toCurrency->symbol,
                'exchange_rate' => $this->exchange_rate,
                'status' => $this->status
            ]
        ]);
    }
}
