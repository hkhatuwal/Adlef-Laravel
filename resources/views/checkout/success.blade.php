<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Payment Successful - {{ config('app.name') }}</title>

    @vite('resources/css/app.css')
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{asset('assets/css/aos.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/google-fonts.css')}}">
    <link href="{{asset('common/css/fontawesome.min.css')}}" rel="stylesheet"/>

    <!-- Custom Styles -->
    <style>
        .success-card {
            background: white;
            border: 1px solid #e5e7eb;
        }

        .logo-container {
            background: #10b981;
        }

        .success-icon {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            animation: successPulse 2s infinite;
        }

        @keyframes successPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .amount-highlight {
            color: #059669;
            font-weight: 700;
        }

        .success-checkmark {
            animation: checkmarkDraw 0.8s ease-in-out;
        }

        @keyframes checkmarkDraw {
            0% { opacity: 0; transform: scale(0.3); }
            50% { opacity: 1; transform: scale(1.1); }
            100% { opacity: 1; transform: scale(1); }
        }
    </style>
</head>
<body class="antialiased bg-gradient-to-br from-green-50 to-emerald-100 min-h-screen poppins-medium">
    <div class="container mx-auto px-4 py-6">
        <div class="max-w-lg mx-auto" data-aos="fade-up" data-aos-duration="600">
            <!-- Logo & Header -->
            <div class="text-center mb-6">
                <div class="mb-4">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="{{ config('app.name') }}" class="h-12 mx-auto object-contain">
                </div>
                <div class="success-icon w-20 h-20 mx-auto rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-check text-white text-3xl success-checkmark"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Payment Successful!</h1>
                <p class="text-gray-600 text-sm">Your transaction has been completed successfully</p>
            </div>

            <!-- Payment Details Card -->
            @if($transaction)
            <div class="success-card rounded-xl shadow-lg border border-gray-200/50 p-6 mb-6" data-aos="fade-up" data-aos-delay="100">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Transaction Details</h2>
                    <div class="flex items-center text-green-600 text-sm">
                        <i class="fas fa-check-circle mr-1"></i>
                        <span>Confirmed</span>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="flex justify-between items-center py-2 border-b border-gray-100 last:border-b-0">
                        <span class="text-gray-600 text-sm">Amount Paid</span>
                        <span class="text-xl font-bold amount-highlight">
                            {{ number_format($transaction->amount, 2) }} {{ strtoupper($transaction->currency) }}
                        </span>
                    </div>

                    @if($transaction->description)
                    <div class="flex justify-between items-center py-2 border-b border-gray-100 last:border-b-0">
                        <span class="text-gray-600 text-sm">Description</span>
                        <span class="text-gray-900 text-sm font-medium text-right max-w-xs">
                            {{ $transaction->description }}
                        </span>
                    </div>
                    @endif

                    <div class="flex justify-between items-center py-2 border-b border-gray-100 last:border-b-0">
                        <span class="text-gray-600 text-sm">Transaction ID</span>
                        <span class="text-gray-900 font-mono text-xs bg-gray-100 px-3 py-1 rounded">
                            {{ $transaction->transaction_id }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center py-2 border-b border-gray-100 last:border-b-0">
                        <span class="text-gray-600 text-sm">Payment Method</span>
                        <span class="text-gray-900 text-sm font-medium">
                            {{ ucfirst(str_replace('_', ' ', $transaction->payment_method ?? 'N/A')) }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center py-2">
                        <span class="text-gray-600 text-sm">Date & Time</span>
                        <span class="text-gray-900 text-sm">
                            {{ $transaction->updated_at->format('M d, Y h:i A') }}
                        </span>
                    </div>

                    @if($transaction->customer_email)
                    <div class="flex justify-between items-center py-2">
                        <span class="text-gray-600 text-sm">Email</span>
                        <span class="text-gray-900 text-sm">{{ $transaction->customer_email }}</span>
                    </div>
                    @endif
                </div>
            </div>
            @else
            <div class="success-card rounded-xl shadow-lg border border-gray-200/50 p-6 mb-6" data-aos="fade-up" data-aos-delay="100">
                <div class="text-center">
                    <h2 class="text-lg font-semibold text-gray-900 mb-2">Payment Completed</h2>
                    <p class="text-gray-600 text-sm">Your payment has been processed successfully. You should receive a confirmation email shortly.</p>
                </div>
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="space-y-3" data-aos="fade-up" data-aos-delay="200">
                @if($transaction && $transaction->return_url)
                <a href="{{ $transaction->return_url }}" class="w-full bg-green-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-green-700 transition-all duration-200 shadow-sm hover:shadow-md text-center block">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Return to Merchant
                </a>
                @endif

                <button onclick="window.print()" class="w-full bg-gray-100 text-gray-700 py-3 px-4 rounded-lg font-semibold hover:bg-gray-200 transition-all duration-200 shadow-sm hover:shadow-md">
                    <i class="fas fa-download mr-2"></i>
                    Download Receipt
                </button>
            </div>

            <!-- Support Information -->
            <div class="text-center mt-8" data-aos="fade-up" data-aos-delay="300">
                <div class="bg-white rounded-lg p-4 shadow-sm border border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-900 mb-2">Need Help?</h3>
                    <p class="text-xs text-gray-600 mb-3">
                        If you have any questions about this transaction, our support team is here to help.
                    </p>
                    <div class="flex justify-center space-x-4 text-xs">
                        <span class="text-gray-500">
                            <i class="fas fa-envelope mr-1"></i>
                            support@{{ parse_url(config('app.url'))['host'] ?? 'example.com' }}
                        </span>
                        <span class="text-gray-500">
                            <i class="fas fa-shield-alt mr-1"></i>
                            Secure Payment
                        </span>
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
            // Initialize AOS
            AOS.init({
                duration: 600,
                easing: 'ease-in-out',
                once: true
            });

            // Auto-redirect after 10 seconds if return URL exists
            @if($transaction && $transaction->return_url)
            setTimeout(function() {
                if (confirm('Would you like to return to the merchant now?')) {
                    window.location.href = '{{ $transaction->return_url }}';
                }
            }, 10000);
            @endif
        });
    </script>
</body>
</html>
