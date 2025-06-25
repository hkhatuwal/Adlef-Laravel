# Payment Gateway Library

A comprehensive, extensible payment gateway library built following SOLID principles. This library provides a unified interface for processing payments through multiple payment providers while maintaining flexibility and ease of maintenance.

## Architecture Overview

The payment gateway library follows a clean architecture pattern with clear separation of concerns:

### SOLID Principles Implementation

1. **Single Responsibility Principle (SRP)**: Each payment gateway provider handles only one payment service
2. **Open/Closed Principle (OCP)**: Easy to add new providers without modifying existing code
3. **Liskov Substitution Principle (LSP)**: All payment providers are interchangeable
4. **Interface Segregation Principle (ISP)**: Clean, focused interfaces without unnecessary dependencies
5. **Dependency Inversion Principle (DIP)**: High-level modules depend on abstractions, not implementations

### Key Components

```
app/
├── Contracts/
│   ├── PaymentGateway.php           # Main payment gateway interface
│   ├── PaymentGatewayFactory.php    # Factory interface
│   └── PaymentResponse.php          # Standardized response class
├── Services/
│   ├── PaymentService.php           # Main service class
│   └── PaymentGateway/
│       ├── AbstractPaymentGateway.php    # Base class with common functionality
│       ├── PaymentGatewayManager.php     # Concrete factory implementation
│       ├── StripePaymentGateway.php      # Stripe implementation
│       ├── PayPalPaymentGateway.php      # PayPal implementation
│       └── RazorpayPaymentGateway.php    # Razorpay implementation
├── Providers/
│   └── PaymentServiceProvider.php   # Laravel service provider
└── Http/Controllers/
    └── PaymentController.php        # Example controller
```

## Installation & Setup

### 1. Register the Service Provider

Add the payment service provider to your `config/app.php`:

```php
'providers' => [
    // Other providers...
    App\Providers\PaymentServiceProvider::class,
],
```

### 2. Publish Configuration

```bash
php artisan vendor:publish --tag=payment-config
```

### 3. Environment Configuration

Add the following to your `.env` file:

```env
# Default Payment Provider
PAYMENT_DEFAULT_PROVIDER=payop

# Payop Configuration
PAYOP_PUBLIC_KEY=pk_test_...
PAYOP_SECRET_KEY=sk_test_...
PAYOP_SANDBOX=true
PAYOP_WEBHOOK_SECRET=your_payop_webhook_secret

# Stripe Configuration
STRIPE_SECRET_KEY=sk_test_...
STRIPE_PUBLISHABLE_KEY=pk_test_...
STRIPE_WEBHOOK_SECRET=whsec_...

# PayPal Configuration
PAYPAL_CLIENT_ID=your_paypal_client_id
PAYPAL_CLIENT_SECRET=your_paypal_client_secret
PAYPAL_SANDBOX=true
PAYPAL_WEBHOOK_SECRET=your_paypal_webhook_secret

# Razorpay Configuration
RAZORPAY_KEY_ID=rzp_test_...
RAZORPAY_KEY_SECRET=your_razorpay_key_secret
RAZORPAY_WEBHOOK_SECRET=your_razorpay_webhook_secret

# Payment Logging
PAYMENT_LOGGING_ENABLED=true
PAYMENT_LOG_CHANNEL=daily
PAYMENT_LOG_LEVEL=info
```

## Usage Examples

### Basic Payment Processing

```php
use App\Services\PaymentService;

class OrderController extends Controller
{
    public function processPayment(PaymentService $paymentService)
    {
        $paymentData = [
            'amount' => 100.00,
            'currency' => 'usd',
            'payment_method' => 'pm_card_visa', // Stripe payment method
            'description' => 'Order #12345',
            'customer_email' => 'customer@example.com',
            'metadata' => [
                'order_id' => '12345',
                'customer_id' => '67890'
            ]
        ];

        $response = $paymentService->processPayment('stripe', $paymentData);

        if ($response->isSuccessful()) {
            // Payment succeeded
            $transactionId = $response->getTransactionId();
            $status = $response->getStatus();
            // Handle success...
        } else {
            // Payment failed
            $errorMessage = $response->getMessage();
            // Handle failure...
        }
    }
}
```

### Using Dependency Injection

```php
class PaymentController extends Controller
{
    protected PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function charge(Request $request)
    {
        $response = $this->paymentService->processPayment(
            $request->provider,
            $request->only(['amount', 'currency', 'payment_method'])
        );

        return response()->json($response->toArray());
    }
}
```

### Processing Refunds

```php
$refundResponse = $paymentService->refundPayment(
    'stripe',
    'ch_1234567890',  // transaction ID
    50.00,            // refund amount
    [
        'reason' => 'requested_by_customer',
        'metadata' => ['refund_reason' => 'Product returned']
    ]
);
```

### Checking Payment Status

```php
$statusResponse = $paymentService->getPaymentStatus('stripe', 'ch_1234567890');

if ($statusResponse->isSuccessful()) {
    $currentStatus = $statusResponse->getStatus();
    // Handle status...
}
```

### Using Multiple Providers

```php
// Get available providers
$providers = $paymentService->getAvailableProviders();

// Check supported features
$supportsRefunds = $paymentService->providerSupportsFeature('stripe', 'refunds');

// Get supported payment methods
$methods = $paymentService->getSupportedPaymentMethods('razorpay');
```

## Adding New Payment Providers

### Step 1: Create the Provider Class

