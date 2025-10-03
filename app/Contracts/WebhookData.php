<?php

namespace App\Contracts;

class WebhookData
{
    public function __construct(
        public readonly string $transactionId,
        public readonly string $status,
        public readonly ?float $amount = null,
        public readonly ?string $currency = null,
        public readonly ?string $gatewayTransactionId = null,
        public readonly ?array $rawData = null,
        public readonly ?string $failureReason = null,
        public readonly ?string $eventType = null,
        public readonly ?array $metadata = null,
        public readonly ?array $paymentMethodDetails = null,


        // Payment method classification
        public readonly ?string $paymentMethodType = null,
        public readonly ?string $paymentMethodSubtype = null,

        // Card details
        public readonly ?string $cardBrand = null,
        public readonly ?string $cardType = null,
        public readonly ?string $cardLastFour = null,
        public readonly ?string $cardExpMonth = null,
        public readonly ?string $cardExpYear = null,
        public readonly ?string $cardCountry = null,
        public readonly ?string $cardIssuer = null,
        public readonly ?string $cardFunding = null,

        // Bank details
        public readonly ?string $bankName = null,
        public readonly ?string $bankCode = null,
        public readonly ?string $accountType = null,
        public readonly ?string $accountLastFour = null,
        public readonly ?string $routingNumber = null,
        public readonly ?string $ibanLastFour = null,
        public readonly ?string $swiftCode = null,

        // Wallet details
        public readonly ?string $walletProvider = null,
        public readonly ?string $walletAccountId = null,
        public readonly ?string $walletEmail = null,

        // Crypto details
        public readonly ?string $cryptoCurrency = null,
        public readonly ?string $cryptoNetwork = null,
        public readonly ?string $cryptoAddress = null,
        public readonly ?string $cryptoTxHash = null,

        // Mobile details
        public readonly ?string $mobileCarrier = null,
        public readonly ?string $mobileNumber = null,

        // Alternative payment details
        public readonly ?string $altPaymentProvider = null,
        public readonly ?string $altPaymentAccount = null,

        // General payment method info
        public readonly ?string $paymentCountry = null,
        public readonly ?bool $isRecurringCapable = null,
        public readonly ?bool $requiresAuthentication = null,
        public readonly ?string $verificationStatus = null,
        public readonly ?string $riskScore = null,
        public readonly ?array $fraudChecks = null
    ) {}

    /**
     * Get the transaction ID
     *
     * @return string
     */
    public function getTransactionId(): string
    {
        return $this->transactionId;
    }

    /**
     * Get the payment status
     *
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * Get the payment amount
     *
     * @return float|null
     */
    public function getAmount(): ?float
    {
        return $this->amount;
    }

    /**
     * Get the currency
     *
     * @return string|null
     */
    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    /**
     * Get the gateway transaction ID
     *
     * @return string|null
     */
    public function getGatewayTransactionId(): ?string
    {
        return $this->gatewayTransactionId;
    }

    /**
     * Get the raw webhook data
     *
     * @return array|null
     */
    public function getRawData(): ?array
    {
        return $this->rawData;
    }

    /**
     * Get the event type
     *
     * @return string|null
     */
    public function getEventType(): ?string
    {
        return $this->eventType;
    }

    /**
     * Get metadata
     *
     * @return array|null
     */
    public function getMetadata(): ?array
    {
        return $this->metadata;
    }

    /**
     * Get payment method details
     *
     * @return array|null
     */
    public function getPaymentMethodDetails(): ?array
    {
        return $this->paymentMethodDetails;
    }

    /**
     * Get payment method type
     *
     * @return string|null
     */
    public function getPaymentMethodType(): ?string
    {
        return $this->paymentMethodType;
    }

    /**
     * Get payment method subtype
     *
     * @return string|null
     */
    public function getPaymentMethodSubtype(): ?string
    {
        return $this->paymentMethodSubtype;
    }

    /**
     * Get card brand
     *
     * @return string|null
     */
    public function getCardBrand(): ?string
    {
        return $this->cardBrand;
    }

    /**
     * Get card type
     *
     * @return string|null
     */
    public function getCardType(): ?string
    {
        return $this->cardType;
    }

    /**
     * Get card last four digits
     *
     * @return string|null
     */
    public function getCardLastFour(): ?string
    {
        return $this->cardLastFour;
    }

    /**
     * Get card expiry month
     *
     * @return string|null
     */
    public function getCardExpMonth(): ?string
    {
        return $this->cardExpMonth;
    }

