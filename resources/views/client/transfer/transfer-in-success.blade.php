@extends('client.layouts.app')
@section('content')
<div class="min-h-screen py-8">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto">
            <!-- Success Message -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 mb-6">
                <div class="text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Transfer Request Successful!</h2>
                    <p class="text-gray-600">Your transfer request has been submitted successfully.</p>
                </div>
            </div>

            <!-- Transfer Details -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Transfer Instructions</h3>

                <!-- Amount Details -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-600">Amount to Transfer:</span>
                        <span class="text-lg font-semibold text-gray-900">{{$transfer->currency->symbol}} {{ number_format($transfer->amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Currency:</span>
                        <span class="text-gray-900">{{ $transfer->currency->name }}</span>
                    </div>
                </div>

                @if($transfer->currency->isUSD())
                <!-- Bank Account Details for USD -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bank Details</label>
                        <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Bank Name:</span>
                                <div class="flex items-center">
                                    <span class="text-gray-900" id="bank-name">Chase Bank</span>
                                    <button onclick="copyToClipboard('Chase Bank', 'Bank name')"
                                        class="ml-2 text-blue-600 hover:text-blue-700">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Account Number:</span>
                                <div class="flex items-center">
                                    <span class="text-gray-900" id="account-number">1234567890</span>
                                    <button onclick="copyToClipboard('1234567890', 'Account number')"
                                        class="ml-2 text-blue-600 hover:text-blue-700">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Routing Number:</span>
                                <div class="flex items-center">
                                    <span class="text-gray-900" id="routing-number">021000021</span>
                                    <button onclick="copyToClipboard('021000021', 'Routing number')"
                                        class="ml-2 text-blue-600 hover:text-blue-700">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Reference Number:</span>
                                <div class="flex items-center">
                                    <span class="text-gray-900" id="reference-number">{{ $transfer->reference_number }}</span>
                                    <button onclick="copyToClipboard('{{ $transfer->reference_number }}', 'Reference number')"
                                        class="ml-2 text-blue-600 hover:text-blue-700">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <!-- Wallet Address for other currencies -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Wallet Address</label>
                        <div class="flex items-center">
                            <input type="text" value="0xffsdfdsf23232sdfe2d23" readonly
                                class="flex-1 block w-full px-4 py-3 text-gray-700 bg-gray-100 border border-gray-200 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <button onclick="copyToClipboard('0xffsdfdsf23232sdfe2d23', 'Wallet address')"
                                class="ml-2 inline-flex items-center px-4 py-3 border border-transparent text-sm font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path>
                                </svg>
                                Copy
                            </button>
                        </div>
                    </div>
                </div>
                @endif

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mt-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">
                                Please transfer <span class="font-semibold">{{$transfer->currency->symbol}} {{ number_format($transfer->amount, 2) }}</span>
                                @if($transfer->currency->isUSD())
                                    to our bank account using the details above. Don't forget to include the reference number in your transfer.
                                @else
                                    to this wallet address.
                                @endif
                                The transfer will be processed once we receive the funds.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-6 flex justify-between">
                    <a href="{{ route('client.transfer') }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Back to Transfers
                    </a>
                    <a href="{{ route('client.transfer.in',["currency_id"=>$transfer->currency_id]) }}"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        New Transfer
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function copyToClipboard(text, label) {
    navigator.clipboard.writeText(text).then(() => {
        // Show a toast notification
        toastr.success(label + ' copied to clipboard!');
    }).catch(err => {
        toastr.error('Failed to copy ' + label.toLowerCase());
        console.error('Failed to copy: ', err);
    });
}
</script>
@endpush
@endsection