```php
<?php

namespace App\Services\PaymentGateway;

use App\Contracts\PaymentResponse;

class SquarePaymentGateway extends AbstractPaymentGateway
{
    protected array $supportedFeatures = ['refunds', 'webhooks'];

    public function getProviderName(): string
    {
        return 'square';
    }

    protected function validateConfig(): void
    {
        $requiredKeys = ['application_id', 'access_token', 'location_id'];
        
        foreach ($requiredKeys as $key) {
            if (empty($this->config[$key])) {
                throw new \InvalidArgumentException("Square configuration missing required key: {$key}");
            }
        }

        $this->baseUrl = $this->config['sandbox'] ? 
            'https://connect.squareupsandbox.com' : 
            'https://connect.squareup.com';
            
        $this->headers = [
            'Authorization' => 'Bearer ' . $this->config['access_token'],
            'Content-Type' => 'application/json',
            'Square-Version' => '2023-10-18',
        ];
    }

    public function processPayment(array $paymentData): PaymentResponse
    {
        // Implement Square payment processing
        // ...
    }

    public function refundPayment(string $transactionId, float $amount, array $options = []): PaymentResponse
    {
        // Implement Square refund processing
        // ...
    }

    public function getPaymentStatus(string $transactionId): PaymentResponse
    {
        // Implement Square status checking
        // ...
    }

    public function verifyWebhookSignature(string $payload, string $signature, string $secret): bool
    {
        // Implement Square webhook verification
        // ...
    }

    public function getSupportedPaymentMethods(): array
    {
        return ['card', 'apple_pay', 'google_pay'];
    }
}
```

### Step 2: Register the Provider

In your service provider or configuration:

```php
// In a service provider boot method
$paymentService = app(PaymentService::class);
$paymentService->registerProvider('square', SquarePaymentGateway::class);

// Or register directly with the factory
app(PaymentGatewayFactory::class)->registerProvider('square', SquarePaymentGateway::class);
```

### Step 3: Add Configuration

Update `config/payment.php`:

```php
'gateways' => [
    // ... existing providers
    'square' => [
        'application_id' => env('SQUARE_APPLICATION_ID'),
        'access_token' => env('SQUARE_ACCESS_TOKEN'),
        'location_id' => env('SQUARE_LOCATION_ID'),
        'sandbox' => env('SQUARE_SANDBOX', true),
        'webhook_secret' => env('SQUARE_WEBHOOK_SECRET'),
        'supported_payment_methods' => [
            'card',
            'apple_pay',
            'google_pay'
        ],
    ],
],

'features' => [
    // ... existing features
    'square' => [
        'refunds',
        'webhooks',
        'in_person_payments'
    ],
],
```

## Webhook Handling

### Setting Up Webhook Routes

Add to your `routes/api.php`:

```php
Route::post('/webhooks/{provider}', [PaymentController::class, 'handleWebhook'])
    ->where('provider', 'stripe|paypal|razorpay')
    ->name('payment.webhook');
```

### Webhook Processing

```php
public function handleWebhook(Request $request, string $provider)
{
    $payload = $request->getContent();
    $signature = $request->header('X-Stripe-Signature'); // Adjust header name per provider

    if (!$this->paymentService->verifyWebhookSignature($provider, $payload, $signature)) {
        return response()->json(['error' => 'Invalid signature'], 400);
    }

    $data = json_decode($payload, true);
    
    // Handle different event types
    switch ($data['type']) {
        case 'payment_intent.succeeded':
            // Handle successful payment
            break;
        case 'payment_intent.payment_failed':
            // Handle failed payment
            break;
        // Add more event handlers...
    }

    return response()->json(['status' => 'success']);
}
```

## Testing

### Unit Tests Example

```php
use App\Services\PaymentService;
use App\Services\PaymentGateway\PaymentGatewayManager;

class PaymentServiceTest extends TestCase
{
    public function test_can_process_payment_with_stripe()
    {
        $paymentService = new PaymentService(new PaymentGatewayManager());

        $response = $paymentService->processPayment('stripe', [
            'amount' => 100.00,
            'currency' => 'usd',
            'payment_method' => 'pm_card_visa'
        ]);

        $this->assertTrue($response->isSuccessful());
        $this->assertNotNull($response->getTransactionId());
    }
}
```

## Security Considerations

1. **API Keys**: Store all API keys in environment variables, never in code
2. **Webhook Signatures**: Always verify webhook signatures to prevent fraud
3. **HTTPS**: Use HTTPS for all payment-related communications
4. **Logging**: Be careful not to log sensitive payment information
5. **Validation**: Always validate payment amounts and currencies

## Error Handling

The library provides standardized error handling through the `PaymentResponse` class:

```php
$response = $paymentService->processPayment('stripe', $paymentData);

if ($response->isFailed()) {
    $errorCode = $response->getErrorCode();
    $errorMessage = $response->getMessage();
    
    // Handle specific error codes
    switch ($errorCode) {
        case 'INSUFFICIENT_FUNDS':
            // Handle insufficient funds
            break;
        case 'INVALID_CARD':
            // Handle invalid card
            break;
        default:
            // Handle generic error
            break;
    }
}
```

## Logging

Payment operations are automatically logged. Configure logging in `config/payment.php`:

```php
'logging' => [
    'enabled' => true,
    'channel' => 'daily',
    'level' => 'info',
    'log_requests' => false,  // Don't log sensitive request data
    'log_responses' => false, // Don't log sensitive response data
],
```

## Performance Considerations

1. **Caching**: Payment gateway instances are cached using singleton pattern
2. **Timeouts**: Configurable connection and request timeouts
3. **Retry Logic**: Built-in retry mechanism for failed requests
4. **Async Processing**: Consider using queues for webhook processing

## Contributing

When adding new payment providers:

1. Extend `AbstractPaymentGateway`
2. Implement all required interface methods
3. Add comprehensive tests
4. Update configuration files
5. Add documentation for the new provider

## License

This payment gateway library is open-sourced software licensed under the MIT license. 