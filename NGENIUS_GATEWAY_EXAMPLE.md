# N-Genius Payment Gateway Integration

This document provides examples of how to use the N-Genius payment gateway integration.

## Configuration

Add the following environment variables to your `.env` file:

```env
# N-Genius Configuration
NGENIUS_API_KEY=your_base64_encoded_api_key_here
NGENIUS_OUTLET_REFERENCE=your_outlet_reference_here
NGENIUS_SANDBOX=true
NGENIUS_WEBHOOK_SECRET=your_webhook_secret_here
```

## Basic Usage

### 1. Creating a Payment

```php
<?php

use App\Contracts\PaymentGatewayFactory;

// Get the gateway factory (usually injected via DI)
$gatewayFactory = app(PaymentGatewayFactory::class);

// Create N-Genius gateway instance
$ngenius = $gatewayFactory->create('ngenius');

// Prepare payment data
$paymentData = [
    'amount' => 100.00,                    // Amount in major currency units (AED 100.00)
    'currency' => 'AED',                   // Currency code
    'description' => 'Test payment',       // Optional description
    'customer_email' => 'customer@example.com',
    'customer_name' => 'John Doe',         // Optional
    'customer_phone' => '+971501234567',   // Optional
    'language' => 'en',                    // Optional: 'en' or 'ar'
    'return_url' => 'https://yoursite.com/payment/success',
    'cancel_url' => 'https://yoursite.com/payment/cancel',
    'action' => 'PURCHASE',               // Optional: PURCHASE (default), AUTH, or SALE
];

// Process payment
$response = $ngenius->processPayment($paymentData);

if ($response->isSuccessful()) {
    // Redirect user to payment URL
    $paymentUrl = $response->getData()['payment_url'];
    return redirect($paymentUrl);
} else {
    // Handle error
    $error = $response->getMessage();
    return back()->withErrors(['payment' => $error]);
}
```

### 2. Handling Webhooks

```php
<?php

use App\Contracts\PaymentGatewayFactory;
use Illuminate\Http\Request;

public function handleNgeniusWebhook(Request $request)
{
    $gatewayFactory = app(PaymentGatewayFactory::class);
    $ngenius = $gatewayFactory->create('ngenius');
    
    // Get webhook payload
    $payload = $request->getContent();
    $webhookData = json_decode($payload, true);
    
    // Verify webhook signature (optional but recommended)
    $signature = $request->header('X-Signature'); // Adjust header name as needed
    $secret = config('payment.gateways.ngenius.webhook_secret');
    
    if (!$ngenius->verifyWebhookSignature($payload, $signature, $secret)) {
        return response()->json(['error' => 'Invalid signature'], 400);
    }
    
    // Parse webhook data
    try {
        $webhookInfo = $ngenius->parseWebhookData($webhookData);
        
        // Update your transaction based on webhook info
        $transactionId = $webhookInfo->getTransactionId();
        $status = $webhookInfo->getStatus();
        $amount = $webhookInfo->getAmount();
        $currency = $webhookInfo->getCurrency();
        $eventType = $webhookInfo->getEventType();
        
        // Update your database
        // PaymentTransaction::where('transaction_id', $transactionId)
        //     ->update(['status' => $status]);
        
        return response()->json(['status' => 'success'], 200);
        
    } catch (\Exception $e) {
        Log::error('N-Genius webhook processing failed: ' . $e->getMessage());
        return response()->json(['error' => 'Processing failed'], 400);
    }
}
```

### 3. Checking Payment Status

```php
<?php

$response = $ngenius->getPaymentStatus($transactionId);

if ($response->isSuccessful()) {
    $data = $response->getData();
    $status = $data['status'];
    $amount = $data['amount'];
    $currency = $data['currency'];
    
    // Handle based on status
    switch ($status) {
        case 'completed':
            // Payment successful
            break;
        case 'failed':
            // Payment failed
            break;
        case 'pending':
            // Payment still processing
            break;
        case 'cancelled':
            // Payment cancelled
            break;
    }
} else {
    // Handle error
    $error = $response->getMessage();
}
```

### 4. Processing Refunds

```php
<?php

$refundAmount = 50.00; // Refund AED 50.00
$options = [
    'currency' => 'AED',
    'reason' => 'Customer requested refund'
];

$response = $ngenius->refundPayment($transactionId, $refundAmount, $options);

if ($response->isSuccessful()) {
    $data = $response->getData();
    $refundId = $data['transaction_id'];
    $status = $data['status'];
    
    // Handle successful refund
} else {
    // Handle refund error
    $error = $response->getMessage();
}
```

## API Integration

### Creating Payment via API

```bash
POST /api/payment/ngenius/create
Content-Type: application/json
Authorization: Bearer your_api_token

{
    "amount": 100.00,
    "currency": "AED",
    "description": "Test payment",
    "customer_email": "customer@example.com",
    "customer_name": "John Doe",
    "customer_phone": "+971501234567",
    "return_url": "https://yoursite.com/success",
    "cancel_url": "https://yoursite.com/cancel",
    "action": "PURCHASE",
    "language": "en"
}
```

### Response

```json
{
    "success": true,
    "message": "N-Genius payment created successfully",
    "data": {
        "transaction_id": "order_123456789",
        "payment_url": "https://paypage.sandbox.ngenius-payments.com/?code=abc123def456",
        "status": "pending",
        "amount": 100.00,
        "currency": "AED"
    }
}
```

## Webhook Events

N-Genius sends various webhook events. Here are the main ones:

- `AUTHORISED` - Payment has been authorized
- `PURCHASED` - Payment has been completed (for PURCHASE action)
- `CAPTURED` - Previously authorized payment has been captured
- `DECLINED` - Payment was declined
- `CANCELLED` - Payment was cancelled
- `REFUNDED` - Payment has been refunded
- `FAILED` - Payment processing failed

## Supported Features

- ✅ Payment processing (PURCHASE, AUTH, SALE)
- ✅ Hosted payment pages
- ✅ Webhook handling
- ✅ Payment status checking
- ✅ Refunds (full and partial)
- ✅ Multi-currency support
- ✅ Authorization and capture
- ✅ Apple Pay and Samsung Pay
- ✅ Credit/Debit cards (Visa, Mastercard, AMEX, Diners)

## Supported Currencies

AED, USD, EUR, GBP, SAR, KWD, BHD, OMR, QAR, JOD, EGP, and many more.

## Error Handling

The gateway returns standardized error responses:

```php
if ($response->isFailed()) {
    $errorCode = $response->getData()['error_code'] ?? 'UNKNOWN_ERROR';
    $errorMessage = $response->getMessage();
    
    // Log error
    Log::error('N-Genius payment failed', [
        'error_code' => $errorCode,
        'message' => $errorMessage,
        'transaction_data' => $paymentData
    ]);
}
```

## Testing

For testing, use the sandbox environment:
- Set `NGENIUS_SANDBOX=true` in your `.env` file
- Use sandbox API credentials
- Test with sandbox URLs and test cards

## Security Notes

1. Always verify webhook signatures in production
2. Use HTTPS for all webhook endpoints
3. Store API keys securely (use environment variables)
4. Implement proper error handling and logging
5. Validate all input data before processing
