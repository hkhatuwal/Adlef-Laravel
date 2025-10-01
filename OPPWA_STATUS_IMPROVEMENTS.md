# OPPWA Status Identification Improvements

## Overview

The OPPWA service has been significantly improved to properly identify and handle transaction statuses based on the official OPPWA result codes documentation. The previous implementation had several issues with status determination that have been resolved.

## Issues Fixed

### 1. Incorrect Status Detection
**Problem**: The `isOppwaResponseSuccessful()` method was checking `resultDetails.AcquirerResponse` instead of the proper `result.code` field.

**Solution**: Updated to use `result.code` field as per OPPWA documentation.

### 2. Incomplete Status Mapping
**Problem**: Limited status code mapping that didn't cover all OPPWA result codes.

**Solution**: Added comprehensive status code mapping with 100+ result codes covering all categories.

### 3. Missing Regex Pattern Matching
**Problem**: No proper pattern matching for status code categories.

**Solution**: Implemented regex patterns based on OPPWA documentation:
- Success: `/^(000\.000\.|000\.100\.1|000\.[36])/`
- Pending: `/^(000\.200|800\.400\.5|100\.400\.500)/`
- Manual Review: `/^(000\.400\.0[^3]|000\.400\.100)/`

## New Features

### 1. Comprehensive Status Analysis
```php
// New method to analyze OPPWA responses
$statusAnalysis = $oppwaService->analyzeOppwaResponse($response);
```

### 2. Enhanced Status Determination
```php
// Determine status from OPPWA response
$status = OppwaTransaction::determineStatusFromOppwaResponse($response);

// Check specific status types
$isSuccessful = OppwaTransaction::isOppwaResponseSuccessful($response);
$isPending = OppwaTransaction::isOppwaResponsePending($response);
$isFailed = OppwaTransaction::isOppwaResponseFailed($response);
```

### 3. Manual Review Detection
```php
// Check if transaction requires manual review
$requiresReview = OppwaTransaction::requiresManualReview($resultCode);
```

### 4. Detailed Status Descriptions
```php
// Get human-readable status description
$description = OppwaTransaction::getOppwaStatusDescription($resultCode);

// Get status category
$category = OppwaTransaction::getResultCodeCategory($resultCode);
```

## Status Categories

### 1. Successful Transactions
- **Pattern**: `/^(000\.000\.|000\.100\.1|000\.[36])/`
- **Examples**: `000.000.000`, `000.100.110`, `000.300.000`
- **Status**: `completed`

### 2. Success with Manual Review Required
- **Pattern**: `/^(000\.400\.0[^3]|000\.400\.100)/`
- **Examples**: `000.400.000`, `000.400.010`, `000.400.020`
- **Status**: `completed` (but flagged for review)

### 3. Pending Transactions
- **Pattern**: `/^(000\.200|800\.400\.5|100\.400\.500)/`
- **Examples**: `000.200.000`, `800.400.500`, `100.400.500`
- **Status**: `pending`

### 4. Failed Transactions
- **Pattern**: All other codes
- **Examples**: `800.100.153`, `100.100.303`, `800.500.100`
- **Status**: `failed`

## Result Code Categories

### Bank Declined (800.100.xxx)
- CVV errors, card expired, insufficient funds, etc.
- Status: `failed`

### Validation Errors (100.xxx.xxx)
- Missing or invalid data (amount, currency, customer info, etc.)
- Status: `failed`

### System Errors (800.500.xxx)
- Internal errors, timeouts, service unavailable
- Status: `failed`

### 3D Secure Errors (000.400.1xx)
- Authentication issues, card not participating
- Status: `failed`

### Authentication Errors (800.900.xxx)
- Authorization failed, invalid credentials
- Status: `failed`

## Usage Examples

### Basic Status Check
```php
$response = [
    'result' => [
        'code' => '000.000.000',
        'description' => 'Transaction succeeded'
    ]
];

$status = OppwaTransaction::determineStatusFromOppwaResponse($response);
// Returns: 'completed'
```

### Detailed Analysis
```php
$analysis = $oppwaService->analyzeOppwaResponse($response);
/*
Returns:
[
    'status' => 'completed',
    'category' => 'success',
    'description' => 'Transaction succeeded',
    'requires_manual_review' => false,
    'is_successful' => true,
    'is_pending' => false,
    'is_failed' => false,
    'result_code' => '000.000.000',
    'raw_description' => 'Transaction succeeded'
]
*/
```

### Manual Review Detection
```php
$code = '000.400.000';
$requiresReview = OppwaTransaction::requiresManualReview($code);
// Returns: true
```

## Improved Logging

The service now provides detailed logging with status analysis:

```php
Log::channel('oppwa')->info('OPPWA payment status retrieved', [
    'transaction_id' => $transaction->transaction_id,
    'checkout_id' => $checkoutId,
    'status' => $newStatus,
    'oppwa_status' => $responseData['result']['code'] ?? null,
    'status_analysis' => $statusAnalysis,
    'requires_manual_review' => $statusAnalysis['requires_manual_review'] ?? false,
]);
```

## Testing

A test script has been created (`test_oppwa_status.php`) to validate the status identification logic with various result codes.

## Benefits

1. **Accurate Status Detection**: Properly identifies transaction status based on OPPWA documentation
2. **Comprehensive Coverage**: Handles 100+ result codes across all categories
3. **Better Error Handling**: Provides detailed failure reasons and descriptions
4. **Manual Review Support**: Identifies transactions requiring manual review
5. **Enhanced Logging**: Detailed status analysis for debugging and monitoring
6. **Future-Proof**: Easily extensible for new result codes

## Migration Notes

- Existing transactions will continue to work
- New status determination is backward compatible
- Enhanced logging provides better visibility into transaction status
- Manual review detection helps identify suspicious transactions

## References

- [OPPWA Result Codes Documentation](https://docs.latam.ppro.com/reference/result-codes)
- OPPWA Integration Guide
- Laravel Payment Gateway Documentation
