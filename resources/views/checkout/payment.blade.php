<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Payment Checkout - {{ $transaction->description ?? 'Payment' }}</title>

    @vite('resources/css/app.css')
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{asset('assets/css/aos.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/google-fonts.css')}}">
    <link href="{{asset('common/css/fontawesome.min.css')}}" rel="stylesheet"/>
    <link rel="stylesheet" href="{{asset('common/css/toastr.min.css')}}">

    <!-- Custom Styles -->
    <style>
        .payment-option {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .payment-option.selected {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            border-color: #374151 !important;
            background-color: #f9fafb !important;
        }

        .radio-indicator {
            transition: all 0.2s ease;
        }

        .radio-indicator.active {
            border-color: #374151;
            background-color: #374151;
        }

        .radio-indicator.active .indicator-dot {
            display: block !important;
            background-color: white;
        }

        .payment-card {
            background: white;
            border: 1px solid #e5e7eb;
        }

        .logo-container {
            background: #374151;
        }

        .amount-highlight {
            color: #111827;
            font-weight: 700;
        }
    </style>
</head>
<body class="antialiased bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen poppins-medium">
    <div class="container mx-auto px-4 py-6">
        <div class="max-w-lg mx-auto" data-aos="fade-up" data-aos-duration="600">
            <!-- Logo & Header -->
            <div class="text-center mb-6">

                <h1 class="text-2xl font-bold text-gray-900 mb-1">Complete Payment</h1>
                <p class="text-gray-600 text-sm">Secure checkout for your transaction</p>
            </div>

            <!-- Payment Details Card -->
            <div class="payment-card rounded-xl shadow-lg border border-gray-200/50 p-5 mb-5" data-aos="fade-up" data-aos-delay="100">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Payment Details</h2>
                    <div class="flex items-center text-gray-600 text-sm">
                        <i class="fas fa-shield-alt mr-1"></i>
                        <span>Secure</span>
                    </div>
                </div>

                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 text-sm">Amount</span>
                        <span class="text-xl font-bold amount-highlight">
                            {{ number_format($transaction->amount, 2) }} {{ strtoupper($transaction->currency) }}
                        </span>
                    </div>

                    @if($transaction->description)
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 text-sm">Description</span>
                        <span class="text-gray-900 text-sm font-medium">{{ Str::limit($transaction->description, 30) }}</span>
                    </div>
                    @endif

                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 text-sm">Transaction ID</span>
                        <span class="text-gray-900 font-mono text-xs bg-gray-100 px-2 py-1 rounded">
                            {{ Str::limit($transaction->transaction_id, 20) }}
                        </span>
                    </div>

                    @if($transaction->customer_email)
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 text-sm">Email</span>
                        <span class="text-gray-900 text-sm">{{ $transaction->customer_email }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Payment Methods -->
            <div class="payment-card rounded-xl shadow-lg border border-gray-200/50 p-5 mb-5" data-aos="fade-up" data-aos-delay="200">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Payment Method</h2>

                <form action="{{ route('payment.checkout.select', $transaction->checkout_session_id) }}" method="POST" id="paymentForm">
                    @csrf

                    <div class="space-y-3">
                        <!-- Credit/Debit Card Option -->
                        <div class="payment-option-wrapper">
                            <input type="radio" name="payment_method" value="card" id="credit_card" class="sr-only payment-radio" required>
                            <label for="credit_card" class="block">
                                                                 <div class="payment-option border-2 border-gray-200 rounded-lg p-4 cursor-pointer hover:border-gray-400 hover:bg-gray-50 transition-all duration-200">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-credit-card text-gray-600"></i>
                                            </div>
                                            <div>
                                                <h3 class="font-semibold text-gray-900 text-sm">Credit/Debit Card</h3>
                                                <p class="text-gray-500 text-xs">VISA/MASTER/JCB/DINER/DISCOVER</p>
                                            </div>
                                        </div>
                                        <div class="radio-indicator w-5 h-5 border-2 border-gray-300 rounded-full flex items-center justify-center">
                                            <div class="indicator-dot w-2.5 h-2.5 rounded-full hidden"></div>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>

                        <!-- Crypto Option -->
                        <div class="payment-option-wrapper">
                            <input type="radio" name="payment_method" value="crypto" id="crypto" class="sr-only payment-radio" required>
                            <label for="crypto" class="block">
                                                                 <div class="payment-option border-2 border-gray-200 rounded-lg p-4 cursor-pointer hover:border-gray-400 hover:bg-gray-50 transition-all duration-200">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                                                <i class="fab fa-bitcoin text-gray-600"></i>
                                            </div>
                                            <div>
                                                <h3 class="font-semibold text-gray-900 text-sm">Cryptocurrency</h3>
                                                <p class="text-gray-500 text-xs">Bitcoin, Ethereum, USDT</p>
                                            </div>
                                        </div>
                                        <div class="radio-indicator w-5 h-5 border-2 border-gray-300 rounded-full flex items-center justify-center">
                                            <div class="indicator-dot w-2.5 h-2.5 rounded-full hidden"></div>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-6">
                        <button type="submit" class="w-full bg-gray-900 text-white py-3 px-4 rounded-lg font-semibold hover:bg-gray-800 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed shadow-sm hover:shadow-md" id="payButton" disabled>
                            <i class="fas fa-lock mr-2"></i>
                            Continue to Payment
                        </button>
                    </div>
                </form>
            </div>

            <!-- Security Footer -->
            <div class="text-center" data-aos="fade-up" data-aos-delay="300">
                <div class="inline-flex items-center space-x-2 text-gray-500 text-xs bg-white rounded-full px-4 py-2 shadow-sm">
                    <i class="fas fa-shield-alt text-gray-600"></i>
                    <span>SSL encrypted • PCI compliant • 100% secure</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{asset('common/js/jquery.min.js')}}" crossorigin="anonymous"></script>
    <script src="{{asset('assets/js/aos.js')}}"></script>
    <script src="{{asset('common/js/fontawesome.js')}}"></script>
    <script src="{{asset('common/js/toastr.min.js')}}"></script>
    <script src="{{asset('assets/js/script.js')}}"></script>
    <script src="{{asset('common/js/script.js')}}"></script>

    <script>
        $(document).ready(function() {
            // Initialize AOS
            AOS.init({
                duration: 600,
                easing: 'ease-in-out',
                once: true
            });

            console.log('Payment page loaded');

            // Handle radio button changes
            $('.payment-radio').on('change', function() {
                const selectedValue = $(this).val();
                const $selectedOption = $(this).closest('.payment-option-wrapper').find('.payment-option');

                console.log('Payment method selected:', selectedValue);

                // Reset all options
                $('.payment-option').removeClass('selected')
                    .addClass('border-gray-200');
                $('.radio-indicator').removeClass('active');
                $('.indicator-dot').addClass('hidden');

                // Style selected option
                $selectedOption.addClass('selected')
                    .removeClass('border-gray-200');
                $selectedOption.find('.radio-indicator').addClass('active');
                $selectedOption.find('.indicator-dot').removeClass('hidden');

                // Enable pay button
                $('#payButton').prop('disabled', false);
            });

            // Handle clicking on payment option div
            $('.payment-option').on('click', function(e) {
                e.preventDefault();
                const $radio = $(this).closest('.payment-option-wrapper').find('.payment-radio');
                $radio.prop('checked', true).trigger('change');
            });

            // Handle form submission
            $('#paymentForm').on('submit', function(e) {
                const selectedMethod = $('input[name="payment_method"]:checked').val();

                if (!selectedMethod) {
                    e.preventDefault();
                    toastr.warning('Please select a payment method to continue.');
                    return false;
                }

                // Show loading state
                $('#payButton').html('<i class="fas fa-spinner fa-spin mr-2"></i>Processing...').prop('disabled', true);

                console.log('Form submitted with payment method:', selectedMethod);
            });

            // Add subtle hover effects
            $('.payment-option').hover(
                function() {
                    if (!$(this).hasClass('selected')) {
                        $(this).addClass('shadow-sm');
                    }
                },
                function() {
                    if (!$(this).hasClass('selected')) {
                        $(this).removeClass('shadow-sm');
                    }
                }
            );
        });
    </script>
</body>
</html>
