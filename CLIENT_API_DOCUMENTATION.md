# Client Payment Gateway API Documentation

## Overview

This API allows registered users to integrate with our payment gateway service to process payments through various payment providers like Payop. Each user can create multiple API clients with unique credentials and manage their transactions through a secure RESTful API.

## Getting Started

1. **Register as a user** on our platform
2. **Create an API Client** through your dashboard
3. **Get your API credentials** (API Key and Secret Key)
4. **Start making API calls** using your credentials

## Authentication

All API requests must be authenticated using your API Key and Secret Key. These can be provided in two ways:

### 1. Headers (Recommended)
```http
X-API-Key: your-api-key
X-Secret-Key: your-secret-key
```

### 2. Query Parameters
```http
GET /api/v1/payment/payop/currencies?api_key=your-api-key&secret_key=your-secret-key
```

## Base URL

- **Sandbox**: `https://your-domain.com/api/v1/payment`
- **Production**: `https://your-domain.com/api/v1/payment`

## Response Format

All API responses follow a consistent format:

```json
{
    "success": true,
    "message": "Operation completed successfully",
    "data": {
        // Response data here
    }
}
```

Error responses:
```json
{
    "success": false,
    "message": "Error description",
    "error_code": "ERROR_CODE",
    "errors": {
        // Validation errors (if applicable)
    }
}
```

## Payop Integration

### Create Payment

Creates a new payment request and returns a payment URL for the customer.

**Endpoint:** `POST /api/v1/payment/payop/create`

**Request Body:**
```json
{
    "amount": 100.50,
    "currency": "USD",
    "description": "Payment for Order #12345",
    "customer_email": "customer@example.com",
    "customer_name": "John Doe",
    "customer_phone": "+1234567890",
    "order_id": "ORD-12345",
    "language": "en",
    "return_url": "https://yoursite.com/payment/success",
    "cancel_url": "https://yoursite.com/payment/cancel",
    "metadata": {
        "user_id": "123",
        "product_id": "456"
    }
}
```

**Required Fields:**
- `amount` - Payment amount (decimal, min: 0.01)
- `currency` - 3-letter currency code (e.g., USD, EUR)
- `return_url` - URL to redirect after successful payment
- `cancel_url` - URL to redirect after cancelled payment

**Response:**
```json
{
    "success": true,
    "message": "Payment created successfully",
    "data": {
        "transaction_id": "txn_abc123def456",
        "status": "processing",
        "amount": "100.50",
        "currency": "USD",
        "description": "Payment for Order #12345",
        "customer_email": "customer@example.com",
        "customer_name": "John Doe",
        "payment_url": "https://payop.com/checkout/xyz789",
        "gateway_transaction_id": "payop_trans_123",
        "client_order_id": "ORD-12345",
        "created_at": "2024-01-15T10:30:00Z",
        "updated_at": "2024-01-15T10:30:00Z"
    }
}
```

### Get Transaction Details

Retrieve details of a specific transaction.

**Endpoint:** `GET /api/v1/payment/payop/transactions/{transaction_id}`

**Response:**
```json
{
    "success": true,
    "data": {
        "transaction_id": "txn_abc123def456",
        "status": "completed",
        "amount": "100.50",
        "currency": "USD",
        "description": "Payment for Order #12345",
        "customer_email": "customer@example.com",
        "customer_name": "John Doe",
        "payment_url": "https://payop.com/checkout/xyz789",
        "gateway_transaction_id": "payop_trans_123",
        "client_order_id": "ORD-12345",
        "created_at": "2024-01-15T10:30:00Z",
        "updated_at": "2024-01-15T10:35:00Z"
    }
}
```

### List Transactions

Get a paginated list of your transactions with optional filtering.

**Endpoint:** `GET /api/v1/payment/payop/transactions`

**Query Parameters:**
- `status` - Filter by status (pending, processing, completed, failed, cancelled, refunded)
- `currency` - Filter by currency (e.g., USD)
- `from_date` - Start date (YYYY-MM-DD)
- `to_date` - End date (YYYY-MM-DD)
- `per_page` - Items per page (1-100, default: 15)