    /**
     * Get card expiry year
     *
     * @return string|null
     */
    public function getCardExpYear(): ?string
    {
        return $this->cardExpYear;
    }

    /**
     * Get card country
     *
     * @return string|null
     */
    public function getCardCountry(): ?string
    {
        return $this->cardCountry;
    }

    /**
     * Get card issuer
     *
     * @return string|null
     */
    public function getCardIssuer(): ?string
    {
        return $this->cardIssuer;
    }

    /**
     * Get card funding type
     *
     * @return string|null
     */
    public function getCardFunding(): ?string
    {
        return $this->cardFunding;
    }

    /**
     * Get bank name
     *
     * @return string|null
     */
    public function getBankName(): ?string
    {
        return $this->bankName;
    }

    /**
     * Get bank code
     *
     * @return string|null
     */
    public function getBankCode(): ?string
    {
        return $this->bankCode;
    }

    /**
     * Get account type
     *
     * @return string|null
     */
    public function getAccountType(): ?string
    {
        return $this->accountType;
    }

    /**
     * Get account last four digits
     *
     * @return string|null
     */
    public function getAccountLastFour(): ?string
    {
        return $this->accountLastFour;
    }

    /**
     * Get routing number
     *
     * @return string|null
     */
    public function getRoutingNumber(): ?string
    {
        return $this->routingNumber;
    }

    /**
     * Get IBAN last four digits
     *
     * @return string|null
     */
    public function getIbanLastFour(): ?string
    {
        return $this->ibanLastFour;
    }

    /**
     * Get SWIFT code
     *
     * @return string|null
     */
    public function getSwiftCode(): ?string
    {
        return $this->swiftCode;
    }

    /**
     * Get wallet provider
     *
     * @return string|null
     */
    public function getWalletProvider(): ?string
    {
        return $this->walletProvider;
    }

    /**
     * Get wallet account ID
     *
     * @return string|null
     */
    public function getWalletAccountId(): ?string
    {
        return $this->walletAccountId;
    }

    /**
     * Get wallet email
     *
     * @return string|null
     */
    public function getWalletEmail(): ?string
    {
        return $this->walletEmail;
    }

    /**
     * Get crypto currency
     *
     * @return string|null
     */
    public function getCryptoCurrency(): ?string
    {
        return $this->cryptoCurrency;
    }

    /**
     * Get crypto network
     *
     * @return string|null
     */
    public function getCryptoNetwork(): ?string
    {
        return $this->cryptoNetwork;
    }

    /**
     * Get crypto address
     *
     * @return string|null
     */
    public function getCryptoAddress(): ?string
    {
        return $this->cryptoAddress;
    }

    /**
     * Get crypto transaction hash
     *
     * @return string|null
     */
    public function getCryptoTxHash(): ?string
    {
        return $this->cryptoTxHash;
    }

    /**
     * Get mobile carrier
     *
     * @return string|null
     */
    public function getMobileCarrier(): ?string
    {
        return $this->mobileCarrier;
    }

    /**
     * Get mobile number
     *
     * @return string|null
     */
    public function getMobileNumber(): ?string
    {
        return $this->mobileNumber;
    }

    /**
     * Get alternative payment provider
     *
     * @return string|null
     */
    public function getAltPaymentProvider(): ?string
    {
        return $this->altPaymentProvider;
    }

    /**
     * Get alternative payment account
     *
     * @return string|null
     */
    public function getAltPaymentAccount(): ?string
    {
        return $this->altPaymentAccount;
    }

    /**
     * Get payment country
     *
     * @return string|null
     */
    public function getPaymentCountry(): ?string
    {
        return $this->paymentCountry;
    }

    /**
     * Get if payment method is recurring capable
     *
     * @return bool|null
     */
    public function getIsRecurringCapable(): ?bool
    {
        return $this->isRecurringCapable;
    }

    /**
     * Get if payment method requires authentication
     *
     * @return bool|null
     */
    public function getRequiresAuthentication(): ?bool
    {
        return $this->requiresAuthentication;
    }

    /**
     * Get verification status
     *
     * @return string|null
     */
    public function getVerificationStatus(): ?string
    {
        return $this->verificationStatus;
    }

    /**
     * Get risk score
     *
     * @return string|null
     */
    public function getRiskScore(): ?string
    {
        return $this->riskScore;
    }

    /**
     * Get fraud checks
     *
     * @return array|null
     */
    public function getFraudChecks(): ?array
    {
        return $this->fraudChecks;
    }

