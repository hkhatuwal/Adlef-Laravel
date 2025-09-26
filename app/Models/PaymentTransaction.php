<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentTransaction extends Model
{
    const STATUS_CHECKOUT_PENDING = 'checkout_pending';
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_REFUNDED = 'refunded';

    const STAGE_CHECKOUT = 'checkout';
    const STAGE_GATEWAY_PROCESSING = 'gateway_processing';
    const STAGE_COMPLETED = 'completed';

    // Payment Method Types
    const PAYMENT_METHOD_CARD = 'card';
    const PAYMENT_METHOD_BANK_TRANSFER = 'bank_transfer';
    const PAYMENT_METHOD_WALLET = 'wallet';
    const PAYMENT_METHOD_CRYPTO = 'crypto';
    const PAYMENT_METHOD_MOBILE = 'mobile_payment';
    const PAYMENT_METHOD_UPI = 'upi';
    const PAYMENT_METHOD_ALTERNATIVE = 'alternative';

    // Card Types
    const CARD_TYPE_CREDIT = 'credit';
    const CARD_TYPE_DEBIT = 'debit';
    const CARD_TYPE_PREPAID = 'prepaid';

    // Card Brands
    const CARD_BRAND_VISA = 'visa';
    const CARD_BRAND_MASTERCARD = 'mastercard';
    const CARD_BRAND_AMEX = 'amex';
    const CARD_BRAND_DISCOVER = 'discover';
    const CARD_BRAND_DINERS = 'diners';
    const CARD_BRAND_JCB = 'jcb';
    const CARD_BRAND_UNIONPAY = 'unionpay';

    // Account Types
    const ACCOUNT_TYPE_CHECKING = 'checking';
    const ACCOUNT_TYPE_SAVINGS = 'savings';
    const ACCOUNT_TYPE_BUSINESS = 'business';

    // Wallet Providers
    const WALLET_PAYPAL = 'paypal';
    const WALLET_APPLE_PAY = 'apple_pay';
    const WALLET_GOOGLE_PAY = 'google_pay';
    const WALLET_SAMSUNG_PAY = 'samsung_pay';
    const WALLET_ALIPAY = 'alipay';
    const WALLET_WECHAT = 'wechat';

    // Verification Status
    const VERIFICATION_VERIFIED = 'verified';
    const VERIFICATION_UNVERIFIED = 'unverified';
    const VERIFICATION_FAILED = 'failed';
    const VERIFICATION_PENDING = 'pending';

    protected $fillable = [
        'api_client_id',
        'transaction_id',
        'checkout_session_id',
        'gateway_transaction_id',
        'gateway_name',
        'available_gateways',
        'client_order_id',
        'amount',
        'currency',
        'description',
        'customer_email',
        'customer_name',
        'customer_phone',
        'payment_url',
        'checkout_url',
        'return_url',
        'cancel_url',
        'status',
        'payment_method',
        'stage',
        'gateway_status',
        'gateway_response',
        'failure_reason',
        'gateway_fee',
        'our_fee',
        'net_amount',
        'webhook_attempts',
        'webhook_sent_at',
        'webhook_responses',
        'metadata',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'gateway_fee' => 'decimal:2',
        'our_fee' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'gateway_response' => 'array',
        'webhook_responses' => 'array',
        'metadata' => 'array',
        'available_gateways' => 'array',
        'webhook_sent_at' => 'datetime',
    ];

    /**
     * Get the API client that owns this transaction
     */
    public function apiClient(): BelongsTo
    {
        return $this->belongsTo(ApiClient::class);
    }

    /**
     * Get the payment method for this transaction
     */
    public function paymentMethod(): HasOne
    {
        return $this->hasOne(PaymentMethod::class);
    }

    /**
     * Generate unique transaction ID
     */
    public static function generateTransactionId(): string
    {
        do {
            $id = 'txn_' . Str::random(16);
        } while (self::where('transaction_id', $id)->exists());

        return $id;
    }

    /**
     * Generate unique checkout session ID
     */
    public static function generateCheckoutSessionId(): string
    {
        do {
            $id = 'cs_' . Str::random(20);
        } while (self::where('checkout_session_id', $id)->exists());

        return $id;
    }

    /**
     * Check if transaction is successful
     */
    public function isSuccessful(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    /**
     * Check if transaction is pending
     */
    public function isPending(): bool
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_PROCESSING]);
    }

    /**
     * Check if transaction is in checkout stage
     */
    public function isCheckoutPending(): bool
    {
        return $this->status === self::STATUS_CHECKOUT_PENDING;
    }

    /**
     * Check if transaction has failed
     */
    public function hasFailed(): bool
    {
        return in_array($this->status, [self::STATUS_FAILED, self::STATUS_CANCELLED]);
    }

    /**
     * Update transaction status
     */
    public function updateStatus(string $status, array $gatewayResponse = null, string $failureReason = null): void
    {
        $updateData = ['status' => $status];

        if ($gatewayResponse) {
            $updateData['gateway_response'] = $gatewayResponse;
            $updateData['gateway_status'] = $gatewayResponse['status'] ?? null;
            $updateData['gateway_transaction_id'] = $gatewayResponse['transaction_id'] ?? $gatewayResponse['id'] ?? null;
        }

        if ($failureReason) {
            $updateData['failure_reason'] = $failureReason;
        }
        Log::info("Updated data");
        Log::info(json_encode($updateData));

        $this->update($updateData);
    }

    /**
     * Record webhook attempt
     */
    public function recordWebhookAttempt(array $response): void
    {
        $responses = $this->webhook_responses ?? [];
        $responses[] = [
            'timestamp' => now()->toISOString(),
            'response' => $response,
        ];

        $this->update([
            'webhook_attempts' => $this->webhook_attempts + 1,
            'webhook_sent_at' => now(),
            'webhook_responses' => $responses,
        ]);
    }

    /**
     * Get transaction details for API response
     */
    public function toApiResponse(): array
    {
        return [
            'transaction_id' => $this->transaction_id,
            'status' => $this->status,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'description' => $this->description,
            'customer_email' => $this->customer_email,
            'customer_name' => $this->customer_name,
            'payment_url' => $this->payment_url,
            'gateway_transaction_id' => $this->gateway_transaction_id,
            'client_order_id' => $this->client_order_id,
            'payment_method' => $this->getPaymentMethodSummary(),
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
        ];
    }

    /**
     * Get webhook payload
     */
    public function getWebhookPayload(): array
    {
        return [
            'event' => 'payment.' . $this->status,
            'transaction_id' => $this->transaction_id,
            'client_order_id' => $this->client_order_id,
            'status' => $this->status,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'gateway_transaction_id' => $this->gateway_transaction_id,
            'customer_email' => $this->customer_email,
            'payment_method' => $this->getPaymentMethodSummary(),
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
        ];
    }

    /**
     * Create or update payment method details from gateway response
     */
    public function updatePaymentMethodDetails(array $paymentMethodData): void
    {
        if ($this->paymentMethod) {
            // Update existing payment method
            $this->paymentMethod->update($paymentMethodData);
        } else {
            // Create new payment method
            PaymentMethod::createFromWebhookData($this->id, $paymentMethodData);
        }
    }

    /**
     * Get payment method summary for API responses
     */
    public function getPaymentMethodSummary(): array
    {
        if (!$this->paymentMethod) {
            return [];
        }

        return $this->paymentMethod->getPaymentMethodSummary();
    }

    /**
     * Check if payment method is a card
     */
    public function isCardPayment(): bool
    {
        return $this->paymentMethod?->isCardPayment() ?? false;
    }

    /**
     * Check if payment method is a bank transfer
     */
    public function isBankTransfer(): bool
    {
        return $this->paymentMethod?->isBankTransfer() ?? false;
    }

    /**
     * Check if payment method is a digital wallet
     */
    public function isWalletPayment(): bool
    {
        return $this->paymentMethod?->isWalletPayment() ?? false;
    }

    /**
     * Check if payment method is cryptocurrency
     */
    public function isCryptoPayment(): bool
    {
        return $this->paymentMethod?->isCryptoPayment() ?? false;
    }

    /**
     * Get masked card number for display
     */
    public function getMaskedCardNumber(): ?string
    {
        return $this->paymentMethod?->getMaskedCardNumber();
    }

    /**
     * Get payment method display name
     */
    public function getPaymentMethodDisplayName(): string
    {
        return $this->paymentMethod?->getPaymentMethodDisplayName() ?? 'Unknown Payment Method';
    }
}
