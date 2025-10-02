@extends('layouts.oppwa')

@section('title', 'Secure Payment - OPPWA')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Header Section -->
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-4">
            <i class="fas fa-credit-card text-2xl text-green-600"></i>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Complete Your Payment</h1>
        <p class="text-gray-600">Secure payment processing powered by OPPWA</p>
    </div>

    <div class="flex flex-row justify-center gap-8">
        <!-- Transaction Details Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-lg p-6 sticky top-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-receipt text-blue-600 mr-2"></i>
                    Order Summary
                </h2>

                <div class="space-y-4">
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-600">Transaction ID</span>
                        <span class="font-mono text-sm text-gray-800">{{ $transaction->transaction_id }}</span>
                    </div>

                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-600">Amount</span>
                        <span class="font-bold text-lg text-gray-900">{{ number_format($transaction->amount, 2) }} {{ $transaction->currency }}</span>
                    </div>

                    @if($transaction->description)
                    <div class="py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-600 block mb-1">Description</span>
                        <p class="text-sm text-gray-800">{{ $transaction->description }}</p>
                    </div>
                    @endif

                    @if($transaction->customer_name || $transaction->customer_email)
                    <div class="py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-600 block mb-1">Customer</span>
                        <p class="text-sm text-gray-800">{{ $transaction->customer_name ?? $transaction->customer_email }}</p>
                    </div>
                    @endif
                </div>

                <!-- Security Badge -->
                <div class="mt-6 p-4 bg-green-50 rounded-lg border border-green-200">
                    <div class="flex items-center">
                        <i class="fas fa-shield-alt text-green-600 mr-2"></i>
                        <div>
                            <p class="text-sm font-semibold text-green-800">Secure Payment</p>
                            <p class="text-xs text-green-600">256-bit SSL encryption</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Form Card -->
        <div class="lg:col-span-2 lg:max-w-[500px]">
            <div class="bg-white rounded-xl shadow-lg p-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-credit-card text-blue-600 mr-2"></i>
                    Payment Information
                </h2>

                <!-- Payment Widget Container -->
                <div id="payment-widget" class="min-h-[500px]">
                    <div class="flex items-center justify-center h-64">
                        <div class="text-center">
                            <div class="animate-spin rounded-full h-12 w-12 border-4 border-blue-200 border-t-blue-600 mx-auto mb-4"></div>
                            <p class="text-gray-600 font-medium">Loading secure payment form...</p>
                        </div>
                    </div>
                </div>

                <!-- Payment Instructions -->
                <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h3 class="text-sm font-semibold text-blue-800 mb-3 flex items-center">
                        <i class="fas fa-info-circle mr-2"></i>
                        Payment Instructions
                    </h3>
                    <ul class="text-sm text-blue-700 space-y-2">
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-blue-500 mr-2 mt-0.5 text-xs"></i>
                            Enter your card details in the secure form above
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-blue-500 mr-2 mt-0.5 text-xs"></i>
                            Supported cards: Visa, Mastercard, American Express
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-blue-500 mr-2 mt-0.5 text-xs"></i>
                            Your payment is processed securely by OPPWA
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-blue-500 mr-2 mt-0.5 text-xs"></i>
                            You will be redirected after payment completion
                        </li>
                    </ul>
                </div>

                <!-- Test Cards Info (for development) -->
                @if(config('app.debug'))
                <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <h3 class="text-sm font-semibold text-yellow-800 mb-3 flex items-center">
                        <i class="fas fa-flask mr-2"></i>
                        Test Cards (Development Only)
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm text-yellow-700">
                        <div>
                            <p><strong>Visa:</strong> 4200000000000000</p>
                            <p><strong>Mastercard:</strong> 5555555555554444</p>
                        </div>
                        <div>
                            <p><strong>American Express:</strong> 378282246310005</p>
                            <p><strong>Expiry:</strong> Any future date</p>
                            <p><strong>CVV:</strong> Any 3 digits</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Status Display -->
    <div id="payment-status" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50" style="display: none;">
        <div class="bg-white rounded-xl p-8 max-w-sm mx-4 text-center">
            <div class="animate-spin rounded-full h-12 w-12 border-4 border-blue-200 border-t-blue-600 mx-auto mb-4"></div>
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Processing Payment</h3>
            <p class="text-gray-600">Please wait while we process your payment...</p>
        </div>
    </div>
