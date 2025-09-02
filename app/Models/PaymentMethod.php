<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentMethod extends Model
{
    // Payment Method Types
    const PAYMENT_METHOD_CARD = 'card';
    const PAYMENT_METHOD_BANK_TRANSFER = 'bank_transfer';
    const PAYMENT_METHOD_WALLET = 'wallet';
    const PAYMENT_METHOD_CRYPTO = 'crypto';
    const PAYMENT_METHOD_MOBILE = 'mobile_payment';
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
        'payment_transaction_id',
        'payment_method_type',
        'payment_method_subtype',
        'card_brand',
        'card_type',
        'card_last_four',
        'card_exp_month',
        'card_exp_year',
        'card_country',
        'card_issuer',
        'card_funding',
        'bank_name',
        'bank_code',
        'account_type',
        'account_last_four',
        'routing_number',
        'iban_last_four',
        'swift_code',
        'wallet_provider',
        'wallet_account_id',
        'wallet_email',
        'crypto_currency',
        'crypto_network',
        'crypto_address',
        'crypto_tx_hash',
        'mobile_carrier',
        'mobile_number',
        'alt_payment_provider',
        'alt_payment_account',
        'payment_method_details',
        'payment_country',
        'is_recurring_capable',
        'requires_authentication',
        'verification_status',
        'risk_score',
        'fraud_checks',
    ];

    protected $casts = [
        'payment_method_details' => 'array',
        'fraud_checks' => 'array',
        'is_recurring_capable' => 'boolean',
        'requires_authentication' => 'boolean',
    ];

    /**
     * Get the payment transaction that owns this payment method
     */
    public function paymentTransaction(): BelongsTo
    {
        return $this->belongsTo(PaymentTransaction::class);
    }

    /**
     * Check if payment method is a card
     */
    public function isCardPayment(): bool
    {
        return $this->payment_method_type === self::PAYMENT_METHOD_CARD;
    }

    /**
     * Check if payment method is a bank transfer
     */
    public function isBankTransfer(): bool
    {
        return $this->payment_method_type === self::PAYMENT_METHOD_BANK_TRANSFER;
    }

    /**
     * Check if payment method is a digital wallet
     */
    public function isWalletPayment(): bool
    {
        return $this->payment_method_type === self::PAYMENT_METHOD_WALLET;
    }

    /**
     * Check if payment method is cryptocurrency
     */
    public function isCryptoPayment(): bool
    {
        return $this->payment_method_type === self::PAYMENT_METHOD_CRYPTO;
    }

    /**
     * Get masked card number for display
     */
    public function getMaskedCardNumber(): ?string
    {
        if (!$this->isCardPayment() || !$this->card_last_four) {
            return null;
        }

        return '**** **** **** ' . $this->card_last_four;
    }

    /**
     * Get payment method display name
     */
    public function getPaymentMethodDisplayName(): string
    {
        switch ($this->payment_method_type) {
            case self::PAYMENT_METHOD_CARD:
                $brand = ucfirst($this->card_brand ?? 'Card');
                $lastFour = $this->card_last_four ? " ****{$this->card_last_four}" : '';
                return $brand . $lastFour;

            case self::PAYMENT_METHOD_BANK_TRANSFER:
                return $this->bank_name ? "{$this->bank_name} Bank Transfer" : 'Bank Transfer';

            case self::PAYMENT_METHOD_WALLET:
                return $this->wallet_provider ? ucfirst(str_replace('_', ' ', $this->wallet_provider)) : 'Digital Wallet';

            case self::PAYMENT_METHOD_CRYPTO:
                return $this->crypto_currency ? strtoupper($this->crypto_currency) . ' Cryptocurrency' : 'Cryptocurrency';

            case self::PAYMENT_METHOD_MOBILE:
                return 'Mobile Payment';

            case self::PAYMENT_METHOD_ALTERNATIVE:
                return $this->alt_payment_provider ? ucfirst($this->alt_payment_provider) : 'Alternative Payment';

            default:
                return 'Unknown Payment Method';
        }
    }

    /**
     * Get payment method summary for API responses
     */
    public function getPaymentMethodSummary(): array
    {
        $summary = [
            'type' => $this->payment_method_type,
            'subtype' => $this->payment_method_subtype,
        ];

        // Add type-specific details
        switch ($this->payment_method_type) {
            case self::PAYMENT_METHOD_CARD:
                $summary['card'] = array_filter([
                    'brand' => $this->card_brand,
                    'type' => $this->card_type,
                    'last_four' => $this->card_last_four,
                    'exp_month' => $this->card_exp_month,
                    'exp_year' => $this->card_exp_year,
                    'country' => $this->card_country,
                    'funding' => $this->card_funding,
                ]);
                break;

            case self::PAYMENT_METHOD_BANK_TRANSFER:
                $summary['bank'] = array_filter([
                    'name' => $this->bank_name,
                    'account_type' => $this->account_type,
                    'account_last_four' => $this->account_last_four,
                ]);
                break;

            case self::PAYMENT_METHOD_WALLET:
                $summary['wallet'] = array_filter([
                    'provider' => $this->wallet_provider,
                    'email' => $this->wallet_email,
                ]);
                break;

            case self::PAYMENT_METHOD_CRYPTO:
                $summary['crypto'] = array_filter([
                    'currency' => $this->crypto_currency,
                    'network' => $this->crypto_network,
                    'address' => $this->crypto_address,
                ]);
                break;

            case self::PAYMENT_METHOD_MOBILE:
                $summary['mobile'] = array_filter([
                    'carrier' => $this->mobile_carrier,
                    'number' => $this->mobile_number ? '***' . substr($this->mobile_number, -4) : null,
                ]);
                break;

            case self::PAYMENT_METHOD_ALTERNATIVE:
                $summary['alternative'] = array_filter([
                    'provider' => $this->alt_payment_provider,
                    'account' => $this->alt_payment_account,
                ]);
                break;
        }

        return array_filter($summary);
    }

    /**
     * Create payment method from webhook data
     */
    public static function createFromWebhookData(int $paymentTransactionId, array $paymentMethodData): self
    {
        $data = [
            'payment_transaction_id' => $paymentTransactionId,
        ];

        $data=array_merge($data,$paymentMethodData);
        // Only include fields that have values
        $data = array_filter($data, fn($value) => $value !== null);

        return self::create($data);
    }
}
