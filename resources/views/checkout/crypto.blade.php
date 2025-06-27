<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Crypto Payment - {{ $transaction->description ?? 'Payment' }}</title>

    @vite('resources/css/app.css')
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{asset('assets/css/aos.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/google-fonts.css')}}">
    <link href="{{asset('common/css/fontawesome.min.css')}}" rel="stylesheet"/>
    <link rel="stylesheet" href="{{asset('common/css/toastr.min.css')}}">

    <style>
        .payment-card {
            background: white;
            border: 1px solid #e5e7eb;
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
        }

        .qr-container {
            background: white;
            padding: 1rem;
            border-radius: 0.5rem;
            border: 2px solid #e5e7eb;
            display: inline-block;
        }

        .copy-button {
            transition: all 0.2s ease;
        }

        .copy-button:hover {
            background-color: #374151;
        }

        .copy-button.copied {
            background-color: #059669;
        }

        .amount-highlight {
            color: #111827;
            font-weight: 700;
        }

        .wallet-address {
            word-break: break-all;
            font-family: 'Courier New', monospace;
            background: #f3f4f6;
            padding: 0.75rem;
            border-radius: 0.375rem;
            border: 1px solid #d1d5db;
        }

        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }

        .countdown-timer {
            font-family: 'Courier New', monospace;
            font-weight: bold;
        }

        .payment-instructions {
            background: #f8fafc;
            border-left: 4px solid #3b82f6;
        }

        /* Additional fixes for layout */
        body {
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif;
        }

        .container {
            max-width: 100%;
            margin-left: auto;
            margin-right: auto;
            padding-left: 1rem;
            padding-right: 1rem;
        }

        @media (min-width: 640px) {
            .container {
                max-width: 640px;
            }
        }

        @media (min-width: 768px) {
            .container {
                max-width: 768px;
            }
        }

        @media (min-width: 1024px) {
            .container {
                max-width: 1024px;
            }
        }

        .grid {
            display: grid;
        }

        .grid-cols-1 {
            grid-template-columns: repeat(1, minmax(0, 1fr));
        }

        @media (min-width: 768px) {
            .md\\:grid-cols-2 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        .gap-6 {
            gap: 1.5rem;
        }

        .text-center {
            text-align: center;
        }

        .bg-gradient-to-br {
            background-image: linear-gradient(to bottom right, #f9fafb, #f3f4f6);
        }

        .min-h-screen {
            min-height: 100vh;
        }

        .flex {
            display: flex;
        }

        .items-center {
            align-items: center;
        }

        .justify-between {
            justify-content: space-between;
        }

        .space-x-4 > :not([hidden]) ~ :not([hidden]) {
            --tw-space-x-reverse: 0;
            margin-right: calc(1rem * var(--tw-space-x-reverse));
            margin-left: calc(1rem * calc(1 - var(--tw-space-x-reverse)));
        }

        .rounded-xl {
            border-radius: 0.75rem;
        }

        .shadow-lg {
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        }

        .p-5 {
            padding: 1.25rem;
        }

        .mb-5 {
            margin-bottom: 1.25rem;
        }

        .mb-6 {
            margin-bottom: 1.5rem;
        }

        .text-2xl {
            font-size: 1.5rem;
            line-height: 2rem;
        }

        .text-lg {
            font-size: 1.125rem;
            line-height: 1.75rem;
        }

        .text-sm {
            font-size: 0.875rem;
            line-height: 1.25rem;
        }

        .text-xs {
            font-size: 0.75rem;
            line-height: 1rem;
        }

        .font-bold {
            font-weight: 700;
        }

        .font-semibold {
            font-weight: 600;
        }

        .font-medium {
            font-weight: 500;
        }

        .text-gray-900 {
            color: #111827;
        }

        .text-gray-700 {
            color: #374151;
        }

        .text-gray-600 {
            color: #4b5563;
        }

        .text-gray-500 {
            color: #6b7280;
        }

        .text-white {
            color: #ffffff;
        }

        .bg-blue-600 {
            background-color: #2563eb;
        }

        .bg-gray-300 {
            background-color: #d1d5db;
        }

        .bg-gray-800 {
            background-color: #1f2937;
        }

        .bg-white {
            background-color: #ffffff;
        }

        .hover\\:bg-blue-700:hover {
            background-color: #1d4ed8;
        }

        .hover\\:bg-gray-400:hover {
            background-color: #9ca3af;
        }

        .hover\\:bg-gray-700:hover {
            background-color: #374151;
        }

        .py-3 {
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
        }

        .px-4 {
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .rounded-lg {
            border-radius: 0.5rem;
        }

        .transition-colors {
            transition-property: color, background-color, border-color, text-decoration-color, fill, stroke;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms;
        }

        .flex-1 {
            flex: 1 1 0%;
        }

        .inline-flex {
            display: inline-flex;
        }

        .rounded-full {
            border-radius: 9999px;
        }

        .px-3 {
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }

        .py-1 {
            padding-top: 0.25rem;
            padding-bottom: 0.25rem;
        }

        .py-2 {
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
        }

        .space-y-2 > :not([hidden]) ~ :not([hidden]) {
            --tw-space-y-reverse: 0;
            margin-top: calc(0.5rem * calc(1 - var(--tw-space-y-reverse)));
            margin-bottom: calc(0.5rem * var(--tw-space-y-reverse));
        }

        .space-y-3 > :not([hidden]) ~ :not([hidden]) {
            --tw-space-y-reverse: 0;
            margin-top: calc(0.75rem * calc(1 - var(--tw-space-y-reverse)));
            margin-bottom: calc(0.75rem * var(--tw-space-y-reverse));
        }

        .space-y-4 > :not([hidden]) ~ :not([hidden]) {
            --tw-space-y-reverse: 0;
            margin-top: calc(1rem * calc(1 - var(--tw-space-y-reverse)));
            margin-bottom: calc(1rem * var(--tw-space-y-reverse));
        }

        .block {
            display: block;
        }

        .w-full {
            width: 100%;
        }

        .mt-2 {
            margin-top: 0.5rem;
        }

        .mb-2 {
            margin-bottom: 0.5rem;
        }

        .mb-3 {
            margin-bottom: 0.75rem;
        }

        .mb-4 {
            margin-bottom: 1rem;
        }

        .mr-1 {
            margin-right: 0.25rem;
        }

        .mr-2 {
            margin-right: 0.5rem;
        }

        .mx-auto {
            margin-left: auto;
            margin-right: auto;
        }

        .h-12 {
            height: 3rem;
        }

        .object-contain {
            object-fit: contain;
        }

        .border-2 {
            border-width: 2px;
        }

        .list-decimal {
            list-style-type: decimal;
        }

        .list-inside {
            list-style-position: inside;
        }

        .font-mono {
            font-family: ui-monospace, SFMono-Regular, "SF Mono", Consolas, "Liberation Mono", Menlo, monospace;
        }

        .bg-gray-100 {
            background-color: #f3f4f6;
        }

        .px-2 {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }

        .py-4 {
            padding-top: 1rem;
            padding-bottom: 1rem;
        }

        .py-6 {
            padding-top: 1.5rem;
            padding-bottom: 1.5rem;
        }

        .text-blue-500 {
            color: #3b82f6;
        }

        .bg-red-100 {
            background-color: #fee2e2;
        }

        .text-red-800 {
            color: #991b1b;
        }

        .w-48 {
            width: 12rem;
        }

        .h-48 {
            height: 12rem;
        }
    </style>
</head>
<body class="antialiased bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-6">
        <div style="max-width: 42rem; margin: 0 auto;" data-aos="fade-up" data-aos-duration="600">
            <!-- Logo & Header -->
            <div class="text-center mb-6">
                <div class="mb-3">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="{{ config('app.name') }}" class="h-12 mx-auto object-contain border-2 w-full">
                </div>
                <h1 class="text-2xl font-bold text-gray-900 mb-1">Cryptocurrency Payment</h1>
                <p class="text-gray-600 text-sm">Send payment to the address below</p>
            </div>

            <!-- Warning Message -->
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-5" data-aos="fade-up" data-aos-delay="50">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <div>
                        <strong>Important:</strong> Do not refresh, close, or navigate away from this page during payment processing. This may cause payment issues.
                    </div>
                </div>
            </div>

            <!-- Payment Status -->
            <div class="payment-card rounded-xl shadow-lg p-5 mb-5" data-aos="fade-up" data-aos-delay="100">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Payment Status</h2>
                    <span class="status-pending px-3 py-1 rounded-full text-xs font-medium">
                        <i class="fas fa-clock mr-1"></i>
                        Awaiting Payment
                    </span>
                </div>

                <div class="text-center">
                    <div class="countdown-timer text-xl text-gray-700 mb-2" id="countdown">
                        <i class="fas fa-hourglass-half mr-2"></i>
                        <span id="timer">29:59</span>
                    </div>
                    <p class="text-gray-500 text-sm">Payment expires in</p>
                </div>
            </div>

            <!-- QR Code & Wallet Address -->
            <div class="payment-card rounded-xl shadow-lg p-5 mb-5" data-aos="fade-up" data-aos-delay="200">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 text-center">Scan QR Code or Copy Address</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- QR Code -->
                    <div class="text-center">
                        <div class="qr-container">
                            <div id="qrcode" class="w-48 h-48 mx-auto" width="192" height="192"></div>
                        </div>
                        <p class="text-gray-500 text-sm mt-2">Scan with your crypto wallet</p>
                    </div>

                    <!-- Payment Details -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Wallet Address (TRON)</label>
                            <div class="wallet-address text-sm">
                                {{ $walletAddress }}
                            </div>
                            <button type="button" class="copy-button w-full mt-2 bg-gray-800 text-white py-2 px-4 rounded-lg text-sm font-medium hover:bg-gray-700 transition-colors" onclick="copyToClipboard('{{ $walletAddress }}', this)">
                                <i class="fas fa-copy mr-2"></i>
                                Copy Address
                            </button>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Amount to Send</label>
                            <div class="text-2xl font-bold text-gray-900">
                                {{ number_format($transaction->amount, 2) }} {{ strtoupper($transaction->currency) }}
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Network</label>
                            <div class="text-gray-900 font-medium">TRON (TRX)</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Instructions -->
            <div class="payment-instructions rounded-xl p-5 mb-5" data-aos="fade-up" data-aos-delay="300">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">
                    <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                    Payment Instructions
                </h3>
                <ol class="list-decimal list-inside space-y-2 text-gray-700">
                    <li>Copy the wallet address above or scan the QR code</li>
                    <li>Open your crypto wallet (Trust Wallet, TronLink, etc.)</li>
                    <li>Send exactly <strong>{{ number_format($transaction->amount, 2) }} {{ strtoupper($transaction->currency) }}</strong> to the provided address</li>
                    <li>Use TRON network (TRC20) for the transaction</li>
                    <li>Payment confirmation may take 1-5 minutes</li>
                </ol>
            </div>

            <!-- Transaction Details -->
            <div class="payment-card rounded-xl shadow-lg p-5 mb-5" data-aos="fade-up" data-aos-delay="400">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Transaction Details</h2>

                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 text-sm">Amount</span>
                        <span class="text-lg font-bold amount-highlight">
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

                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 text-sm">Payment Method</span>
                        <span class="text-gray-900 text-sm font-medium">
                            <i class="fab fa-bitcoin mr-1"></i>
                            Cryptocurrency (TRON)
                        </span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex space-x-4 mb-5" data-aos="fade-up" data-aos-delay="500">
                <button type="button" class="flex-1 bg-blue-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-blue-700 transition-colors" onclick="checkPaymentStatus()">
                    <i class="fas fa-sync-alt mr-2"></i>
                    Check Payment Status
                </button>

                @if($transaction->cancel_url)
                <a href="{{ $transaction->cancel_url }}" class="flex-1 bg-gray-300 text-gray-700 py-3 px-4 rounded-lg font-semibold hover:bg-gray-400 transition-colors text-center">
                    <i class="fas fa-times mr-2"></i>
                    Cancel Payment
                </a>
                @endif
            </div>

            <!-- Security Footer -->
            <div class="text-center" data-aos="fade-up" data-aos-delay="600">
                <div class="inline-flex items-center space-x-2 text-gray-500 text-xs bg-white rounded-full px-4 py-2 shadow-sm">
                    <i class="fas fa-shield-alt text-gray-600"></i>
                    <span>Blockchain secured • Decentralized • Transparent</span>
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

    <!-- QR Code Library with fallback -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <script>
        // Timer variables - Calculate remaining time based on transaction updated_at
        const transactionUpdatedAt = new Date('{{ \Carbon\Carbon::parse($transaction->updated_at)->toISOString() }}');
        const currentTime = new Date(); // already in local time

        // Get both timestamps in UTC by using `.getTime()` (returns milliseconds since epoch UTC)
        const elapsedSeconds = Math.floor((currentTime.getTime() - transactionUpdatedAt.getTime()) / 1000);

        const totalTimeLimit = 30 * 60; // 30 minutes in seconds
        let timeLeft = Math.max(0, totalTimeLimit - elapsedSeconds);
        let timerInterval;

        $(document).ready(function() {
            console.log('Document ready - initializing payment page');
            console.log('Time remaining:', timeLeft, 'seconds');

            // Initialize AOS if it exists
            if (typeof AOS !== 'undefined') {
                AOS.init({
                    duration: 600,
                    easing: 'ease-in-out',
                    once: true
                });
            }

            // Generate QR Code with a small delay to ensure library is loaded
            setTimeout(function() {
                generateQRCode();
            }, 500);

            // Check if timer has already expired
            if (timeLeft <= 0) {
                showExpiredMessage();
            } else {
                // Start countdown timer
                startTimer();
            }

            // Auto-refresh payment status every 30 seconds
            setInterval(checkPaymentStatus, 30000);
        });

        function generateQRCode() {
            const walletAddress = '{{ $walletAddress }}';
            const qrcode = document.getElementById('qrcode');


            console.log('Generating QR code for wallet address:', walletAddress);

            new QRCode(qrcode, walletAddress);


        }


        function showQRCodeFallback() {
            const canvas = document.getElementById('qrcode');
            if (canvas) {
                const parent = canvas.parentNode;
                const fallbackDiv = document.createElement('div');
                fallbackDiv.style.cssText = 'width: 192px; height: 192px; background: #f3f4f6; display: flex; align-items: center; justify-content: center; font-size: 12px; color: #6b7280; border: 1px solid #d1d5db; border-radius: 8px;';
                fallbackDiv.innerHTML = 'QR Code unavailable<br><small>Use the address above</small>';
                parent.replaceChild(fallbackDiv, canvas);
            }
        }

        function startTimer() {
            console.log('Starting timer with', timeLeft, 'seconds');

            function updateTimer() {
                const minutes = Math.floor(timeLeft / 60);
                const seconds = timeLeft % 60;

                const timerElement = document.getElementById('timer');
                if (timerElement) {
                    timerElement.textContent =
                        `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                } else {
                    console.error('Timer element not found');
                }

                if (timeLeft <= 0) {
                    clearInterval(timerInterval);
                    showExpiredMessage();
                    return;
                }

                timeLeft--;
            }

            // Clear any existing timer
            if (timerInterval) {
                clearInterval(timerInterval);
            }

            timerInterval = setInterval(updateTimer, 1000);
            updateTimer(); // Initial call
        }

        function copyToClipboard(text, button) {
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(text).then(function() {
                    showCopySuccess(button);
                    if (typeof toastr !== 'undefined') {
                        toastr.success('Address copied to clipboard!');
                    }
                }).catch(function(err) {
                    console.error('Failed to copy text: ', err);
                    fallbackCopyTextToClipboard(text, button);
                });
            } else {
                fallbackCopyTextToClipboard(text, button);
            }
        }

        function fallbackCopyTextToClipboard(text, button) {
            const textArea = document.createElement("textarea");
            textArea.value = text;

            // Avoid scrolling to bottom
            textArea.style.top = "0";
            textArea.style.left = "0";
            textArea.style.position = "fixed";

            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();

            try {
                const successful = document.execCommand('copy');
                if (successful) {
                    showCopySuccess(button);
                    if (typeof toastr !== 'undefined') {
                        toastr.success('Address copied to clipboard!');
                    }
                } else {
                    throw new Error('Copy command was unsuccessful');
                }
            } catch (err) {
                console.error('Fallback: Oops, unable to copy', err);
                if (typeof toastr !== 'undefined') {
                    toastr.error('Failed to copy address');
                }
            }

            document.body.removeChild(textArea);
        }

        function showCopySuccess(button) {
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-check mr-2"></i>Copied!';
            button.classList.add('copied');

            setTimeout(function() {
                button.innerHTML = originalText;
                button.classList.remove('copied');
            }, 2000);
        }

        function checkPaymentStatus() {
            console.log('Checking payment status');

            // Show loading state if called from button
            let button = null;
            if (event && event.target) {
                button = event.target;
                const originalText = button.innerHTML;
                button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Checking...';
                button.disabled = true;
            }

            // Make API call to check payment status
            fetch(`/payment/checkout/status/{{ $transaction->id }}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(response => {
                console.log('Payment status response:', response);

                if (response.success && response.data) {
                    const data = response.data;

                    if (data.status === 'completed') {
                        if (typeof toastr !== 'undefined') {
                            toastr.success('Payment confirmed! Redirecting...');
                        }
                        setTimeout(() => {
                            redirectWithNavigation(`/payment/success?transaction_id=${data.transaction_id}`);
                        }, 2000);
                    } else if (data.status === 'failed') {
                        if (typeof toastr !== 'undefined') {
                            toastr.error('Payment failed');
                        }
                        setTimeout(() => {
                            redirectWithNavigation(`/payment/failed?transaction_id=${data.transaction_id}`);
                        }, 2000);
                    } else {
                        if (typeof toastr !== 'undefined') {
                            toastr.info('Payment is still pending. Please wait...');
                        }
                    }
                } else {
                    throw new Error('Invalid response format');
                }
            })
            .catch(error => {
                console.error('Error checking payment status:', error);
                if (typeof toastr !== 'undefined') {
                    toastr.error('Failed to check payment status');
                }
            })
            .finally(() => {
                // Restore button state if it was a button click
                if (button) {
                    button.innerHTML = '<i class="fas fa-sync-alt mr-2"></i>Check Payment Status';
                    button.disabled = false;
                }
            });
        }

        function showExpiredMessage() {
            console.log('Payment session expired');

            if (typeof toastr !== 'undefined') {
                toastr.warning('Payment session has expired. Redirecting...');
            }

            const statusElement = document.querySelector('.status-pending');
            if (statusElement) {
                statusElement.innerHTML = '<i class="fas fa-exclamation-triangle mr-1"></i>Expired';
                statusElement.classList.remove('status-pending');
                statusElement.classList.add('bg-red-100', 'text-red-800');
            }

            // Redirect to failed page with timeout error
            setTimeout(() => {
                redirectWithNavigation(`/payment/failed?transaction_id={{ $transaction->transaction_id }}&error=timeout`);
            }, 3000);
        }

        // Prevent page refresh and navigation
        let allowNavigation = false;

        // Show confirmation dialog when user tries to leave/refresh the page
        window.addEventListener('beforeunload', function(e) {
            if (!allowNavigation) {
                const confirmationMessage = 'Warning: Refreshing or leaving this page may cause payment issues. Are you sure you want to continue?';
                e.returnValue = confirmationMessage;
                return confirmationMessage;
            }
        });

        // Prevent common refresh keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Prevent F5
            if (e.key === 'F5') {
                e.preventDefault();
                showRefreshWarning();
                return false;
            }

            // Prevent Ctrl+R
            if (e.ctrlKey && e.key === 'r') {
                e.preventDefault();
                showRefreshWarning();
                return false;
            }

            // Prevent Ctrl+F5
            if (e.ctrlKey && e.key === 'F5') {
                e.preventDefault();
                showRefreshWarning();
                return false;
            }
        });

        // Show warning when user tries to refresh
        function showRefreshWarning() {
            if (typeof toastr !== 'undefined') {
                toastr.warning('Page refresh is disabled during payment processing!');
            } else {
                alert('Page refresh is disabled during payment processing!');
            }
        }

        // Allow navigation when payment is completed or expired
        function allowPageNavigation() {
            allowNavigation = true;
        }

        // Override the existing functions to allow navigation on completion/failure
        const originalCheckPaymentStatus = checkPaymentStatus;
        checkPaymentStatus = function() {
            originalCheckPaymentStatus.call(this);
        };

        const originalShowExpiredMessage = showExpiredMessage;
        showExpiredMessage = function() {
            allowPageNavigation(); // Allow navigation when session expires
            originalShowExpiredMessage.call(this);
        };

        // Allow navigation when redirecting to success/failure pages
        function redirectWithNavigation(url) {
            allowPageNavigation();
            window.location.href = url;
        }
    </script>
</body>
</html>
