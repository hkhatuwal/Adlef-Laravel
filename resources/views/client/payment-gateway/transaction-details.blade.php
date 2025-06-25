@extends('client._layouts.app')

@section('title', 'Transaction Details')

@section('content')
<div class="p-6 space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <a href="{{ route('client.payment-gateway.index') }}" class="text-gray-600 hover:text-gray-900 transition-colors">
                <i class="fa-solid fa-arrow-left text-lg"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Transaction Details</h1>
                <p class="text-gray-600 mt-1">Transaction ID: {{ $transaction->transaction_id }}</p>
            </div>
        </div>
        <div class="flex space-x-3">
            <button class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all" onclick="copyToClipboard('{{ $transaction->transaction_id }}')">
                <i class="fa-solid fa-copy mr-2"></i>
                Copy ID
            </button>
            <button class="px-4 py-2 bg-black text-white rounded-lg hover:bg-gray-800 transition-all">
                <i class="fa-solid fa-download mr-2"></i>
                Download Receipt
            </button>
        </div>
    </div>

    <!-- Status Banner -->
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                @php
                    $statusConfig = [
                        'completed' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'icon' => 'fa-check-circle', 'color' => 'text-green-600'],
                        'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'icon' => 'fa-clock', 'color' => 'text-yellow-600'],
                        'processing' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'icon' => 'fa-spinner', 'color' => 'text-blue-600'],
                        'failed' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'icon' => 'fa-exclamation-circle', 'color' => 'text-red-600'],
                        'refunded' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'icon' => 'fa-undo', 'color' => 'text-gray-600'],
                    ];
                    $config = $statusConfig[$transaction->status] ?? $statusConfig['pending'];
                @endphp
                
                <div class="w-12 h-12 {{ $config['bg'] }} rounded-full flex items-center justify-center">
                    <i class="fa-solid {{ $config['icon'] }} {{ $config['color'] }} text-xl"></i>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Payment {{ ucfirst($transaction->status) }}</h3>
                    <p class="text-gray-600">{{ $transaction->description }}</p>
                </div>
            </div>
            
            <div class="text-right">
                <p class="text-2xl font-bold text-gray-900">{{ $transaction->currency }} {{ number_format($transaction->amount, 2) }}</p>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $config['bg'] }} {{ $config['text'] }}">
                    {{ ucfirst($transaction->status) }}
                </span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Transaction Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Details -->
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Transaction Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Transaction ID</label>
                        <div class="flex items-center space-x-2">
                            <p class="text-sm text-gray-900 font-mono">{{ $transaction->transaction_id }}</p>
                            <button class="text-gray-400 hover:text-gray-600" onclick="copyToClipboard('{{ $transaction->transaction_id }}')">
                                <i class="fa-solid fa-copy text-xs"></i>
                            </button>
                        </div>
                    </div>
                    
                    @if($transaction->gateway_transaction_id)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Gateway Transaction ID</label>
                        <div class="flex items-center space-x-2">
                            <p class="text-sm text-gray-900 font-mono">{{ $transaction->gateway_transaction_id }}</p>
                            <button class="text-gray-400 hover:text-gray-600" onclick="copyToClipboard('{{ $transaction->gateway_transaction_id }}')">
                                <i class="fa-solid fa-copy text-xs"></i>
                            </button>
                        </div>
                    </div>
                    @endif
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Amount</label>
                        <p class="text-sm text-gray-900">{{ $transaction->currency }} {{ number_format($transaction->amount, 2) }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Gateway</label>
                        <div class="flex items-center space-x-2">
                            <div class="w-5 h-5 bg-gray-200 rounded-full flex items-center justify-center">
                                <i class="fa-solid fa-credit-card text-xs text-gray-600"></i>
                            </div>
                            <p class="text-sm text-gray-900">{{ ucfirst($transaction->gateway_name) }}</p>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Created</label>
                        <p class="text-sm text-gray-900">{{ $transaction->created_at->format('M j, Y g:i A') }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Updated</label>
                        <p class="text-sm text-gray-900">{{ $transaction->updated_at->format('M j, Y g:i A') }}</p>
                    </div>
                </div>
            </div>

            <!-- Customer Information -->
            @if($transaction->customer_email || $transaction->customer_name || $transaction->customer_phone)
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Customer Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if($transaction->customer_name)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <p class="text-sm text-gray-900">{{ $transaction->customer_name }}</p>
                    </div>
                    @endif
                    
                    @if($transaction->customer_email)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <p class="text-sm text-gray-900">{{ $transaction->customer_email }}</p>
                    </div>
                    @endif
                    
                    @if($transaction->customer_phone)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                        <p class="text-sm text-gray-900">{{ $transaction->customer_phone }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Gateway Response -->
            @if($transaction->gateway_response)
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Gateway Response</h3>
                <div class="bg-gray-50 rounded-lg p-4">
                    <pre class="text-sm text-gray-700 whitespace-pre-wrap">{{ json_encode($transaction->gateway_response, JSON_PRETTY_PRINT) }}</pre>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    <button class="w-full flex items-center justify-center px-4 py-2 bg-black text-white rounded-lg hover:bg-gray-800 transition-all">
                        <i class="fa-solid fa-download mr-2"></i>
                        Download Receipt
                    </button>
                    
                    <button class="w-full flex items-center justify-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all" onclick="copyToClipboard('{{ $transaction->transaction_id }}')">
                        <i class="fa-solid fa-copy mr-2"></i>
                        Copy Transaction ID
                    </button>
                    
                    @if($transaction->status === 'completed' && $transaction->gateway_name === 'payop')
                    <button class="w-full flex items-center justify-center px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-all">
                        <i class="fa-solid fa-undo mr-2"></i>
                        Request Refund
                    </button>
                    @endif
                </div>
            </div>

            <!-- Transaction Timeline -->
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Timeline</h3>
                <div class="space-y-4">
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fa-solid fa-plus text-blue-600 text-xs"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">Transaction Created</p>
                            <p class="text-xs text-gray-500">{{ $transaction->created_at->format('M j, Y g:i A') }}</p>
                        </div>
                    </div>
                    
                    @if($transaction->gateway_transaction_id)
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                            <i class="fa-solid fa-credit-card text-yellow-600 text-xs"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">Payment Processing</p>
                            <p class="text-xs text-gray-500">Sent to {{ ucfirst($transaction->gateway_name) }}</p>
                        </div>
                    </div>
                    @endif
                    
                    @if($transaction->status === 'completed')
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fa-solid fa-check text-green-600 text-xs"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">Payment Completed</p>
                            <p class="text-xs text-gray-500">{{ $transaction->updated_at->format('M j, Y g:i A') }}</p>
                        </div>
                    </div>
                    @elseif($transaction->status === 'failed')
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                            <i class="fa-solid fa-times text-red-600 text-xs"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">Payment Failed</p>
                            <p class="text-xs text-gray-500">{{ $transaction->updated_at->format('M j, Y g:i A') }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Support -->
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Need Help?</h3>
                <p class="text-sm text-gray-600 mb-4">Having issues with this transaction? Our support team is here to help.</p>
                <button class="w-full flex items-center justify-center px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-all">
                    <i class="fa-solid fa-life-ring mr-2"></i>
                    Contact Support
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Copy to clipboard function
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Show toast or notification
        const toast = document.createElement('div');
        toast.className = 'fixed top-4 right-4 bg-black text-white px-4 py-2 rounded-lg text-sm z-50';
        toast.textContent = 'Copied to clipboard!';
        document.body.appendChild(toast);
        
        setTimeout(() => {
            document.body.removeChild(toast);
        }, 2000);
    });
}
</script>
@endpush
@endsection 