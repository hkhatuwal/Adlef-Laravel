# Webhook Architecture Refactoring Summary

## Overview
This document summarizes the webhook handling architecture refactoring implemented to make the payment gateway system more flexible and extensible when adding new payment gateways.

## Problem Statement
The original webhook handling in `ClientPaymentService` was tightly coupled to a specific webhook payload structure:
```php
// Old hardcoded approach
$transactionId = $webhookData['transaction']['order']['id'];
$status = $webhookData['invoice']['status'];
```

This approach had several issues:
- **Tight Coupling**: Code was specific to one gateway's webhook format
- **Not Extensible**: Adding new payment gateways would require modifying core service logic
- **Brittle**: Changes in any gateway's webhook format would break the system
- **Violation of Open/Closed Principle**: Core service needed modification for each new gateway

## Solution Architecture

### 1. Standardized Webhook Data Structure
Created `App\Contracts\WebhookData` class to standardize webhook information across all gateways:

```php
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
}
```

### 2. Gateway Interface Extension
Extended `PaymentGateway` interface with a new method:
```php
public function parseWebhookData(array $webhookData): WebhookData;
```

### 3. Gateway-Specific Implementations
Each payment gateway now implements its own webhook parsing logic:

#### PayopPaymentGateway
```php
public function parseWebhookData(array $webhookData): WebhookData
{
    $transactionId = $webhookData['transaction']['order']['id'];
    $payopStatus = $webhookData['invoice']['status'];
    
    $status = match ($payopStatus) {
        1 => 'completed',
        2 => 'failed',
        3 => 'cancelled',
        default => 'pending',
    };
    
    return new WebhookData(
        transactionId: $transactionId,
        status: $status,
        // ... other fields
    );
}
```

#### PaydoPaymentGateway
```php
public function parseWebhookData(array $webhookData): WebhookData
{
    $transactionId = $webhookData['order_id'] ?? $webhookData['transaction_id'];
    $status = match (strtolower($webhookData['status'])) {
        'success', 'completed', 'paid' => 'completed',
        'failed', 'error', 'declined' => 'failed',
        'cancelled', 'canceled' => 'cancelled',
        default => 'pending',
    };
    
    return new WebhookData(/* ... */);
}
```

### 4. Updated ClientPaymentService
Modified webhook handling methods to use the gateway factory pattern with optimized single parsing:

```php
public function handleGatewayWebhook(string $gatewayName, array $webhookData): void
{
    try {
        // Create gateway instance once
        $gateway = $this->gatewayFactory->create($gatewayName);
        
        // Parse webhook data once
        $parsedData = $gateway->parseWebhookData($webhookData);
        
        // Pass parsed data to helper methods
        $transaction = $this->findTransactionFromParsedData($gatewayName, $parsedData);
        
        if (!$transaction) {
            // Enhanced logging with parsed transaction ID
            Log::warning('Transaction not found for webhook', [
                'gateway' => $gatewayName,
                'transaction_id' => $parsedData->getTransactionId(),
                'parsed_data' => $parsedData->toArray()
            ]);
            return;
        }

        $this->updateTransactionFromParsedData($transaction, $parsedData);
        $this->sendClientWebhook($transaction);

    } catch (Exception $e) {
        Log::error('Failed to handle gateway webhook', [
            'gateway' => $gatewayName,
            'error' => $e->getMessage(),
            'webhook_data' => $webhookData
        ]);
    }
}
```

## Performance Optimization

### Single Parse Approach
The implementation uses an optimized "parse once, use everywhere" approach:

- **Gateway Instance**: Created once at the beginning of webhook processing
- **Webhook Parsing**: Data parsed once using `parseWebhookData()` 
- **Helper Methods**: Accept `WebhookData` objects instead of raw arrays
- **Consistency**: Same parsed data guaranteed across all operations

**Before Optimization:**
```php
// Gateway created and webhook parsed in findTransactionFromWebhook()
$transaction = $this->findTransactionFromWebhook($gatewayName, $webhookData);

// Gateway created AGAIN and webhook parsed AGAIN in updateTransactionFromWebhook()
$this->updateTransactionFromWebhook($transaction, $webhookData);
```

