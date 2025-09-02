# Payment Gateway Webhook Integration Examples

This document provides examples of how to update existing payment gateway services to use the enhanced WebhookData contract with individual payment method fields.

## Updated Payment Gateway Services

### 1. PayopPaymentGateway.php

```php
public function parseWebhookData(array $webhookData): WebhookData
{
    // Extract transaction ID from Payop webhook structure
    $transactionId = $webhookData['transaction']['order']['id'] ?? null;
    if (!$transactionId) {
        throw new \Exception('Transaction ID not found in Payop webhook data');
    }

    // Extract status from Payop webhook structure
    $payopStatus = $webhookData['invoice']['status'] ?? null;
    if ($payopStatus === null) {
        throw new \Exception('Invoice status not found in Payop webhook data');
    }

    // Map Payop status to standardized status
    $status = match ($payopStatus) {
        1 => 'completed',
        2 => 'failed',
        3 => 'cancelled',
        default => 'pending',
    };

    // Extract payment method details
    $paymentMethod = $webhookData['transaction']['payment_method'] ?? [];
    
    return new WebhookData(
        transactionId: $transactionId,
        status: $status,
        amount: $webhookData['invoice']['amount'] ? (float) $webhookData['invoice']['amount'] : null,
        currency: $webhookData['invoice']['currency'] ?? null,
        gatewayTransactionId: $webhookData['transaction']['id'] ?? null,
        rawData: $webhookData,
        eventType: $webhookData['type'] ?? 'payment',
        
        // Payment method details from Payop
        paymentMethodType: $paymentMethod['type'] ?? null,
        paymentMethodSubtype: $paymentMethod['subtype'] ?? null,
        
        // Card details
        cardBrand: $paymentMethod['card']['brand'] ?? null,
        cardType: $paymentMethod['card']['type'] ?? null,
        cardLastFour: $paymentMethod['card']['masked_number'] ? substr($paymentMethod['card']['masked_number'], -4) : null,
        cardExpMonth: $paymentMethod['card']['exp_month'] ?? null,
        cardExpYear: $paymentMethod['card']['exp_year'] ?? null,
        cardCountry: $paymentMethod['card']['country'] ?? null,
        
        // Bank details
        bankName: $paymentMethod['bank']['name'] ?? null,
        bankCode: $paymentMethod['bank']['code'] ?? null,
        
        // General details
        paymentCountry: $paymentMethod['country'] ?? null,
        requiresAuthentication: $paymentMethod['three_d_secure'] ?? false,
        verificationStatus: $paymentMethod['verification_status'] ?? null
    );
}
```

### 2. PaydoPaymentGateway.php

```php
public function parseWebhookData(array $webhookData): WebhookData
{
    // Extract transaction ID from Paydo webhook structure
    $transactionId = $webhookData['order_id'] ?? $webhookData['transaction_id'] ?? null;
    if (!$transactionId) {
        throw new \Exception('Transaction ID not found in Paydo webhook data');
    }

    // Extract status from Paydo webhook structure
    $paydoStatus = $webhookData['status'] ?? null;
    if ($paydoStatus === null) {
        throw new \Exception('Status not found in Paydo webhook data');
    }

    // Map Paydo status to standardized status
    $status = match (strtolower($paydoStatus)) {
        'success', 'completed', 'paid' => 'completed',
        'failed', 'error', 'declined' => 'failed',
        'cancelled', 'canceled' => 'cancelled',
        default => 'pending',
    };

    // Extract payment method details
    $paymentMethod = $webhookData['payment_method'] ?? [];
    
    return new WebhookData(
        transactionId: $transactionId,
        status: $status,
        amount: $webhookData['amount'] ?? null,
        currency: $webhookData['currency'] ?? null,
        gatewayTransactionId: $webhookData['gateway_transaction_id'] ?? $webhookData['id'] ?? null,
        rawData: $webhookData,
        eventType: $webhookData['type'] ?? $webhookData['event'] ?? 'payment',
        
        // Payment method details from Paydo
        paymentMethodType: $paymentMethod['type'] ?? null,
        
        // Card details
        cardBrand: $paymentMethod['card_brand'] ?? null,
        cardType: $paymentMethod['card_type'] ?? null,
        cardLastFour: $paymentMethod['card_last_four'] ?? null,
        cardExpMonth: $paymentMethod['card_exp_month'] ?? null,
        cardExpYear: $paymentMethod['card_exp_year'] ?? null,
        cardCountry: $paymentMethod['card_country'] ?? null,
        
        // Wallet details
        walletProvider: $paymentMethod['wallet_type'] ?? null,
        walletEmail: $paymentMethod['wallet_email'] ?? null,
        
        // Alternative payment details
        altPaymentProvider: $paymentMethod['alt_provider'] ?? null,
        altPaymentAccount: $paymentMethod['alt_account'] ?? null,
        
        // General details
        paymentCountry: $paymentMethod['country'] ?? null,
        verificationStatus: $paymentMethod['verification'] ?? null,
        riskScore: $paymentMethod['risk_score'] ?? null
    );
}
```

