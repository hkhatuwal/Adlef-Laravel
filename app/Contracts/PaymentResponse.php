<?php

namespace App\Contracts;

class PaymentResponse
{
    public function __construct(
        public readonly bool $success,
        public readonly ?string $transactionId = null,
        public readonly ?string $status = null,
        public readonly ?string $paymentUrl = null,
        public readonly ?float $amount = null,
        public readonly ?string $currency = null,
        public readonly ?string $message = null,
        public readonly ?array $data = null,
        public readonly ?string $errorCode = null,
        public readonly ?array $metadata = null
    ) {}

    /**
     * Check if the payment was successful
     *
     * @return bool
     */
    public function isSuccessful(): bool
    {
        return $this->success;
    }

    /**
     * Check if the payment failed
     *
     * @return bool
     */
    public function isFailed(): bool
    {
        return !$this->success;
    }

    /**
     * Get transaction ID
     *
     * @return string|null
     */
    public function getTransactionId(): ?string
    {
        return $this->transactionId;
    }

    /**
     * Get payment status
     *
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * Get amount
     *
     * @return float|null
     */
    public function getAmount(): ?float
    {
        return $this->amount;
    }

    /**
     * Get currency
     *
     * @return string|null
     */
    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    /**
     * Get message
     *
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * Get raw data
     *
     * @return array|null
     */
    public function getData(): ?array
    {
        return $this->data;
    }

    /**
     * Get error code
     *
     * @return string|null
     */
    public function getErrorCode(): ?string
    {
        return $this->errorCode;
    }


    /**
     * Get payment url
     *
     * @return string|null
     */
    public function getPaymentUrl(): ?string
    {
        return $this->paymentUrl;
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
     * Convert to array
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'success' => $this->success,
            'transaction_id' => $this->transactionId,
            'status' => $this->status,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'message' => $this->message,
            'data' => $this->data,
            'error_code' => $this->errorCode,
            'metadata' => $this->metadata,
            'payment_url' => $this->paymentUrl,
        ];
    }
}