</div>

<!-- OPPWA Widget Customization -->
<script>
// Configure OPPWA widget options before loading
var wpwlOptions = {
    style: "card",                    // Use card style for professional appearance
    locale: "en",                     // Set language to English
    iframeStyles: {
        'card-number-placeholder': {
            'color': '#6B7280',       // Gray placeholder text
            'font-size': '16px',
            'font-family': 'Inter, system-ui, sans-serif'
        },
        'cvv-placeholder': {
            'color': '#6B7280',
            'font-size': '16px',
            'font-family': 'Inter, system-ui, sans-serif'
        },
        'card-holder-placeholder': {
            'color': '#6B7280',
            'font-size': '16px',
            'font-family': 'Inter, system-ui, sans-serif'
        },
        'expiry-placeholder': {
            'color': '#6B7280',
            'font-size': '16px',
            'font-family': 'Inter, system-ui, sans-serif'
        }
    }
};
</script>

<!-- Custom CSS for OPPWA Widget Styling -->
<style>
/* OPPWA Widget Custom Styling */
.wpwl-container {
    background: transparent !important;
    border: none !important;
    border-radius: 12px !important;
    padding: 0 !important;
    margin: 0 !important;
}

.wpwl-form {
    border: none !important;
    padding: 0 !important;
    margin: 0 !important;
    box-shadow: none;
    background-color: white;
}

.wpwl-group {
    margin-bottom: 24px !important;
    position: relative !important;
}

.wpwl-label {
    display: block !important;
    font-size: 14px !important;
    font-weight: 600 !important;
    color: #374151 !important;
    margin-bottom: 8px !important;
    font-family: 'Inter', system-ui, sans-serif !important;
}

.wpwl-wrapper {
    position: relative !important;
    width: 100% !important;
}

.wpwl-control {
    width: 100% !important;
    height: 48px !important;
    padding: 12px 16px !important;
    border: 2px solid #E5E7EB !important;
    border-radius: 8px !important;
    font-size: 16px !important;
    font-family: 'Inter', system-ui, sans-serif !important;
    color: #111827 !important;
    background-color: #FFFFFF !important;
    transition: all 0.2s ease-in-out !important;
    box-sizing: border-box !important;
}

.wpwl-control:focus {
    outline: none !important;
    border-color: #3B82F6 !important;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
}

.wpwl-control:hover {
    border-color: #9CA3AF !important;
}

