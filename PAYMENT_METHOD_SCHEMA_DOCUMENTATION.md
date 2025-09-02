# Payment Method Schema Documentation

This document describes the enhanced payment method schema for the PaymentTransaction model, which provides detailed information about payment methods used in transactions.

## Overview

The PaymentTransaction model has been enhanced to store comprehensive payment method details including:
- Card information (brand, type, last 4 digits, expiry, etc.)
- Bank account details (bank name, account type, routing info, etc.)
- Digital wallet information (provider, account details, etc.)
- Cryptocurrency details (currency, network, addresses, etc.)
- Mobile payment information (carrier, number, etc.)
- Alternative payment methods (provider-specific details)

## Database Schema

### New Fields Added

```sql
-- Payment method classification
payment_method_type VARCHAR(255) NULL     -- card, bank_transfer, wallet, crypto, mobile_payment, alternative
payment_method_subtype VARCHAR(255) NULL  -- More specific classification

-- Card-specific details
card_brand VARCHAR(255) NULL              -- visa, mastercard, amex, etc.
card_type VARCHAR(255) NULL               -- credit, debit, prepaid
card_last_four VARCHAR(255) NULL          -- Last 4 digits
card_exp_month VARCHAR(255) NULL          -- Expiry month
card_exp_year VARCHAR(255) NULL           -- Expiry year
card_country VARCHAR(255) NULL            -- Issuing country
card_issuer VARCHAR(255) NULL             -- Bank/issuer name
card_funding VARCHAR(255) NULL            -- credit, debit, prepaid, unknown

-- Bank transfer details
bank_name VARCHAR(255) NULL               -- Bank name
bank_code VARCHAR(255) NULL               -- Bank code/identifier
account_type VARCHAR(255) NULL            -- checking, savings, business
account_last_four VARCHAR(255) NULL       -- Last 4 digits of account
routing_number VARCHAR(255) NULL          -- Routing number
iban_last_four VARCHAR(255) NULL          -- Last 4 digits of IBAN
swift_code VARCHAR(255) NULL              -- SWIFT/BIC code

-- Digital wallet details
wallet_provider VARCHAR(255) NULL         -- paypal, apple_pay, google_pay, etc.
wallet_account_id VARCHAR(255) NULL       -- Wallet account identifier
wallet_email VARCHAR(255) NULL            -- Associated email

-- Cryptocurrency details
crypto_currency VARCHAR(255) NULL         -- BTC, ETH, USDT, etc.
crypto_network VARCHAR(255) NULL          -- ethereum, tron, bitcoin, etc.
crypto_address VARCHAR(255) NULL          -- Wallet address
crypto_tx_hash VARCHAR(255) NULL          -- Transaction hash

-- Mobile payment details
mobile_carrier VARCHAR(255) NULL          -- Mobile carrier
mobile_number VARCHAR(255) NULL           -- Phone number

-- Alternative payment methods
alt_payment_provider VARCHAR(255) NULL    -- qiwi, webmoney, etc.
alt_payment_account VARCHAR(255) NULL     -- Provider account identifier

-- General information
payment_method_details JSON NULL          -- Additional gateway-specific data
payment_country VARCHAR(255) NULL         -- Payment method country
is_recurring_capable BOOLEAN DEFAULT FALSE -- Can be used for recurring payments
requires_authentication BOOLEAN DEFAULT FALSE -- Requires 3DS/authentication

-- Risk and verification
verification_status VARCHAR(255) NULL     -- verified, unverified, failed, pending
risk_score VARCHAR(255) NULL              -- Risk assessment score
fraud_checks JSON NULL                    -- Fraud detection results
```

## Model Constants

The PaymentTransaction model includes comprehensive constants for payment method types:

```php
// Payment Method Types
const PAYMENT_METHOD_CARD = 'card';
const PAYMENT_METHOD_BANK_TRANSFER = 'bank_transfer';
const PAYMENT_METHOD_WALLET = 'wallet';
const PAYMENT_METHOD_CRYPTO = 'crypto';
const PAYMENT_METHOD_MOBILE = 'mobile_payment';
const PAYMENT_METHOD_ALTERNATIVE = 'alternative';

// Card Types
const CARD_TYPE_CREDIT = 'credit';
const CARD_TYPE_DEBIT = 'debit';
const CARD_TYPE_PREPAID = 'prepaid';

// Card Brands
const CARD_BRAND_VISA = 'visa';
const CARD_BRAND_MASTERCARD = 'mastercard';
const CARD_BRAND_AMEX = 'amex';
// ... and more

// Wallet Providers
const WALLET_PAYPAL = 'paypal';
const WALLET_APPLE_PAY = 'apple_pay';
const WALLET_GOOGLE_PAY = 'google_pay';
// ... and more
```

## Usage Examples

### 1. Updating Payment Method Details from Gateway Response

```php
use App\Models\PaymentTransaction;

$transaction = PaymentTransaction::find($id);

// Example gateway response with payment method details
$paymentMethodData = [
    'type' => 'card',
    'card' => [
        'brand' => 'visa',
        'type' => 'credit',
        'last_four' => '4242',
        'exp_month' => '12',
        'exp_year' => '2025',
        'country' => 'US',
        'funding' => 'credit'
    ],
    'country' => 'US',
    'requires_authentication' => true,
    'verification_status' => 'verified'
];

// Update the transaction with payment method details
$transaction->updatePaymentMethodDetails($paymentMethodData);
```

### 2. Different Payment Method Types

#### Credit Card Payment
```php
$cardData = [
    'type' => PaymentTransaction::PAYMENT_METHOD_CARD,
    'card' => [
        'brand' => PaymentTransaction::CARD_BRAND_MASTERCARD,
        'type' => PaymentTransaction::CARD_TYPE_DEBIT,
        'last_four' => '1234',
        'exp_month' => '06',
        'exp_year' => '2026',
        'country' => 'GB',
        'issuer' => 'HSBC Bank',
        'funding' => 'debit'
    ],
    'requires_authentication' => false,
    'verification_status' => PaymentTransaction::VERIFICATION_VERIFIED
];
```

#### Bank Transfer Payment
```php
$bankData = [
    'type' => PaymentTransaction::PAYMENT_METHOD_BANK_TRANSFER,
    'bank' => [
        'name' => 'Chase Bank',
        'account_type' => PaymentTransaction::ACCOUNT_TYPE_CHECKING,
        'account_last_four' => '5678',
        'routing_number' => '021000021'
    ],
    'country' => 'US'
];
```

#### Digital Wallet Payment
```php
$walletData = [
    'type' => PaymentTransaction::PAYMENT_METHOD_WALLET,
    'wallet' => [
        'provider' => PaymentTransaction::WALLET_PAYPAL,
        'email' => 'user@example.com'
    ],
    'is_recurring_capable' => true
];
```

#### Cryptocurrency Payment
```php
$cryptoData = [
    'type' => PaymentTransaction::PAYMENT_METHOD_CRYPTO,
    'crypto' => [
        'currency' => 'USDT',
        'network' => 'tron',
        'address' => 'TXYZabc123...',
        'tx_hash' => '0xabc123...'
    ]
];
```

### 3. Retrieving Payment Method Information

#### Get Payment Method Summary
```php
$transaction = PaymentTransaction::find($id);
$summary = $transaction->getPaymentMethodSummary();

// Example output for a card payment:
// [
//     'type' => 'card',
//     'card' => [
//         'brand' => 'visa',
//         'type' => 'credit',
//         'last_four' => '4242',
//         'exp_month' => '12',
//         'exp_year' => '2025',
//         'country' => 'US',
//         'funding' => 'credit'
//     ]
// ]
```

#### Check Payment Method Type
```php
$transaction = PaymentTransaction::find($id);

if ($transaction->isCardPayment()) {
    $maskedNumber = $transaction->getMaskedCardNumber(); // "**** **** **** 4242"
    $displayName = $transaction->getPaymentMethodDisplayName(); // "Visa ****4242"
}

if ($transaction->isBankTransfer()) {
    // Handle bank transfer specific logic
}

if ($transaction->isWalletPayment()) {
    // Handle wallet payment specific logic
}
```

### 4. API Response with Payment Method Details

The enhanced `toApiResponse()` method now includes payment method details:

```php
$transaction = PaymentTransaction::find($id);
$response = $transaction->toApiResponse();

// Example response:
// [
//     'transaction_id' => 'txn_abc123',
//     'status' => 'completed',
//     'amount' => 100.00,
//     'currency' => 'USD',
//     'payment_method' => [
//         'type' => 'card',
//         'card' => [
//             'brand' => 'visa',
//             'type' => 'credit',
//             'last_four' => '4242',
//             'exp_month' => '12',
//             'exp_year' => '2025',
//             'country' => 'US'
//         ]
//     ],
//     // ... other transaction details
// ]
```

### 5. Webhook Payload with Payment Method Details

Webhooks now include payment method information:

```php
$transaction = PaymentTransaction::find($id);
$webhookPayload = $transaction->getWebhookPayload();

// The payload will include the payment_method field with detailed information
```

### 6. Querying by Payment Method

```php
// Find all Visa card transactions
$visaTransactions = PaymentTransaction::where('card_brand', 'visa')->get();

// Find all PayPal transactions
$paypalTransactions = PaymentTransaction::where('wallet_provider', 'paypal')->get();

// Find all cryptocurrency transactions
$cryptoTransactions = PaymentTransaction::where('payment_method_type', 'crypto')->get();

// Find transactions requiring authentication
$authTransactions = PaymentTransaction::where('requires_authentication', true)->get();
```

## Integration with Payment Gateways

### Using Enhanced WebhookData Contract

The `WebhookData` contract now includes individual fields for all payment method details, making it easier to work with webhook data:

```php
// In your payment gateway service
public function parseWebhookData(array $webhookData): WebhookData
{
    // Extract basic transaction info
    $transactionId = $webhookData['transaction_id'];
    $status = $this->mapStatus($webhookData['status']);
    
    // Extract payment method details directly
    $paymentMethod = $webhookData['payment_method'] ?? [];
    
    return new WebhookData(
        transactionId: $transactionId,
        status: $status,
        amount: $webhookData['amount'] ?? null,
        currency: $webhookData['currency'] ?? null,
        gatewayTransactionId: $webhookData['id'] ?? null,
        rawData: $webhookData,
        
        // Payment method details
        paymentMethodType: $paymentMethod['type'] ?? null,
        paymentMethodSubtype: $paymentMethod['subtype'] ?? null,
        
        // Card details
        cardBrand: $paymentMethod['card']['brand'] ?? null,
        cardType: $paymentMethod['card']['type'] ?? null,
        cardLastFour: $paymentMethod['card']['last_four'] ?? null,
        cardExpMonth: $paymentMethod['card']['exp_month'] ?? null,
        cardExpYear: $paymentMethod['card']['exp_year'] ?? null,
        cardCountry: $paymentMethod['card']['country'] ?? null,
        cardIssuer: $paymentMethod['card']['issuer'] ?? null,
        cardFunding: $paymentMethod['card']['funding'] ?? null,
        
        // Bank details
        bankName: $paymentMethod['bank']['name'] ?? null,
        bankCode: $paymentMethod['bank']['code'] ?? null,
        accountType: $paymentMethod['bank']['account_type'] ?? null,
        accountLastFour: $paymentMethod['bank']['account_last_four'] ?? null,
        routingNumber: $paymentMethod['bank']['routing_number'] ?? null,
        
        // Wallet details
        walletProvider: $paymentMethod['wallet']['provider'] ?? null,
        walletEmail: $paymentMethod['wallet']['email'] ?? null,
        
        // Crypto details
        cryptoCurrency: $paymentMethod['crypto']['currency'] ?? null,
        cryptoNetwork: $paymentMethod['crypto']['network'] ?? null,
        cryptoAddress: $paymentMethod['crypto']['address'] ?? null,
        cryptoTxHash: $paymentMethod['crypto']['tx_hash'] ?? null,
        
        // General details
        paymentCountry: $paymentMethod['country'] ?? null,
        isRecurringCapable: $paymentMethod['is_recurring_capable'] ?? null,
        requiresAuthentication: $paymentMethod['requires_authentication'] ?? null,
        verificationStatus: $paymentMethod['verification_status'] ?? null,
        riskScore: $paymentMethod['risk_score'] ?? null,
        fraudChecks: $paymentMethod['fraud_checks'] ?? null
    );
}

public function processWebhook(array $webhookData): void
{
    $webhookDataObject = $this->parseWebhookData($webhookData);
    $transaction = PaymentTransaction::where('gateway_transaction_id', $webhookDataObject->getGatewayTransactionId())->first();
    
    if ($transaction) {
        // Update transaction with payment method details directly from WebhookData
        $paymentMethodData = $webhookDataObject->getPaymentMethodDataForTransaction();
        if (!empty($paymentMethodData)) {
            $transaction->update($paymentMethodData);
        }
        
        // Update transaction status
        $transaction->updateStatus($webhookDataObject->getStatus(), $webhookDataObject->getRawData());
    }
}
```