**Example:**
```http
GET /api/v1/payment/payop/transactions?status=completed&currency=USD&per_page=25
```

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "transaction_id": "txn_abc123def456",
            "status": "completed",
            "amount": "100.50",
            "currency": "USD",
            // ... other fields
        }
    ],
    "pagination": {
        "current_page": 1,
        "per_page": 25,
        "total": 150,
        "last_page": 6
    }
}
```

### Get Available Payment Methods

Get payment methods available for a specific currency.

**Endpoint:** `GET /api/v1/payment/payop/payment-methods?currency=USD`

**Response:**
```json
{
    "success": true,
    "currency": "USD",
    "payment_methods": [
        {
            "id": "card",
            "name": "Credit/Debit Card",
            "type": "card"
        },
        {
            "id": "bank_transfer",
            "name": "Bank Transfer",
            "type": "bank"
        }
    ]
}
```

### Get Supported Currencies

Get list of all supported currencies.

**Endpoint:** `GET /api/v1/payment/payop/currencies`

**Response:**
```json
{
    "success": true,
    "currencies": ["USD", "EUR", "GBP", "CAD"]
}
```

## Transaction Statuses

- `pending` - Transaction created but not yet processed
- `processing` - Payment is being processed
- `completed` - Payment completed successfully
- `failed` - Payment failed
- `cancelled` - Payment was cancelled
- `refunded` - Payment was refunded

## Webhooks

When transaction status changes, we'll send a POST request to your configured webhook URLs.

### Webhook Format

```json
{
    "event": "payment.completed",
    "transaction_id": "txn_abc123def456",
    "client_order_id": "ORD-12345",
    "status": "completed",
    "amount": "100.50",
    "currency": "USD",
    "gateway_transaction_id": "payop_trans_123",
    "customer_email": "customer@example.com",
    "created_at": "2024-01-15T10:30:00Z",
    "updated_at": "2024-01-15T10:35:00Z"
}
```

### Webhook Verification

Each webhook includes a signature header for verification:

```
X-Signature: hash_hmac('sha256', $payload, $your_secret_key)
```

### Webhook Events

- `payment.pending` - Payment created
- `payment.processing` - Payment processing started
- `payment.completed` - Payment completed successfully
- `payment.failed` - Payment failed
- `payment.cancelled` - Payment cancelled
- `payment.refunded` - Payment refunded

## Error Codes

- `UNAUTHORIZED` - Invalid API credentials
- `FORBIDDEN` - Action not allowed (e.g., IP restriction)
- `VALIDATION_ERROR` - Request validation failed
- `CURRENCY_NOT_ALLOWED` - Currency not allowed for your account
- `LIMIT_EXCEEDED` - Transaction exceeds your limits
- `TRANSACTION_NOT_FOUND` - Transaction not found
- `GATEWAY_ERROR` - Payment gateway error
- `INTERNAL_ERROR` - Internal server error

## Rate Limits

- 100 requests per minute per API key
- Daily and monthly transaction limits based on your account settings

## Client Libraries

### PHP Example

```php
<?php

class PaymentGatewayClient
{
    private $baseUrl;
    private $apiKey;
    private $secretKey;

    public function __construct($baseUrl, $apiKey, $secretKey)
    {
        $this->baseUrl = $baseUrl;
        $this->apiKey = $apiKey;
        $this->secretKey = $secretKey;
    }

    public function createPayment($data)
    {
        return $this->makeRequest('POST', '/payop/create', $data);
    }

    public function getTransaction($transactionId)
    {
        return $this->makeRequest('GET', "/payop/transactions/{$transactionId}");
    }

    private function makeRequest($method, $endpoint, $data = null)
    {
        $url = $this->baseUrl . $endpoint;
        
        $headers = [
            'X-API-Key: ' . $this->apiKey,
            'X-Secret-Key: ' . $this->secretKey,
            'Content-Type: application/json'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        if ($method === 'POST' && $data) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }
}

// Usage
$client = new PaymentGatewayClient(
    'https://your-domain.com/api/v1/payment',
    'your-api-key',
    'your-secret-key'
);

$payment = $client->createPayment([
    'amount' => 100.00,
    'currency' => 'USD',
    'return_url' => 'https://yoursite.com/success',
    'cancel_url' => 'https://yoursite.com/cancel'
]);
```

### JavaScript Example

```javascript
class PaymentGatewayClient {
    constructor(baseUrl, apiKey, secretKey) {
        this.baseUrl = baseUrl;
        this.apiKey = apiKey;
        this.secretKey = secretKey;
    }

    async createPayment(data) {
        return this.makeRequest('POST', '/payop/create', data);
    }

    async getTransaction(transactionId) {
        return this.makeRequest('GET', `/payop/transactions/${transactionId}`);
    }

    async makeRequest(method, endpoint, data = null) {
        const url = this.baseUrl + endpoint;
        
        const options = {
            method,
            headers: {
                'X-API-Key': this.apiKey,
                'X-Secret-Key': this.secretKey,
                'Content-Type': 'application/json'
            }
        };

        if (method === 'POST' && data) {
            options.body = JSON.stringify(data);
        }

        const response = await fetch(url, options);
        return response.json();
    }
}

// Usage
const client = new PaymentGatewayClient(
    'https://your-domain.com/api/v1/payment',
    'your-api-key',
    'your-secret-key'
);

const payment = await client.createPayment({
    amount: 100.00,
    currency: 'USD',
    return_url: 'https://yoursite.com/success',
    cancel_url: 'https://yoursite.com/cancel'
});
```

## Support

For technical support, please contact:
- Email: api-support@your-domain.com
- Documentation: https://your-domain.com/docs

## Changelog

### v1.0.0 (2024-01-15)
- Initial API release
- Payop payment gateway integration
- Transaction management
- Webhook notifications 