**After Optimization:**  
```php
// Parse once, use multiple times
$gateway = $this->gatewayFactory->create($gatewayName);
$parsedData = $gateway->parseWebhookData($webhookData);

$transaction = $this->findTransactionFromParsedData($gatewayName, $parsedData);
$this->updateTransactionFromParsedData($transaction, $parsedData);
```

## Benefits

### 1. **Extensibility**
- Adding new payment gateways only requires implementing the `parseWebhookData` method
- No changes needed to `ClientPaymentService` or core webhook handling logic
- Follows the Open/Closed Principle

### 2. **Maintainability**
- Each gateway's webhook logic is encapsulated in its own class
- Changes to one gateway's webhook format don't affect others
- Clear separation of concerns

### 3. **Standardization**
- All webhook data is transformed into a consistent format
- Easier to work with webhook data across the application
- Reduced complexity in webhook handling logic

### 4. **Error Handling**
- Gateway-specific error handling for webhook parsing
- Graceful fallbacks when webhook parsing fails
- Better logging and debugging capabilities

### 5. **Performance**
- **Single Parsing**: Webhook data parsed only once per request
- **Reduced Object Creation**: Gateway instance created once, not multiple times
- **Memory Efficiency**: Eliminates duplicate parsing operations
- **Faster Processing**: Optimized webhook handling pipeline

### 6. **Testing**
- Each gateway's webhook parsing can be tested independently
- Mock different webhook payloads easily
- Better unit test coverage
- Test parsed data consistency across operations

## Implementation Files Modified

### Core Architecture
- `app/Contracts/WebhookData.php` - New standardized webhook data structure
- `app/Contracts/PaymentGateway.php` - Added `parseWebhookData` method
- `app/Services/PaymentGateway/AbstractPaymentGateway.php` - Added abstract method

### Gateway Implementations
- `app/Services/PaymentGateway/PayopPaymentGateway.php` - Implemented Payop-specific parsing
- `app/Services/PaymentGateway/PaydoPaymentGateway.php` - Implemented Paydo-specific parsing
- `app/Services/PaymentGateway/TronGridPaymentGateway.php` - Implemented TronGrid-specific parsing
- `app/Services/PaymentGateway/NgeniusPaymentGateway.php` - Implemented Ngenius-specific parsing

### Service Layer
- `app/Services/ClientPaymentService.php` - Updated to use gateway factory approach

### Bug Fixes
- `app/Http/Controllers/Api/PaymentGateway/PaydoController.php` - Fixed gateway name bug

## Usage for New Payment Gateways

When adding a new payment gateway, follow these steps:

1. **Create Gateway Class**: Extend `AbstractPaymentGateway`
2. **Implement Required Methods**: Including `parseWebhookData`
3. **Register Gateway**: Add to `PaymentGatewayManager`
4. **Create Webhook Controller**: Use existing pattern
5. **No Changes Required**: To `ClientPaymentService` or core webhook logic

### Example for New Gateway:
```php
class NewGatewayPaymentGateway extends AbstractPaymentGateway
{
    public function parseWebhookData(array $webhookData): WebhookData
    {
        // Extract data using this gateway's specific structure
        $transactionId = $webhookData['their_transaction_field'];
        $status = $this->mapTheirStatusToOurs($webhookData['their_status_field']);
        
        return new WebhookData(
            transactionId: $transactionId,
            status: $status,
            rawData: $webhookData
        );
    }
}
```

## Backward Compatibility
- Existing webhook endpoints continue to work without changes
- All existing payment gateways have been updated with appropriate parsing logic
- No breaking changes to the public API

## Testing Recommendations
1. Test each gateway's webhook parsing with real webhook payloads
2. Verify error handling for malformed webhook data
3. Test the fallback mechanism when parsing fails
4. Ensure transaction updates work correctly with parsed data

This architecture provides a solid foundation for scaling the payment gateway system while maintaining code quality and extensibility. 