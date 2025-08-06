# TronGrid Payment Gateway - Amount Fingerprinting System

## Overview

The TronGrid payment gateway now uses an **amount fingerprinting system** to uniquely identify crypto payments without requiring temporary wallet addresses. This solves the problem of distinguishing between multiple payments of the same amount to the same wallet address.

## How It Works

### 1. Amount Fingerprinting

When a payment is created, the system:
- Takes the original converted USDT amount (e.g., `100.00000000`)
- Generates a random 4-digit fingerprint code (e.g., `0023`)
- Adds the fingerprint as micro-decimals: `100.00000000 + 0.000023 = 100.000023`
- This creates a unique fingerprinted amount that customers must pay exactly

### 2. Payment Tracking

Instead of temporary wallets, we now use:
- **Single Main Wallet**: One TRON USDT wallet address for all payments
- **CryptoPaymentOrder Table**: Tracks each payment with its fingerprinted amount
- **Order IDs**: Unique identifiers (e.g., `TG_66E2F4B8_A7X9`) for each payment

### 3. Webhook Processing

When a payment webhook is received:
1. Extract the `amount` and `wallet_address` from webhook data
2. Find the matching `CryptoPaymentOrder` by fingerprinted amount and wallet
3. Update the order status and link it to the blockchain transaction

## Database Schema

### CryptoPaymentOrder Model

```php
// Key fields for fingerprinting
'order_id'           => 'TG_66E2F4B8_A7X9'     // Our unique order ID
'wallet_address'     => 'TQn9Y2khEsLJW1ChVWFMSMeRDow5KcbLSE'  // Main wallet
'original_amount'    => 100.00                 // Original requested amount
'fingerprint_amount' => 100.000023             // Amount with fingerprint
'fingerprint_code'   => '0023'                 // The 4-digit fingerprint
'original_currency'  => 'USD'                  // Currency requested
'payment_currency'   => 'USDT'                 // Currency to be paid
'status'            => 'pending|paid|completed|failed|cancelled|expired'
```

## Configuration

### Required Config Keys

```php'trongrid' => [
    'secret_key' => 'your-webhook-secret',
    'main_wallet_address' => 'TQn9Y2khEsLJW1ChVWFMSMeRDow5KcbLSE', // Your USDT wallet
    'sandbox' => false,
],
```

## API Usage

### 1. Create Payment

```php
$paymentData = [
    'amount' => 100.00,
    'currency' => 'USD',
    'customer_email' => 'customer@example.com',
    'description' => 'Purchase order #12345',
    'return_url' => 'https://yoursite.com/success',
    'cancel_url' => 'https://yoursite.com/cancel',
];

$response = $gateway->processPayment($paymentData);

// Response includes:
// - transaction_id: 'TG_66E2F4B8_A7X9'
// - amount: 100.000023 (fingerprinted)
// - wallet_address: 'TQn9Y2khEsLJW1ChVWFMSMeRDow5KcbLSE'
// - payment_url: '/payment/crypto/TG_66E2F4B8_A7X9'
```

### 2. Check Payment Status

```php
$status = $gateway->getPaymentStatus('TG_66E2F4B8_A7X9');

// Returns detailed status including:
// - Original and fingerprinted amounts
// - Payment URLs and expiration
// - Blockchain transaction details if paid
```

### 3. Webhook Processing

```php
$webhookData = [
    'to_address' => 'TQn9Y2khEsLJW1ChVWFMSMeRDow5KcbLSE',
    'amount' => 100.000023,
    'txid' => 'blockchain-transaction-hash',
    'confirmations' => 3,
    'status' => 'success'
];

$parsedData = $gateway->parseWebhookData($webhookData);
// Automatically finds and updates the matching payment order
```

## Advantages

### 1. **Unique Payment Identification**
- Each payment has a unique fingerprinted amount
- No collision risk even with same amounts
- Easy to match incoming payments to orders

### 2. **Single Wallet Management**
- One main wallet address for all payments  
- No need to generate temporary wallets
- Simpler wallet management and monitoring

### 3. **Better Tracking**
- Complete payment lifecycle tracking
- Detailed order information storage
- Webhook correlation with internal orders

### 4. **Scalable Architecture**
- Handles high volume payments efficiently
- Fast database lookups by amount + wallet
- Minimal resource overhead

## Payment Flow

```
1. Customer initiates payment
   ↓
2. System converts currency to USDT
   ↓  
3. Generate fingerprinted amount (e.g., 100.000023)
   ↓
4. Create CryptoPaymentOrder with unique order_id
   ↓
5. Customer sends exact fingerprinted amount to main wallet
   ↓
6. Webhook received with amount + wallet address
   ↓
7. System finds matching order by fingerprint
   ↓
8. Update order status to paid/completed
```

## Error Handling

### Common Scenarios

1. **No Matching Order Found**: When webhook amount doesn't match any pending order
2. **Expired Orders**: Orders older than 30 minutes are marked as expired
3. **Duplicate Payments**: System handles multiple webhooks for same transaction
4. **Invalid Amounts**: Validation for minimum/maximum payment amounts

### Example Error Responses

```php
// No matching order
throw new \Exception('No matching payment order found for amount 100.000023 and wallet TQn9...');

// Order expired  
$paymentOrder->markAsExpired();

// Invalid wallet address
throw new \InvalidArgumentException("Invalid TRON wallet address provided");
```

## Migration Commands

```bash
# Create the crypto payment orders table
php artisan migrate

# The migration creates:
# - crypto_payment_orders table with fingerprinting fields
# - Indexes for efficient amount + wallet lookups
# - Status tracking and webhook correlation
```

## Security Considerations

1. **Webhook Verification**: Always verify webhook signatures
2. **Amount Validation**: Validate payment amounts match exactly
3. **Expiration Handling**: Automatically expire old payment orders
4. **Address Validation**: Ensure wallet addresses are valid TRON addresses

## Monitoring & Analytics

Track key metrics:
- Payment success/failure rates
- Average payment completion time
- Webhook processing efficiency
- Fingerprint collision rates (should be near zero)

---

This fingerprinting system provides a robust, scalable solution for crypto payment processing while maintaining security and reliability. 