# Error View Usage Documentation

## Overview
The `checkout.error` view is a reusable error page component that provides a beautiful, user-friendly interface for displaying payment-related errors.

## Basic Usage

### 1. Direct View Rendering
```php
return view('checkout.error', [
    'title' => 'Payment Error',
    'subtitle' => 'Something went wrong',
    'message' => 'Your payment could not be processed.',
    'transaction' => $transaction, // Optional
    'errorCode' => 'PAYMENT_FAILED', // Optional
    'retryUrl' => route('payment.checkout', $sessionId), // Optional
    'cancelUrl' => $transaction->cancel_url, // Optional
    'backUrl' => url()->previous(), // Optional
    'suggestions' => [ // Optional
        'Check your internet connection',
        'Verify payment details',
        'Try a different payment method'
    ]
]);
```

### 2. Using the Helper Method (CheckoutController)
```php
return $this->renderErrorPage(
    title: 'Payment Processing Error',
    subtitle: 'Unable to process your payment request',
    message: 'Failed to create the payment. Please try again.',
    transaction: $transaction,
    errorCode: 'PAYMENT_CREATION_FAILED',
    retryUrl: route('payment.checkout', $transaction->checkout_session_id),
    cancelUrl: $transaction->cancel_url,
    suggestions: [
        'Check your internet connection and try again',
        'Verify your payment details are correct',
        'Try using a different payment method'
    ]
);
```

### 3. Using the Error Route
```php
// Redirect to error page with query parameters
return redirect()->route('payment.error', [
    'title' => 'Payment Error',
    'message' => 'Your payment failed',
    'transaction_id' => $transaction->transaction_id,
    'retry_url' => route('payment.checkout', $sessionId)
]);
```

## Available Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `title` | string | No | Main error title (default: "Payment Error") |
| `subtitle` | string | No | Subtitle text (default: "An error occurred...") |
| `message` | string | No | Detailed error message |
| `errorCode` | string | No | Error code for debugging |
| `transaction` | PaymentTransaction | No | Transaction object for details |
| `retryUrl` | string | No | URL for retry button |
| `cancelUrl` | string | No | URL for cancel/return button |
| `backUrl` | string | No | URL for back button |
| `suggestions` | array | No | Array of suggestion strings |

## Features

- **Responsive Design**: Works on all device sizes
- **Animated Elements**: Smooth animations and transitions
- **Transaction Details**: Shows transaction information when available
- **Action Buttons**: Retry, cancel, and back buttons
- **Error Code Display**: Shows error codes for debugging
- **Custom Suggestions**: Displays helpful suggestions to users
- **Support Information**: Contact details and quick solutions
- **Consistent Styling**: Matches the existing checkout design

## Examples

### Simple Error
```php
return view('checkout.error', [
    'title' => 'Session Expired',
    'message' => 'Your payment session has expired. Please start over.',
    'retryUrl' => route('payment.checkout', $newSessionId)
]);
```

### Error with Transaction Details
```php
return view('checkout.error', [
    'title' => 'Payment Declined',
    'message' => 'Your payment was declined by your bank.',
    'transaction' => $transaction,
    'errorCode' => 'BANK_DECLINED',
    'retryUrl' => route('payment.checkout', $transaction->checkout_session_id),
    'cancelUrl' => $transaction->cancel_url,
    'suggestions' => [
        'Contact your bank to verify the transaction',
        'Try using a different card',
        'Check your account balance'
    ]
]);
```

### Error with Custom Actions
```php
return view('checkout.error', [
    'title' => 'Gateway Unavailable',
    'message' => 'The payment gateway is temporarily unavailable.',
    'backUrl' => route('payment.checkout', $sessionId),
    'suggestions' => [
        'Please try again in a few minutes',
        'Contact support if the issue persists'
    ]
]);
```



