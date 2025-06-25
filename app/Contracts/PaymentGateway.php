<?php

namespace App\Contracts;

interface PaymentGateway
{
    /**
     * Process a payment
     *
     * @param array $paymentData
     * @return PaymentResponse
     */
    public function processPayment(array $paymentData): PaymentResponse;

    /**
     * Refund a payment
     *
     * @param string $transactionId
     * @param float $amount
     * @param array $options
     * @return PaymentResponse
     */
    public function refundPayment(string $transactionId, float $amount, array $options = []): PaymentResponse;

    /**
     * Get payment status
     *
     * @param string $transactionId
     * @return PaymentResponse
     */
    public function getPaymentStatus(string $transactionId): PaymentResponse;

    /**
     * Verify webhook signature
     *
     * @param string $payload
     * @param string $signature
     * @param string $secret
     * @return bool
     */
    public function verifyWebhookSignature(string $payload, string $signature, string $secret): bool;

    /**
     * Get supported payment methods
     *
     * @return array
     */
    public function getSupportedPaymentMethods(): array;

    /**
     * Get provider name
     *
     * @return string
     */
    public function getProviderName(): string;

    /**
     * Check if provider supports a specific feature
     *
     * @param string $feature
     * @return bool
     */
    public function supportsFeature(string $feature): bool;
} 