<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Payment Failed - {{ config('app.name') }}</title>

    @vite('resources/css/app.css')
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{asset('assets/css/aos.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/google-fonts.css')}}">
    <link href="{{asset('common/css/fontawesome.min.css')}}" rel="stylesheet"/>

    <style>
        .failed-icon {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            animation: failedPulse 1.5s ease-in-out infinite;
        }

        @keyframes failedPulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.03); opacity: 0.9; }
        }

        .amount-highlight {
            color: #dc2626;
            font-weight: 700;
        }

        .retry-button {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            transition: all 0.2s ease;
        }

        .retry-button:hover {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            transform: translateY(-1px);
        }

        .compact-card {
            background: white;
            border: 1px solid #e5e7eb;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .detail-row:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body class="antialiased bg-gradient-to-br from-red-50 to-rose-100 min-h-screen poppins-medium">
    <div class="container mx-auto px-4 py-4">
        <div class="max-w-md mx-auto" data-aos="fade-up" data-aos-duration="500">

            <!-- Header -->
            <div class="text-center mb-4">
                <div class="mb-3">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="{{ config('app.name') }}" class="h-12 mx-auto object-contain border-2 w-full bg-red-100">
                </div>
                <div class="failed-icon w-16 h-16 mx-auto rounded-full flex items-center justify-center mb-3">
                    <i class="fas fa-times text-white text-2xl"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 mb-1">Payment Failed</h1>
                <p class="text-gray-600 text-sm">Transaction could not be processed</p>
            </div>

            <!-- Main Content Card -->
            <div class="compact-card rounded-lg p-4 mb-4" data-aos="fade-up" data-aos-delay="100">

                <!-- Error Message -->
                <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-4">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-exclamation-triangle text-red-600 text-sm mr-2"></i>
                        <span class="font-medium text-red-900 text-sm">Error Details</span>
                    </div>
                    <p class="text-red-800 text-sm mb-2">
                        {{ $errorMessage ?? 'Your payment could not be processed at this time.' }}
                    </p>
                    <div class="text-xs text-red-700 space-y-1">
                        <p>• Check payment details and try again</p>
                        <p>• Ensure sufficient funds are available</p>
                        <p>• Contact your bank if issues persist</p>
                    </div>
                </div>

                <!-- Transaction Details -->
                @if($transaction)
                <div class="mb-4">
                    <h3 class="font-semibold text-gray-900 mb-3 flex items-center">
                        Transaction Details
                        <span class="ml-auto text-red-600 text-sm font-normal">
                            <i class="fas fa-times-circle mr-1"></i>Failed
                        </span>
                    </h3>

                    <div class="space-y-0">
                        <div class="detail-row">
                            <span class="text-gray-600 text-sm">Amount</span>
                            <span class="text-lg font-bold amount-highlight">
                                {{ number_format($transaction->amount, 2) }} {{ strtoupper($transaction->currency) }}
                            </span>
                        </div>

                        @if($transaction->description)
                        <div class="detail-row">
                            <span class="text-gray-600 text-sm">Description</span>
                            <span class="text-gray-900 text-sm font-medium text-right max-w-48 truncate">
                                {{ $transaction->description }}
                            </span>
                        </div>
                        @endif

                        <div class="detail-row">
                            <span class="text-gray-600 text-sm">Transaction ID</span>
                            <span class="text-gray-900 font-mono text-xs bg-gray-100 px-2 py-1 rounded">
                                {{ $transaction->transaction_id }}
                            </span>
                        </div>

                        <div class="detail-row">
                            <span class="text-gray-600 text-sm">Date</span>
                            <span class="text-gray-900 text-sm">
                                {{ $transaction->updated_at->format('M d, Y h:i A') }}
                            </span>
                        </div>

                        @if($transaction->customer_email)
                        <div class="detail-row">
                            <span class="text-gray-600 text-sm">Email</span>
                            <span class="text-gray-900 text-sm truncate max-w-48">{{ $transaction->customer_email }}</span>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Action Buttons -->
                <div class="space-y-2">
                    @if($transaction)
                    <a href="{{ route('payment.checkout', $transaction->checkout_session_id) }}" class="w-full retry-button text-white py-2.5 px-4 rounded-lg font-semibold text-center block text-sm">
                        <i class="fas fa-redo mr-2"></i>Try Again
                    </a>
                    @endif

                    @if($transaction && $transaction->cancel_url)
                    <a href="{{ $transaction->cancel_url }}" class="w-full bg-gray-600 text-white py-2.5 px-4 rounded-lg font-semibold hover:bg-gray-700 transition-all duration-200 text-center block text-sm">
                        <i class="fas fa-arrow-left mr-2"></i>Return to Merchant
                    </a>
                    @endif

                    <button onclick="window.location.reload()" class="w-full bg-gray-100 text-gray-700 py-2.5 px-4 rounded-lg font-semibold hover:bg-gray-200 transition-all duration-200 text-sm">
                        <i class="fas fa-refresh mr-2"></i>Refresh Page
                    </button>
                </div>
            </div>

            <!-- Support & Help -->
            <div class="compact-card rounded-lg p-4" data-aos="fade-up" data-aos-delay="200">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900">Need Help?</h3>
                        <p class="text-xs text-gray-600">Contact support with your transaction ID</p>
                    </div>
                    <div class="text-right text-xs text-gray-500">
                        <div><i class="fas fa-envelope mr-1"></i>support@{{ parse_url(config('app.url'))['host'] ?? 'example.com' }}</div>
                        <div class="mt-1"><i class="fas fa-phone mr-1"></i>24/7 Support</div>
                    </div>
                </div>

                <!-- Quick Solutions -->
                <div class="bg-gray-50 rounded-lg p-3">
                    <h4 class="text-xs font-semibold text-gray-700 mb-2">Quick Solutions:</h4>
                    <div class="grid grid-cols-1 gap-1 text-xs text-gray-600">
                        <div class="flex items-center">
                            <i class="fas fa-credit-card text-gray-400 mr-2 w-3"></i>
                            <span><strong>Card Issue:</strong> Check details & balance</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-wifi text-gray-400 mr-2 w-3"></i>
                            <span><strong>Connection:</strong> Check internet & retry</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-shield-alt text-gray-400 mr-2 w-3"></i>
                            <span><strong>Security:</strong> Bank verification may be needed</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{asset('common/js/jquery.min.js')}}" crossorigin="anonymous"></script>
    <script src="{{asset('assets/js/aos.js')}}"></script>
    <script src="{{asset('common/js/fontawesome.js')}}"></script>
    <script src="{{asset('assets/js/script.js')}}"></script>

    <script>
        $(document).ready(function() {
            AOS.init({
                duration: 500,
                easing: 'ease-in-out',
                once: true
            });
        });
    </script>
</body>
</html>