    /**
     * Convert to array
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'transaction_id' => $this->transactionId,
            'status' => $this->status,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'gateway_transaction_id' => $this->gatewayTransactionId,
            'raw_data' => $this->rawData,
            'event_type' => $this->eventType,
            'metadata' => $this->metadata,
            'payment_method_details' => $this->paymentMethodDetails,

            // Payment method fields
            'payment_method_type' => $this->paymentMethodType,
            'payment_method_subtype' => $this->paymentMethodSubtype,

            // Card details
            'card_brand' => $this->cardBrand,
            'card_type' => $this->cardType,
            'card_last_four' => $this->cardLastFour,
            'card_exp_month' => $this->cardExpMonth,
            'card_exp_year' => $this->cardExpYear,
            'card_country' => $this->cardCountry,
            'card_issuer' => $this->cardIssuer,
            'card_funding' => $this->cardFunding,

            // Bank details
            'bank_name' => $this->bankName,
            'bank_code' => $this->bankCode,
            'account_type' => $this->accountType,
            'account_last_four' => $this->accountLastFour,
            'routing_number' => $this->routingNumber,
            'iban_last_four' => $this->ibanLastFour,
            'swift_code' => $this->swiftCode,

            // Wallet details
            'wallet_provider' => $this->walletProvider,
            'wallet_account_id' => $this->walletAccountId,
            'wallet_email' => $this->walletEmail,

            // Crypto details
            'crypto_currency' => $this->cryptoCurrency,
            'crypto_network' => $this->cryptoNetwork,
            'crypto_address' => $this->cryptoAddress,
            'crypto_tx_hash' => $this->cryptoTxHash,

            // Mobile details
            'mobile_carrier' => $this->mobileCarrier,
            'mobile_number' => $this->mobileNumber,

            // Alternative payment details
            'alt_payment_provider' => $this->altPaymentProvider,
            'alt_payment_account' => $this->altPaymentAccount,

            // General payment method info
            'payment_country' => $this->paymentCountry,
            'is_recurring_capable' => $this->isRecurringCapable,
            'requires_authentication' => $this->requiresAuthentication,
            'verification_status' => $this->verificationStatus,
            'risk_score' => $this->riskScore,
            'fraud_checks' => $this->fraudChecks,
        ];
    }

    /**
     * Get payment method data formatted for PaymentMethod model
     *
     * @return array
     */
    public function getPaymentMethodDataForTransaction(): array
    {
        return array_filter([
            'payment_method_type' => strtolower($this->paymentMethodType),
            'payment_method_subtype' => strtolower($this->paymentMethodSubtype),

            // Card details
            'card_brand' => strtolower($this->cardBrand),
            'card_type' => $this->cardType,
            'card_last_four' => $this->cardLastFour,
            'card_exp_month' => $this->cardExpMonth,
            'card_exp_year' => $this->cardExpYear,
            'card_country' =>strtolower($this->cardCountry),
            'card_issuer' =>strtolower( $this->cardIssuer),
            'card_funding' => $this->cardFunding,

            // Bank details
            'bank_name' => $this->bankName,
            'bank_code' => $this->bankCode,
            'account_type' => $this->accountType,
            'account_last_four' => $this->accountLastFour,
            'routing_number' => $this->routingNumber,
            'iban_last_four' => $this->ibanLastFour,
            'swift_code' => $this->swiftCode,

            // Wallet details
            'wallet_provider' => $this->walletProvider,
            'wallet_account_id' => $this->walletAccountId,
            'wallet_email' => $this->walletEmail,

            // Crypto details
            'crypto_currency' => $this->cryptoCurrency,
            'crypto_network' => $this->cryptoNetwork,
            'crypto_address' => $this->cryptoAddress,
            'crypto_tx_hash' => $this->cryptoTxHash,

            // Mobile details
            'mobile_carrier' => $this->mobileCarrier,
            'mobile_number' => $this->mobileNumber,

            // Alternative payment details
            'alt_payment_provider' => $this->altPaymentProvider,
            'alt_payment_account' => $this->altPaymentAccount,

            // General payment method info
            'payment_country' => $this->paymentCountry,
            'is_recurring_capable' => $this->isRecurringCapable,
            'requires_authentication' => $this->requiresAuthentication,
            'verification_status' => $this->verificationStatus,
            'risk_score' => $this->riskScore,
            'fraud_checks' => $this->fraudChecks,
        ], fn($value) => $value !== null);
    }
}
