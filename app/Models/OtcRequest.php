<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Models\Currency;

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
        });

        // Update activity record when OTC request status changes
        static::updated(function ($otcRequest) {
            if ($otcRequest->isDirty('status')) {
                $otcRequest->updateActivity();
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
        $rate = number_format($this->exchange_rate, 4);

        return "Exchange {$fromAmount} to {$toAmount} at rate {$rate}";
    }
}
