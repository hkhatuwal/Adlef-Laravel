# OPPWA Payment Integration Guide

## Overview

This guide explains how to use the OPPWA (Open Payment Platform for Web Applications) payment integration in the Adlef Laravel application.

## Features

- ✅ Create OPPWA checkout sessions
- ✅ Process payments through OPPWA widget
- ✅ Handle payment callbacks and webhooks
- ✅ Track transaction status
- ✅ Support for multiple currencies
- ✅ Web-based payment interface
- ✅ API endpoints for integration

## Configuration

### Environment Variables

Add the following environment variables to your `.env` file:

```env
# OPPWA Configuration
OPPWA_BASE_URL=https://eu-prod.oppwa.com
OPPWA_ENTITY_ID=your_entity_id_here
OPPWA_AUTH_BEARER=your_bearer_token_here
OPPWA_SANDBOX=false
OPPWA_WEBHOOK_SECRET=your_webhook_secret_here
```

### Database Migration

Run the migration to create the `oppwa_transactions` table:

```bash
php artisan migrate
```

## API Usage

### 1. Create Checkout Session

**Endpoint:** `POST /api/oppwa/create-checkout`

**Request:**
```json
{
    "amount": 100.00,
    "currency": "USD",
    "external_order_id": "ORDER-12345",
    "description": "Payment for services",
    "customer_email": "customer@example.com",
    "customer_name": "John Doe",
    "customer_phone": "+1234567890",
    "result_url": "https://yoursite.com/payment/result",
    "callback_url": "https://yoursite.com/api/oppwa/webhook",
    "metadata": {
        "order_id": "ORDER-12345",
        "user_id": 123
    }
}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "transaction_id": "oppwa_abc123def456",
        "checkout_id": "8a8294494e34d2b2014e34d2b2c000001",
        "payment_url": "https://eu-prod.oppwa.com/v1/paymentWidgets.js?checkoutId=...",
        "integrity": "sha256_hash_here",
        "expires_at": "2024-01-28T12:00:00Z"
    }
}
```

### 2. Get Payment Status

**Endpoint:** `GET /api/oppwa/payment-status/{checkoutId}`

**Response:**
```json
{
    "success": true,
    "data": {
        "transaction": {
            "transaction_id": "oppwa_abc123def456",
            "status": "completed",
            "amount": "100.00",
            "currency": "USD",
            "oppwa_payment_id": "8a8294494e34d2b2014e34d2b2c000002"
        },
        "oppwa_response": {
            "id": "8a8294494e34d2b2014e34d2b2c000002",
            "result": {
                "code": "000.100.110",
                "description": "Request successfully processed"
            },
            "amount": "100.00",
            "currency": "USD"
        }
    }
}
```

### 3. Get Transaction Details

**Endpoint:** `GET /api/oppwa/transaction/{transactionId}`

**Response:**
```json
{
    "success": true,
    "data": {
        "transaction_id": "oppwa_abc123def456",
        "external_order_id": "ORDER-12345",
        "status": "completed",
        "amount": "100.00",
        "currency": "USD",
        "payment_url": "https://eu-prod.oppwa.com/v1/paymentWidgets.js?checkoutId=...",
        "oppwa_checkout_id": "8a8294494e34d2b2014e34d2b2c000001",
        "oppwa_payment_id": "8a8294494e34d2b2014e34d2b2c000002",
        "created_at": "2024-01-27T12:00:00Z",
        "updated_at": "2024-01-27T12:05:00Z"
    }
}
```

## Web Interface

### 1. Create Payment Form

Visit `/client/oppwa/create` to access the payment creation form.

### 2. Payment Page

After creating a payment, users are redirected to `/client/oppwa/payment/{transactionId}` where they can complete the payment using the OPPWA widget.

### 3. Payment Results

Users are redirected to `/client/oppwa/result/{transactionId}` after payment completion.

## Webhook Handling

### Webhook Endpoint

**Endpoint:** `POST /api/oppwa/webhook`

The webhook endpoint automatically processes OPPWA payment notifications and updates transaction statuses.

### Webhook Data Structure

```json
{
    "id": "8a8294494e34d2b2014e34d2b2c000002",
    "result": {
        "code": "000.100.110",
        "description": "Request successfully processed"
    },
    "amount": "100.00",
    "currency": "USD",
    "paymentBrand": "VISA",
    "card": {
        "bin": "1234",
        "last4Digits": "1234"
    }
}
```

## Service Usage

### Using OppwaService in Your Code

```php
use App\Services\OppwaService;

class PaymentController extends Controller
{
    private OppwaService $oppwaService;

    public function __construct(OppwaService $oppwaService)
    {
        $this->oppwaService = $oppwaService;
    }

    public function createPayment(Request $request)
    {
        $paymentData = [
            'amount' => 100.00,
            'currency' => 'USD',
            'external_order_id' => 'ORDER-12345',
            'customer_email' => 'customer@example.com',
            'result_url' => route('payment.result'),
            'callback_url' => route('payment.webhook'),
        ];

        $result = $this->oppwaService->createCheckout($paymentData);

        if ($result['success']) {
            return redirect($result['data']['payment_url']);
        }

        return back()->withErrors(['payment' => $result['error']]);
    }
}
```

## Supported Currencies

- USD - US Dollar
- EUR - Euro
- GBP - British Pound
- CAD - Canadian Dollar
- AUD - Australian Dollar
- JPY - Japanese Yen
- CHF - Swiss Franc
- SEK - Swedish Krona
- NOK - Norwegian Krone
- DKK - Danish Krone
- PLN - Polish Zloty
- CZK - Czech Koruna
- HUF - Hungarian Forint
- BGN - Bulgarian Lev
- RON - Romanian Leu
- HRK - Croatian Kuna
- RUB - Russian Ruble
- UAH - Ukrainian Hryvnia
- KZT - Kazakhstani Tenge
- BYN - Belarusian Ruble

## Payment Methods

- **Cards:** Visa, Mastercard, American Express, Discover, Diners Club
- **Bank Transfers:** SEPA, ACH, Wire transfers
- **Digital Wallets:** PayPal, Apple Pay, Google Pay
- **Alternative Payments:** Klarna, Afterpay, Sezzle

## Test Cards (Development)

- **Visa:** 4200000000000000
- **Mastercard:** 5555555555554444
- **American Express:** 378282246310005
- **Expiry:** Any future date
- **CVV:** Any 3 digits

## Error Handling

### Common Error Codes

- `000.100.110` - Request successfully processed
- `000.200.000` - Transaction pending
- `000.400.000` - Transaction declined
- `800.400.100` - Invalid request
- `800.400.200` - Invalid payment data
- `800.400.300` - Invalid amount
- `800.400.400` - Invalid currency
- `800.500.100` - Internal error

### Error Response Format

```json
{
    "success": false,
    "error": "Error message",
    "details": {
        "field_name": ["Validation error message"]
    }
}
```

## Security Considerations

1. **Webhook Verification:** Implement signature verification for webhooks
2. **HTTPS:** Always use HTTPS for webhook endpoints
3. **IP Whitelisting:** Consider whitelisting OPPWA IP addresses
4. **Data Validation:** Validate all incoming data
5. **Logging:** Log all payment activities for audit trails

## Monitoring and Logging

The service includes comprehensive logging for:
- Checkout creation
- Payment status updates
- Webhook processing
- Error conditions

Check the Laravel logs for detailed information about payment processing.

## Support

For issues or questions regarding the OPPWA integration:

1. Check the Laravel logs for error details
2. Verify your OPPWA credentials
3. Ensure webhook endpoints are accessible
4. Contact support with transaction IDs for specific issues
