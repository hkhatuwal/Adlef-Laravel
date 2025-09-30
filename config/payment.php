<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Payment Gateway
    |--------------------------------------------------------------------------
    |
    | This option controls the default payment gateway that will be used
    | when no specific gateway is requested.
    |
    */
    'default' => [
        'provider' => env('PAYMENT_DEFAULT_PROVIDER', 'payop'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Payment Gateway Configurations
    |--------------------------------------------------------------------------
    |
    | Here you can configure each payment gateway provider. Each provider
    | can have its own configuration settings.
    |
    */
    'gateways' => [
        'payop' => [
            'public_key' => env('PAYOP_PUBLIC_KEY'),
            'secret_key' => env('PAYOP_SECRET_KEY'),
            'sandbox' => env('PAYOP_SANDBOX', true),
            'webhook_secret' => env('PAYOP_WEBHOOK_SECRET'),
            'supported_payment_methods' => [
                'card',
                'bank_transfer',
                'wallet',
                'crypto',
                'qiwi',
                'webmoney',
                'yandex_money',
                'perfect_money',
                'advcash',
                'payeer',
                'skrill',
                'neteller',
                'paysafecard',
                'mobile_payment'
            ],
        ],
        'paydo' => [
            'public_key' => env('PAYDO_PUBLIC_KEY'),
            'secret_key' => env('PAYDO_SECRET_KEY'),
            'sandbox' => env('PAYDO_SANDBOX', true),
            'webhook_secret' => env('PAYDO_WEBHOOK_SECRET'),
            'supported_payment_methods' => [
                'card',
                'bank_transfer',
                'wallet',
            ],
        ],
        'ngenius' => [
            'api_key' => env('NGENIUS_API_KEY'),
            'outlet_reference' => env('NGENIUS_OUTLET_REFERENCE'),
            'sandbox' => env('NGENIUS_SANDBOX', true),
            'webhook_secret' => env('NGENIUS_WEBHOOK_SECRET'),
            'supported_payment_methods' => [
                'card',
                'wallet',
                'apple_pay',
                'samsung_pay',
                'visa',
                'mastercard',
                'american_express',
                'diners_club'
            ],
        ],
        'pay4work' => [
            'api_key' => env('PAY4WORK_API_KEY'),
            'api_secret' => env('PAY4WORK_API_SECRET'),
            'sandbox' => env('PAY4WORK_SANDBOX', true),
            'webhook_secret' => env('PAY4WORK_WEBHOOK_SECRET'),
            'supported_payment_methods' => [
                'card',
                'credit_card',
                'debit_card',
                'upi',
                'net_banking',
                'wallet',
                'visa',
                'mastercard',
                'american_express',
                'diners_club',
                'rupay'
            ],
        ],
        'trongrid' => [
            'secret_key' => env('TRON_NODE_SECRET'),
            'sandbox' => env('TRON_NODE_SANDBOX', false),
            'main_wallet_address' => env('TRON_RECEVING_WALLET', false),
            'supported_payment_methods' => [
                'crypto',
            ],
        ],

        'stripe' => [
            'secret_key' => env('STRIPE_SECRET_KEY'),
            'publishable_key' => env('STRIPE_PUBLISHABLE_KEY'),
            'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
            'supported_payment_methods' => [
                'card',
                'bank_account',
                'alipay',
                'apple_pay',
                'google_pay',
                'sepa_debit',
                'ideal',
                'sofort'
            ],
        ],

        'paypal' => [
            'client_id' => env('PAYPAL_CLIENT_ID'),
            'client_secret' => env('PAYPAL_CLIENT_SECRET'),
            'sandbox' => env('PAYPAL_SANDBOX', true),
            'webhook_secret' => env('PAYPAL_WEBHOOK_SECRET'),
            'supported_payment_methods' => [
                'paypal',
                'credit_card',
                'debit_card'
            ],
        ],

        'razorpay' => [
            'key_id' => env('RAZORPAY_KEY_ID'),
            'key_secret' => env('RAZORPAY_KEY_SECRET'),
            'webhook_secret' => env('RAZORPAY_WEBHOOK_SECRET'),
            'supported_payment_methods' => [
                'card',
                'netbanking',
                'wallet',
                'upi',
                'emi',
                'paylater',
                'cardless_emi'
            ],
        ],

        // Add more payment providers here
        // 'square' => [
        //     'application_id' => env('SQUARE_APPLICATION_ID'),
        //     'access_token' => env('SQUARE_ACCESS_TOKEN'),
        //     'location_id' => env('SQUARE_LOCATION_ID'),
        //     'sandbox' => env('SQUARE_SANDBOX', true),
        //     'webhook_secret' => env('SQUARE_WEBHOOK_SECRET'),
        //     'supported_payment_methods' => [
        //         'card',
        //         'apple_pay',
        //         'google_pay'
        //     ],
        // ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Feature Support Matrix
    |--------------------------------------------------------------------------
    |
    | This matrix defines which features are supported by each provider.
    | This helps in choosing the right provider for specific use cases.
    |
    */
    'features' => [
        'payop' => [
            'refunds',
            'webhooks',
            'recurring_payments',
            'multi_currency',
            'direct_integration',
            'hosted_payment',
            'crypto_payments'
        ],
        'ngenius' => [
            'refunds',
            'webhooks',
            'multi_currency',
            'direct_integration',
            'hosted_payment',
            'authorization',
            'capture'
        ],
        'pay4work' => [
            'refunds',
            'webhooks',
            'multi_currency',
            'direct_integration',
            'hosted_payment'
        ],
        'stripe' => [
            'refunds',
            'webhooks',
            'recurring_payments',
            'split_payments',
            'marketplace_payments',
            'dispute_management'
        ],
        'paypal' => [
            'refunds',
            'webhooks',
            'subscriptions',
            'marketplace_payments'
        ],
        'razorpay' => [
            'refunds',
            'webhooks',
            'recurring_payments',
            'upi_payments',
            'qr_payments'
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Currency Support
    |--------------------------------------------------------------------------
    |
    | Define which currencies are supported by each provider.
    |
    */
    'currencies' => [
        'payop' => [
            'USD', 'EUR', 'GBP', 'RUB', 'UAH', 'KZT', 'BYN',
            'PLN', 'CZK', 'BGN', 'RON', 'HUF', 'SEK', 'NOK',
            'DKK', 'CHF', 'CAD', 'AUD', 'JPY', 'CNY', 'INR',
            'BRL', 'MXN', 'ARS', 'CLP', 'PEN', 'COP', 'UYU',
            'BTC', 'ETH', 'LTC', 'BCH', 'XRP', 'USDT'
        ],
        'stripe' => [
            'USD', 'EUR', 'GBP', 'CAD', 'AUD', 'JPY', 'INR', 'SGD', 'HKD'
        ],
        'paypal' => [
            'USD', 'EUR', 'GBP', 'CAD', 'AUD', 'JPY'
        ],
        'razorpay' => [
            'INR'
        ],
        'pay4work' => [
            'AED', 'INR', 'USD'
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Transaction Limits
    |--------------------------------------------------------------------------
    |
    | Define transaction limits for each provider.
    |
    */
    'limits' => [
        'payop' => [
            'min_amount' => 0.01,
            'max_amount' => 100000.00,
        ],
        'stripe' => [
            'min_amount' => 0.50, // $0.50 minimum
            'max_amount' => 999999.99,
        ],
        'paypal' => [
            'min_amount' => 0.01,
            'max_amount' => 10000.00,
        ],
        'razorpay' => [
            'min_amount' => 1.00, // ₹1 minimum
            'max_amount' => 1500000.00, // ₹15 lakh maximum
        ],
        'pay4work' => [
            'min_amount' => 0.01,
            'max_amount' => 100000.00,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Webhook Configuration
    |--------------------------------------------------------------------------
    |
    | Configure webhook endpoints for each provider.
    |
    */
    'webhooks' => [
        'routes' => [
            'payop' => '/api/webhooks/payop',
            'ngenius' => '/api/webhooks/ngenius',
            'pay4work' => '/api/webhooks/pay4work',
            'stripe' => '/api/webhooks/stripe',
            'paypal' => '/api/webhooks/paypal',
            'razorpay' => '/api/webhooks/razorpay',
        ],
        'verify_signature' => true,
        'log_requests' => env('APP_DEBUG', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging Configuration
    |--------------------------------------------------------------------------
    |
    | Configure logging for payment operations.
    |
    */
    'logging' => [
        'enabled' => env('PAYMENT_LOGGING_ENABLED', true),
        'channel' => env('PAYMENT_LOG_CHANNEL', 'daily'),
        'level' => env('PAYMENT_LOG_LEVEL', 'info'),
        'log_requests' => env('PAYMENT_LOG_REQUESTS', false),
        'log_responses' => env('PAYMENT_LOG_RESPONSES', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Retry Configuration
    |--------------------------------------------------------------------------
    |
    | Configure retry behavior for failed payment operations.
    |
    */
    'retry' => [
        'enabled' => env('PAYMENT_RETRY_ENABLED', true),
        'max_attempts' => env('PAYMENT_MAX_RETRY_ATTEMPTS', 3),
        'delay_seconds' => env('PAYMENT_RETRY_DELAY', 5),
        'exponential_backoff' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Timeout Configuration
    |--------------------------------------------------------------------------
    |
    | Configure timeout settings for API requests.
    |
    */
    'timeout' => [
        'connection' => env('PAYMENT_CONNECTION_TIMEOUT', 10),
        'request' => env('PAYMENT_REQUEST_TIMEOUT', 30),
    ],
];