.wpwl-button {
    width: 100% !important;
    height: 52px !important;
    background: linear-gradient(135deg, #3B82F6 0%, #1D4ED8 100%) !important;
    border: none !important;
    border-radius: 8px !important;
    color: #FFFFFF !important;
    font-size: 16px !important;
    font-weight: 600 !important;
    font-family: 'Inter', system-ui, sans-serif !important;
    cursor: pointer !important;
    transition: all 0.2s ease-in-out !important;
    text-transform: none !important;
    letter-spacing: 0 !important;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
}

.wpwl-button:hover {
    background: linear-gradient(135deg, #2563EB 0%, #1E40AF 100%) !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
}

.wpwl-button:active {
    transform: translateY(0) !important;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
}

.wpwl-brand {
    position: absolute !important;
    right: 12px !important;
    top: 50% !important;
    transform: translateY(-50%) !important;
    height: 24px !important;
    width: auto !important;
}

.wpwl-has-error .wpwl-control {
    border-color: #EF4444 !important;
    background-color: #FEF2F2 !important;
}

.wpwl-hint {
    font-size: 12px !important;
    color: #EF4444 !important;
    margin-top: 4px !important;
    font-family: 'Inter', system-ui, sans-serif !important;
}

/* Card number group specific styling */
.wpwl-group-cardNumber .wpwl-control {
    padding-right: 60px !important;
}

/* CVV group specific styling */
.wpwl-group-cvv .wpwl-control {
    padding-right: 40px !important;
}

/* Brand selection styling */
.wpwl-group-brand .wpwl-control {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e") !important;
    background-position: right 12px center !important;
    background-repeat: no-repeat !important;
    background-size: 16px !important;
    padding-right: 40px !important;
    appearance: none !important;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .wpwl-control {
        font-size: 16px !important; /* Prevent zoom on iOS */
    }

    .wpwl-group {
        margin-bottom: 20px !important;
    }
}

/* Loading state */
.wpwl-container.loading {
    opacity: 0.6 !important;
    pointer-events: none !important;
}

/* Success state */
.wpwl-container.success .wpwl-control {
    border-color: #10B981 !important;
    background-color: #F0FDF4 !important;
}

/* Animation for form appearance */
@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.wpwl-container {
    animation: slideInUp 0.3s ease-out !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkoutId = '{{ $transaction->oppwa_checkout_id }}';
    const integrity = '{{ $transaction->oppwa_response["integrity"]["value"] ?? "" }}';

    if (!checkoutId) {
        document.getElementById('payment-widget').innerHTML =
            '<div class="text-center p-8"><div class="text-red-600 mb-4"><i class="fas fa-exclamation-triangle text-4xl"></i></div><h3 class="text-lg font-semibold text-gray-800 mb-2">Payment Error</h3><p class="text-gray-600">Checkout session not found. Please try again.</p></div>';
        return;
    }

    // Load OPPWA payment widget
    const script = document.createElement('script');
    script.src = `https://eu-prod.oppwa.com/v1/paymentWidgets.js?checkoutId=${encodeURIComponent(checkoutId)}`;

    if (integrity) {
        script.setAttribute('integrity', integrity);
        script.setAttribute('crossorigin', 'anonymous');
    }

    script.onload = function() {
        // Clear loading state
        document.getElementById('payment-widget').innerHTML = '';

        // Create payment form with enhanced styling
        const form = document.createElement('form');
        form.action = '{{ route("oppwa.result", ["transactionId" => $transaction->transaction_id]) }}';
        form.className = 'paymentWidgets';
        form.setAttribute('data-brands', 'VISA MASTER AMEX');
        form.setAttribute('data-style', 'card');

        // Add form to container
        document.getElementById('payment-widget').appendChild(form);

        // Add loading class during processing
        const container = document.querySelector('.wpwl-container');
        if (container) {
            container.classList.add('loading');
        }

        // Handle form submission with enhanced UX
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            // Show processing modal
            const statusModal = document.getElementById('payment-status');
            statusModal.classList.remove('hidden');
            statusModal.style.display = 'flex';
            statusModal.classList.add('items-center', 'justify-center');

            // Disable form interactions
            const inputs = form.querySelectorAll('input, select, button');
            inputs.forEach(input => {
                input.disabled = true;
            });

            // Add loading state to container
            if (container) {
                container.classList.add('loading');
            }
        });

        // Add success/error handling
        form.addEventListener('wpwlResponseReady', function(event) {
            if (container) {
                container.classList.remove('loading');
            }
        });

        // Add form validation feedback
        form.addEventListener('wpwlValidationError', function(event) {
            if (container) {
                container.classList.remove('loading');
            }
        });
    };

    script.onerror = function() {
        document.getElementById('payment-widget').innerHTML =
            '<div class="text-center p-8"><div class="text-red-600 mb-4"><i class="fas fa-exclamation-triangle text-4xl"></i></div><h3 class="text-lg font-semibold text-gray-800 mb-2">Loading Error</h3><p class="text-gray-600">Failed to load payment form. Please refresh the page and try again.</p><button onclick="window.location.reload()" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">Refresh Page</button></div>';
    };

    document.head.appendChild(script);
});
</script>
@endsection
