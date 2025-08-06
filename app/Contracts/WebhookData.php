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
        public readonly ?string $eventType = null,
        public readonly ?array $metadata = null
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
        ];
    }
} 