<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ApiClient extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'company_name',
        'api_key',
        'secret_key',
        'is_active',
        'is_sandbox',
        'allowed_ips',
        'webhook_urls',
        'allowed_currencies',
        'daily_limit',
        'monthly_limit',
        'daily_used',
        'monthly_used',
        'last_used_at',
        'expires_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_sandbox' => 'boolean',
        'allowed_ips' => 'array',
        'webhook_urls' => 'array',
        'allowed_currencies' => 'array',
        'daily_limit' => 'decimal:2',
        'monthly_limit' => 'decimal:2',
        'daily_used' => 'decimal:2',
        'monthly_used' => 'decimal:2',
        'last_used_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    protected $hidden = [
        'secret_key',
    ];

    /**
     * Get the user that owns this API client
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all payment transactions for this client
     */
    public function paymentTransactions(): HasMany
    {
        return $this->hasMany(PaymentTransaction::class);
    }

    /**
     * Generate API key and secret
     */
    public static function generateCredentials(): array
    {
        return [
            'api_key' => 'ak_' . Str::random(32),
            'secret_key' => 'sk_' . Str::random(32),
        ];
    }

    /**
     * Check if client is within limits
     */
    public function isWithinLimits(float $amount): bool
    {
        // Check daily limit
        if ($this->daily_limit && ($this->daily_used + $amount) > $this->daily_limit) {
            return false;
        }

        // Check monthly limit
        if ($this->monthly_limit && ($this->monthly_used + $amount) > $this->monthly_limit) {
            return false;
        }

        return true;
    }

    /**
     * Check if IP is allowed
     */
    public function isIpAllowed(string $ip): bool
    {
        $allowedIps = is_array($this->allowed_ips)
            ? $this->allowed_ips
            : json_decode($this->allowed_ips, true);

        if (empty($allowedIps)) {
            return true; // No IP restriction
        }

        return in_array($ip, $allowedIps);
    }


    /**
     * Check if currency is allowed
     */
    public function isCurrencyAllowed(string $currency): bool
    {
        if (empty($this->allowed_currencies)) {
            return true; // No currency restriction
        }

        return in_array(strtoupper($currency), $this->allowed_currencies);
    }

    /**
     * Update usage statistics
     */
    public function updateUsage(float $amount): void
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();

        // Reset daily usage if needed
        if ($this->last_used_at && !$this->last_used_at->isToday()) {
            $this->daily_used = 0;
        }

        // Reset monthly usage if needed
        if ($this->last_used_at && $this->last_used_at->lt($thisMonth)) {
            $this->monthly_used = 0;
        }

        $this->increment('daily_used', $amount);
        $this->increment('monthly_used', $amount);
        $this->update(['last_used_at' => now()]);
    }

    /**
     * Check if client is expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Verify client credentials
     */
    public static function verifyCredentials(string $apiKey, string $secretKey): ?self
    {
        return self::where('api_key', $apiKey)
            ->where('secret_key', $secretKey)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Get client's webhook URLs
     */
    public function getWebhookUrls(): array
    {
        return $this->webhook_urls ?? [];
    }

    /**
     * Generate signature for webhook
     */
    public function generateWebhookSignature(string $payload): string
    {
        return hash_hmac('sha256', $payload, $this->secret_key);
    }

    /**
     * Get formatted usage percentage for daily limit
     */
    public function getDailyUsagePercentageAttribute(): float
    {
        if (!$this->daily_limit || $this->daily_limit <= 0) {
            return 0;
        }

        return min(($this->daily_used / $this->daily_limit) * 100, 100);
    }

    /**
     * Get formatted usage percentage for monthly limit
     */
    public function getMonthlyUsagePercentageAttribute(): float
    {
        if (!$this->monthly_limit || $this->monthly_limit <= 0) {
            return 0;
        }

        return min(($this->monthly_used / $this->monthly_limit) * 100, 100);
    }

    /**
     * Check if client is approaching daily limit (80% threshold)
     */
    public function isApproachingDailyLimit(): bool
    {
        return $this->daily_usage_percentage >= 80;
    }

    /**
     * Check if client is approaching monthly limit (80% threshold)
     */
    public function isApproachingMonthlyLimit(): bool
    {
        return $this->monthly_usage_percentage >= 80;
    }

    /**
     * Get environment label
     */
    public function getEnvironmentLabelAttribute(): string
    {
        return $this->is_sandbox ? 'Sandbox' : 'Production';
    }

    /**
     * Get status color for UI
     */
    public function getStatusColorAttribute(): string
    {
        if (!$this->is_active) {
            return 'red';
        }

        if ($this->isExpired()) {
            return 'orange';
        }

        return 'green';
    }

    /**
     * Get a display-friendly API key (masked)
     */
    public function getMaskedApiKeyAttribute(): string
    {
        return substr($this->api_key, 0, 8) . '...' . substr($this->api_key, -8);
    }
}