### 3. NgeniusPaymentGateway.php

```php
public function parseWebhookData(array $webhookData): WebhookData
{
    // Extract transaction details from N-Genius webhook
    $orderReference = $webhookData['orderReference'] ?? null;
    if (!$orderReference) {
        throw new \Exception('Order reference not found in N-Genius webhook data');
    }

    // Extract payment details
    $payment = $webhookData['_embedded']['payment'][0] ?? [];
    $paymentMethod = $payment['paymentMethod'] ?? [];
    
    // Map N-Genius status
    $status = match ($payment['state'] ?? '') {
        'CAPTURED', 'PURCHASED' => 'completed',
        'FAILED', 'DECLINED' => 'failed',
        'CANCELLED' => 'cancelled',
        default => 'pending',
    };

    return new WebhookData(
        transactionId: $orderReference,
        status: $status,
        amount: $payment['amount'] ? $payment['amount'] / 100 : null, // Convert from cents
        currency: $payment['currencyCode'] ?? null,
        gatewayTransactionId: $payment['reference'] ?? null,
        rawData: $webhookData,
        eventType: $webhookData['eventName'] ?? 'payment',
        
        // Payment method details from N-Genius
        paymentMethodType: $paymentMethod['name'] === 'CARD' ? 'card' : strtolower($paymentMethod['name'] ?? ''),
        
        // Card details
        cardBrand: strtolower($paymentMethod['brand'] ?? ''),
        cardLastFour: $paymentMethod['pan'] ? substr($paymentMethod['pan'], -4) : null,
        cardExpMonth: $paymentMethod['expiry'] ? substr($paymentMethod['expiry'], 0, 2) : null,
        cardExpYear: $paymentMethod['expiry'] ? '20' . substr($paymentMethod['expiry'], 2, 2) : null,
        
        // 3DS details
        requiresAuthentication: isset($payment['3ds']) && $payment['3ds']['status'] === 'Y',
        verificationStatus: $payment['3ds']['status'] ?? null,
        
        // General details
        paymentCountry: $paymentMethod['cardCountryCode'] ?? null
    );
}
```

### 4. TronGridPaymentGateway.php

```php
public function parseWebhookData(array $webhookData): WebhookData
{
    // Extract transaction details from TronGrid webhook
    $transactionId = $webhookData['transaction_id'] ?? null;
    if (!$transactionId) {
        throw new \Exception('Transaction ID not found in TronGrid webhook data');
    }

    // Extract crypto transaction details
    $transaction = $webhookData['transaction'] ?? [];
    $contractData = $transaction['raw_data']['contract'][0] ?? [];
    $parameter = $contractData['parameter']['value'] ?? [];

    return new WebhookData(
        transactionId: $transactionId,
        status: $webhookData['confirmed'] ? 'completed' : 'pending',
        amount: $parameter['amount'] ? $parameter['amount'] / 1000000 : null, // Convert from Sun to TRX
        currency: 'TRX',
        gatewayTransactionId: $webhookData['txID'] ?? null,
        rawData: $webhookData,
        eventType: 'crypto_payment',
        
        // Crypto payment details
        paymentMethodType: 'crypto',
        cryptoCurrency: $webhookData['token_name'] ?? 'TRX',
        cryptoNetwork: 'tron',
        cryptoAddress: $parameter['to_address'] ?? null,
        cryptoTxHash: $webhookData['txID'] ?? null,
        
        // Verification details
        verificationStatus: $webhookData['confirmed'] ? 'verified' : 'pending',
        requiresAuthentication: false // Crypto payments don't require traditional auth
    );
}
```

## Generic Webhook Processing Service

Here's a generic service that can process webhooks from any gateway using the enhanced WebhookData:

