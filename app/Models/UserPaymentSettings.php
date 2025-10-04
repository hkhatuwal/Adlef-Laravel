<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPaymentSettings extends Model
{
    protected $fillable = [
        'user_id',
        'max_api_clients',
        'allowed_payment_providers',
        'provider_limits',
        'is_active',
        'fee_type',
        'fee_percentage',
        'fee_fixed',
    ];

    protected $casts = [
        'allowed_payment_providers' => 'array',
        'provider_limits' => 'array',
        'is_active' => 'boolean',
        'fee_percentage' => 'decimal:2',
        'fee_fixed' => 'decimal:2',
    ];

    /**
     * Get the user that owns the payment settings
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get default payment settings for a user
     */
    public static function getDefaultSettings(): array
    {
        $defaultProviderLimits = [];
        $availableProviders = config('constants.internal_payment_providers');

        // Set default limits for each provider
        foreach ($availableProviders as $category => $providers) {
            foreach ($providers as $provider => $config) {
                $defaultProviderLimits[$provider] = [
                    'daily_limit' => 10000.00,
                    'monthly_limit' => 100000.00,
                ];
            }
        }

        return [
            'max_api_clients' => 5,
            'allowed_payment_providers' => $availableProviders,
            'provider_limits' => $defaultProviderLimits,
            'is_active' => true,
            'fee_type' => 'percentage',
            'fee_percentage' => 0.00,
            'fee_fixed' => 0.00,
        ];
    }

    /**
     * Check if a payment provider is allowed
     */
    public function isProviderAllowed(string $provider): bool
    {
        if (empty($this->allowed_payment_providers)) {
            return true; // No restrictions
        }

        // Check if provider exists in any category
        foreach ($this->allowed_payment_providers as $category => $providers) {
            if (is_array($providers) && in_array($provider, $providers)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get allowed providers for a specific category
     */
    public function getAllowedProvidersForCategory(string $category): array
    {
        return $this->allowed_payment_providers[$category] ?? [];
    }

    /**
     * Check if user can create more API clients
     */
    public function canCreateApiClient(): bool
    {
        $currentCount = $this->user->apiClients()->count();
        return $currentCount < $this->max_api_clients;
    }

    /**
     * Get remaining API client slots
     */
    public function getRemainingApiClientSlots(): int
    {
        $currentCount = $this->user->apiClients()->count();
        return max(0, $this->max_api_clients - $currentCount);
    }

    /**
     * Get limits for a specific provider
     */
    public function getProviderLimits(string $provider): array
    {
        return $this->provider_limits[$provider] ?? [
            'daily_limit' => 0,
            'monthly_limit' => 0,
        ];
    }

    /**
     * Get daily limit for a specific provider
     */
    public function getProviderDailyLimit(string $provider): float
    {
        return $this->getProviderLimits($provider)['daily_limit'] ?? 0;
    }

    /**
     * Get monthly limit for a specific provider
     */
    public function getProviderMonthlyLimit(string $provider): float
    {
        return $this->getProviderLimits($provider)['monthly_limit'] ?? 0;
    }

    /**
     * Set limits for a specific provider
     */
    public function setProviderLimits(string $provider, float $dailyLimit, float $monthlyLimit): void
    {
        $limits = $this->provider_limits ?? [];
        $limits[$provider] = [
            'daily_limit' => $dailyLimit,
            'monthly_limit' => $monthlyLimit,
        ];
        $this->provider_limits = $limits;
    }

    /**
     * Get all providers with their limits
     */
    public function getAllProvidersWithLimits(): array
    {
        $result = [];
        $availableProviders = config('constants.internal_payment_providers');

        foreach ($availableProviders as $category => $providers) {
            foreach ($providers as $provider) {
                $result[$provider] = [
                    'category' => $category,
                    'limits' => $this->getProviderLimits($provider),
                    'allowed' => $this->isProviderAllowed($provider),
                ];
            }
        }

        return $result;
    }

    /**
     * Calculate settlement fee for a given amount
     */
    public function calculateSettlementFee(float $amount): float
    {
        if ($this->fee_type === 'percentage') {
            return ($amount * $this->fee_percentage) / 100;
        } else {
            return $this->fee_fixed;
        }
    }

    /**
     * Get the current fee type
     */
    public function getFeeType(): string
    {
        return $this->fee_type ?? 'percentage';
    }

    /**
     * Get the current fee value based on type
     */
    public function getFeeValue(): float
    {
        if ($this->fee_type === 'percentage') {
            return $this->fee_percentage ?? 0.00;
        } else {
            return $this->fee_fixed ?? 0.00;
        }
    }

    /**
     * Check if settlement fees are enabled
     */
    public function hasSettlementFees(): bool
    {
        if ($this->fee_type === 'percentage') {
            return ($this->fee_percentage ?? 0) > 0;
        } else {
            return ($this->fee_fixed ?? 0) > 0;
        }
    }

    /**
     * Get fee description for display
     */
    public function getFeeDescription(): string
    {
        if (!$this->hasSettlementFees()) {
            return 'No settlement fees';
        }

        if ($this->fee_type === 'percentage') {
            return $this->fee_percentage . '% per transaction';
        } else {
            return '$' . number_format($this->fee_fixed, 2) . ' per transaction';
        }
    }
}
