<!-- Payment Alert for Out Transfers -->
@if($transfer->transfer_type === 'out')
    @php
        $latestPayment = $transfer->latestTransferOutPayment;
    @endphp

    @if($latestPayment)
        @if($latestPayment->isConfirmed())
            <!-- Success Alert -->
            <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-700/50 rounded-lg p-4 mb-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="material-symbols-outlined text-emerald-600 dark:text-emerald-400 text-xl">check_circle</i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-emerald-800 dark:text-emerald-300">
                                Payment Successfully Completed
                            </p>
                            <p class="text-xs text-emerald-600 dark:text-emerald-400 mt-1">
                                Transaction ID: {{ $latestPayment->transaction_id }}
                                @if($latestPayment->confirmed_at)
                                    • Confirmed: {{ $latestPayment->confirmed_at->format('M d, Y H:i') }}
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-400">
                            Confirmed
                        </span>
                    </div>
                </div>
            </div>
        @elseif($latestPayment->isFailed())
            <!-- Failure Alert -->
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700/50 rounded-lg p-4 mb-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="material-symbols-outlined text-red-600 dark:text-red-400 text-xl">error</i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800 dark:text-red-300">
                                Payment Failed - Action Required
                            </p>
                            <p class="text-xs text-red-600 dark:text-red-400 mt-1">
                                {{ $latestPayment->failure_reason ?: 'Payment processing failed. Please retry.' }}
                            </p>
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        <form action="{{ route('admin.transfers.retry-payment', $transfer) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit"
                                    class="inline-flex items-center px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded-md transition-colors duration-200">
                                <i class="material-symbols-outlined text-sm mr-1">refresh</i>
                                Retry Payment
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @elseif($latestPayment->isSent())
            <!-- Sent Payment Alert -->
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700/50 rounded-lg p-4 mb-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-xl">send</i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-blue-800 dark:text-blue-300">
                                Payment Sent - Awaiting Confirmation
                            </p>
                            <p class="text-xs text-blue-600 dark:text-blue-400 mt-1">
                                Sent: {{ $latestPayment->sent_at->format('M d, Y H:i') }} • Ref: {{ $latestPayment->payment_reference }}
                            </p>
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        <form action="{{ route('admin.transfers.confirm-payment', $transfer) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit"
                                    class="inline-flex items-center px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-medium rounded-md transition-colors duration-200">
                                <i class="material-symbols-outlined text-sm mr-1">check_circle</i>
                                Confirm Payment
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @else
            <!-- Pending Payment Alert -->
            <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700/50 rounded-lg p-4 mb-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="material-symbols-outlined text-amber-600 dark:text-amber-400 text-xl">pending</i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-amber-800 dark:text-amber-300">
                                Payment Ready to Send
                            </p>
                            <p class="text-xs text-amber-600 dark:text-amber-400 mt-1">
                                Click "Send Payment" to process this transfer
                            </p>
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        <button type="button"
                                id="send-payment-btn"
                                class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-md transition-colors duration-200">
                            <i class="material-symbols-outlined text-sm mr-1">send</i>
                            Send Payment
                        </button>
                    </div>
                </div>
            </div>
        @endif
    @else
        <!-- No Payment Record Alert -->
        <div class="bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-200 dark:border-indigo-700/50 rounded-lg p-4 mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="material-symbols-outlined text-indigo-600 dark:text-indigo-400 text-xl">payment</i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-indigo-800 dark:text-indigo-300">
                            Payment Setup Required
                        </p>
                        <p class="text-xs text-indigo-600 dark:text-indigo-400 mt-1">
                            Create a payment record to process this outgoing transfer
                        </p>
                    </div>
                </div>
                <div class="flex-shrink-0">
                    <button type="button"
                            id="create-payment-btn"
                            class="inline-flex items-center px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium rounded-md transition-colors duration-200">
                        <i class="material-symbols-outlined text-sm mr-1">add_circle</i>
                        Process Payment
                    </button>
                </div>
            </div>
        </div>
    @endif
@endif