### Example Gateway-Specific Implementations

#### Stripe Webhook Example
```php
public function parseWebhookData(array $webhookData): WebhookData
{
    $paymentIntent = $webhookData['data']['object'];
    $paymentMethod = $paymentIntent['payment_method_details'] ?? [];
    
    return new WebhookData(
        transactionId: $paymentIntent['metadata']['transaction_id'],
        status: $this->mapStripeStatus($paymentIntent['status']),
        amount: $paymentIntent['amount'] / 100, // Convert from cents
        currency: strtoupper($paymentIntent['currency']),
        gatewayTransactionId: $paymentIntent['id'],
        
        // Card details from Stripe
        paymentMethodType: $paymentMethod['type'] ?? null,
        cardBrand: $paymentMethod['card']['brand'] ?? null,
        cardType: $paymentMethod['card']['funding'] ?? null,
        cardLastFour: $paymentMethod['card']['last4'] ?? null,
        cardExpMonth: $paymentMethod['card']['exp_month'] ?? null,
        cardExpYear: $paymentMethod['card']['exp_year'] ?? null,
        cardCountry: $paymentMethod['card']['country'] ?? null,
        
        requiresAuthentication: $paymentMethod['card']['three_d_secure']['result'] ?? null ? true : false,
        fraudChecks: $paymentMethod['card']['checks'] ?? null
    );
}
```

#### PayPal Webhook Example
```php
public function parseWebhookData(array $webhookData): WebhookData
{
    $payment = $webhookData['resource'];
    $payer = $payment['payer'] ?? [];
    
    return new WebhookData(
        transactionId: $payment['custom'],
        status: $this->mapPayPalStatus($payment['state']),
        amount: (float) $payment['transactions'][0]['amount']['total'],
        currency: $payment['transactions'][0]['amount']['currency'],
        gatewayTransactionId: $payment['id'],
        
        // PayPal wallet details
        paymentMethodType: 'wallet',
        walletProvider: 'paypal',
        walletEmail: $payer['payer_info']['email'] ?? null,
        paymentCountry: $payer['payer_info']['country_code'] ?? null
    );
}
```

### Accessing Payment Method Details from WebhookData

```php
$webhookData = new WebhookData(/* ... parameters ... */);

// Access individual fields
$cardBrand = $webhookData->getCardBrand();
$walletProvider = $webhookData->getWalletProvider();
$cryptoCurrency = $webhookData->getCryptoCurrency();

// Get all payment method data for database update
$paymentMethodData = $webhookData->getPaymentMethodDataForTransaction();

// Convert to array for logging or API responses
$allData = $webhookData->toArray();
```

## Security Considerations

1. **Sensitive Data**: The schema is designed to store only non-sensitive payment method details
2. **PCI Compliance**: Full card numbers and CVV codes are never stored
3. **Data Masking**: Helper methods provide masked versions for display purposes
4. **Access Control**: Implement appropriate access controls for viewing payment method details

## Migration

To apply the schema changes to your database:

```bash
php artisan migrate
```

This will run the migration file that adds all the new payment method fields to the `payment_transactions` table.

## Best Practices

1. **Always validate** payment method data before storing
2. **Use constants** instead of hardcoded strings for payment method types
3. **Implement proper indexing** for frequently queried payment method fields
4. **Consider data retention** policies for sensitive payment method information
5. **Test thoroughly** with different payment method types and gateways