```php
<?php

namespace App\Services;

use App\Models\PaymentTransaction;
use App\Contracts\WebhookData;
use App\Services\PaymentGateway\PayopPaymentGateway;
use App\Services\PaymentGateway\PaydoPaymentGateway;
use App\Services\PaymentGateway\NgeniusPaymentGateway;
use App\Services\PaymentGateway\TronGridPaymentGateway;
use Illuminate\Support\Facades\Log;

class WebhookProcessingService
{
    public function processWebhook(string $gatewayName, array $webhookData): bool
    {
        try {
            // Get the appropriate gateway service
            $gateway = $this->getGatewayService($gatewayName);
            
            // Parse webhook data using the gateway-specific logic
            $webhookDataObject = $gateway->parseWebhookData($webhookData);
            
            // Find the transaction
            $transaction = $this->findTransaction($webhookDataObject);
            
            if (!$transaction) {
                Log::warning('Transaction not found for webhook', [
                    'gateway' => $gatewayName,
                    'transaction_id' => $webhookDataObject->getTransactionId(),
                    'gateway_transaction_id' => $webhookDataObject->getGatewayTransactionId()
                ]);
                return false;
            }

            // Update transaction with payment method details
            $this->updateTransactionPaymentMethod($transaction, $webhookDataObject);
            
            // Update transaction status
            $transaction->updateStatus(
                $webhookDataObject->getStatus(),
                $webhookDataObject->getRawData()
            );

            Log::info('Webhook processed successfully', [
                'gateway' => $gatewayName,
                'transaction_id' => $transaction->transaction_id,
                'status' => $webhookDataObject->getStatus()
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Webhook processing failed', [
                'gateway' => $gatewayName,
                'error' => $e->getMessage(),
                'webhook_data' => $webhookData
            ]);
            return false;
        }
    }

    private function getGatewayService(string $gatewayName)
    {
        return match ($gatewayName) {
            'payop' => app(PayopPaymentGateway::class),
            'paydo' => app(PaydoPaymentGateway::class),
            'ngenius' => app(NgeniusPaymentGateway::class),
            'trongrid' => app(TronGridPaymentGateway::class),
            default => throw new \Exception("Unsupported gateway: {$gatewayName}")
        };
    }

    private function findTransaction(WebhookData $webhookData): ?PaymentTransaction
    {
        // Try to find by gateway transaction ID first
        if ($webhookData->getGatewayTransactionId()) {
            $transaction = PaymentTransaction::where('gateway_transaction_id', $webhookData->getGatewayTransactionId())->first();
            if ($transaction) {
                return $transaction;
            }
        }

        // Fallback to internal transaction ID
        return PaymentTransaction::where('transaction_id', $webhookData->getTransactionId())->first();
    }

    private function updateTransactionPaymentMethod(PaymentTransaction $transaction, WebhookData $webhookData): void
    {
        // Get payment method data formatted for the transaction model
        $paymentMethodData = $webhookData->getPaymentMethodDataForTransaction();
        
        if (!empty($paymentMethodData)) {
            $transaction->update($paymentMethodData);
            
            Log::info('Payment method details updated', [
                'transaction_id' => $transaction->transaction_id,
                'payment_method_type' => $paymentMethodData['payment_method_type'] ?? null,
                'card_brand' => $paymentMethodData['card_brand'] ?? null,
                'wallet_provider' => $paymentMethodData['wallet_provider'] ?? null
            ]);
        }
    }
}
```

## Controller Example

Here's how to use the webhook processing service in your controllers:

```php
<?php

namespace App\Http\Controllers;

use App\Services\WebhookProcessingService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class WebhookController extends Controller
{
    public function __construct(
        private WebhookProcessingService $webhookProcessor
    ) {}

    public function handlePayopWebhook(Request $request): Response
    {
        $success = $this->webhookProcessor->processWebhook('payop', $request->all());
        return response('OK', $success ? 200 : 400);
    }

    public function handlePaydoWebhook(Request $request): Response
    {
        $success = $this->webhookProcessor->processWebhook('paydo', $request->all());
        return response('OK', $success ? 200 : 400);
    }

    public function handleNgeniusWebhook(Request $request): Response
    {
        $success = $this->webhookProcessor->processWebhook('ngenius', $request->all());
        return response('OK', $success ? 200 : 400);
    }

    public function handleTronGridWebhook(Request $request): Response
    {
        $success = $this->webhookProcessor->processWebhook('trongrid', $request->all());
        return response('OK', $success ? 200 : 400);
    }
}
```

## Benefits of the Enhanced WebhookData

1. **Type Safety**: Individual fields provide better type safety and IDE support
2. **Consistency**: Standardized field names across all payment gateways
3. **Easy Access**: Direct getter methods for all payment method details
4. **Database Integration**: Helper method to get data ready for database updates
5. **Extensibility**: Easy to add new payment method types and fields
6. **Debugging**: Clear structure makes debugging webhook issues easier

This approach ensures that all payment method details are properly captured and stored, regardless of which payment gateway is used.
