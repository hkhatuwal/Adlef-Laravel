<?php

namespace App\Services\PaymentGateway;

use App\Services\PaymentGateway\AbstractPaymentGateway;

class NgeniusPaymentGateway extends AbstractPaymentGateway
{

    /**
     * @throws Exception
     */
    protected function validateConfig(): void
    {
        if (!isset($this->config['api_key'])) {
            throw new \Exception('NGENIUS API KEY is required.');
        }
        $this->baseUrl = $this->config['sandbox'] ?? false
            ? 'https://api-gateway.sandbox.ngenius-payments.com'
            : 'https://api-gateway.ngenius-payments.com';

        $this->headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];


    }


//$paymentData = [
//'amount' => $request->amount,
//'currency' => strtoupper($request->currency),
//'description' => $request->description,
//'customer_email' => $request->customer_email ?? '',
//'customer_name' => $request->customer_name ?? '',
//'customer_phone' => $request->customer_phone ?? '',
//'order_id' => $request->order_id,
//'language' => $request->language ?? 'en',
//'return_url' => $request->return_url,
//'cancel_url' => $request->cancel_url,
//'metadata' => $request->metadata ?? [],
//];

    public function processPayment(array $paymentData): \App\Contracts\PaymentResponse
    {
        $accessToken = $this->getAccessToken();
        $payload = [
            "action" => "SALE",
            "amount" => [

                "currencyCode" => $paymentData['currency'],
                "value" => $paymentData['amount'],
                "emailAddress" => $paymentData['customer_email'],
            ],
        ];
        $response = $this->makeRequest('POST', '/identity/auth/access-token', $payload, [
            "Content-Type" => "application/vnd.ni-payment.v2+json",
            "Authorization" => "Basic " . $accessToken,
            "Accept" => "application/vnd.ni-payment.v2+json",
        ]);

        return $this->createSuccessResponse([
            'transaction_id' => $response['data']['id'] ?? $orderId,
            'status' => 'pending',
            'amount' => $paymentData['amount'],
            'currency' => $paymentData['currency'] ?? 'USD',
            'message' => 'Hosted payment created successfully',
            'payment_url' => $response['data']['url'] ?? $response['url'],
            'raw_response' => $response
        ]);
    }

    public function refundPayment(string $transactionId, float $amount, array $options = []): \App\Contracts\PaymentResponse
    {
        // TODO: Implement refundPayment() method.
    }

    public function getPaymentStatus(string $transactionId): \App\Contracts\PaymentResponse
    {
        // TODO: Implement getPaymentStatus() method.
    }

    public function verifyWebhookSignature(string $payload, string $signature, string $secret): bool
    {
        // TODO: Implement verifyWebhookSignature() method.
    }

    public function getProviderName(): string
    {
        return "Ngenius";
    }

    private function getAccessToken(): string
    {
        $response = $this->makeRequest('POST', '/identity/auth/access-token', [], [
            "Content-Type" => "application/vnd.ni-identity.v1+json",
            "Authorization" => "Basic " . $this->config['api_key'],
        ]);
        return $response['access_token'] ?? '';
    }

    /**
     * Parse webhook data and return standardized webhook information
     *
     * @param array $webhookData
     * @return \App\Contracts\WebhookData
     * @throws \Exception
     */
    public function parseWebhookData(array $webhookData): \App\Contracts\WebhookData
    {
        // Extract transaction ID from Ngenius webhook structure
        // Note: This structure may need to be adjusted based on actual Ngenius webhook format
        $transactionId = $webhookData['orderReference'] ?? $webhookData['order_id'] ?? null;
        if (!$transactionId) {
            throw new \Exception('Transaction ID not found in Ngenius webhook data');
        }

        // Extract status from Ngenius webhook structure
        $ngeniusStatus = $webhookData['state'] ?? $webhookData['status'] ?? null;
        if ($ngeniusStatus === null) {
            throw new \Exception('Status not found in Ngenius webhook data');
        }

        // Map Ngenius status to standardized status
        // Note: This mapping may need to be adjusted based on actual Ngenius status values
        $status = match (strtolower($ngeniusStatus)) {
            'captured', 'success', 'completed' => 'completed',
            'failed', 'error', 'declined' => 'failed',
            'cancelled', 'canceled', 'voided' => 'cancelled',
            default => 'pending',
        };

        // Extract additional data
        $amount = $webhookData['amount']['value'] ?? $webhookData['amount'] ?? null;
        $currency = $webhookData['amount']['currencyCode'] ?? $webhookData['currency'] ?? null;
        $gatewayTransactionId = $webhookData['id'] ?? $webhookData['paymentId'] ?? null;
        $eventType = $webhookData['eventName'] ?? $webhookData['type'] ?? 'payment';

        return new \App\Contracts\WebhookData(
            transactionId: $transactionId,
            status: $status,
            amount: $amount ? (float) $amount : null,
            currency: $currency,
            gatewayTransactionId: $gatewayTransactionId,
            rawData: $webhookData,
            eventType: $eventType
        );
    }
}
