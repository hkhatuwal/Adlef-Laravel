<!-- Payment Alert for Out Transfers -->
@if($transfer->transfer_type === 'out')
    @php
        $latestPayment = $transfer->latestTransferOutPayment;
    @endphp

    @if($latestPayment)
        @if($latestPayment->isConfirmed())
            <!-- Success Alert -->
            <div
                class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-700/50 rounded-lg p-4 mb-6">
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
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-400">
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

                        <button type="submit"
                                id="retry-payment-btn"
                                data-url="{{ route('admin.transfers.request-send-otp', $transfer) }}"
                                class="inline-flex items-center px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded-md transition-colors duration-200">
                            <i class="material-symbols-outlined text-sm mr-1">refresh</i>
                            Retry Payment
                        </button>
                    </div>
                </div>
            </div>
        @elseif($latestPayment->isSent())
            <!-- Sent Payment Alert -->
            <div
                class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700/50 rounded-lg p-4 mb-6">
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
                                Sent: {{ $latestPayment->sent_at->format('M d, Y H:i') }} •
                                Ref: {{ $latestPayment->payment_reference }}
                            </p>
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        <form action="{{ route('admin.transfers.confirm-payment', $transfer) }}" method="POST"
                              class="inline">
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
            <div
                class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700/50 rounded-lg p-4 mb-6">
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
        <div
            class="bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-200 dark:border-indigo-700/50 rounded-lg p-4 mb-6">
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

@push('modals')
    <!-- OTP Modal for Send Payment -->
    <div id="otpSendModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-xl max-w-md w-full">
                <!-- Modal Header -->
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-semibold text-slate-900 dark:text-white">
                                OTP Verification Required
                            </h3>
                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                Verify your identity to send payment
                            </p>
                        </div>
                        <button type="button"
                                id="close-otp-send-modal"
                                class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                            <i class="material-symbols-outlined text-2xl">close</i>
                        </button>
                    </div>
                </div>

                <div class="p-6">
                    <!-- OTP Request Section -->
                    <div id="otp-send-request-section">
                        <div class="text-center mb-6">
                            <div
                                class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-100 to-indigo-100 dark:bg-gradient-to-br dark:from-blue-900/50 dark:to-indigo-900/50 flex items-center justify-center mx-auto mb-4">
                                <i class="material-symbols-outlined text-3xl text-blue-600 dark:text-blue-400">send</i>
                            </div>
                            <h4 class="text-lg font-medium text-slate-900 dark:text-white mb-2">Payment
                                Authorization</h4>
                            <p class="text-slate-600 dark:text-slate-400 text-sm">
                                An OTP will be sent to your registered email address to authorize this payment
                                transaction.
                            </p>
                        </div>

                        <div
                            class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/50 rounded-lg p-4 mb-6">
                            <div class="flex gap-3">
                                <i class="material-symbols-outlined text-red-500">warning</i>
                                <div class="text-sm text-red-800 dark:text-red-300">
                                    <strong>Critical Action:</strong> This will initiate payment processing for
                                    <strong>{{ number_format($transfer->amount, 8) }} {{ $transfer->currency->symbol }}</strong>
                                </div>
                            </div>
                        </div>

                        <button type="button"
                                id="request-otp-send"
                                data-url="{{ route('admin.transfers.request-send-otp', $transfer) }}"
                                class="w-full inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-sm font-medium rounded-lg transition-all duration-200">
                            <i class="material-symbols-outlined text-[20px] mr-2">send</i>
                            Send OTP to Email
                        </button>
                    </div>

                    <!-- OTP Verification Section -->
                    <div id="otp-send-verify-section" class="hidden">
                        <div class="text-center mb-6">
                            <div
                                class="w-16 h-16 rounded-full bg-gradient-to-br from-green-100 to-emerald-100 dark:bg-gradient-to-br dark:from-green-900/50 dark:to-emerald-900/50 flex items-center justify-center mx-auto mb-4">
                                <i class="material-symbols-outlined text-3xl text-green-600 dark:text-green-400">verified_user</i>
                            </div>
                            <h4 class="text-lg font-medium text-slate-900 dark:text-white mb-2">Enter OTP Code</h4>
                            <p class="text-slate-600 dark:text-slate-400 text-sm">
                                Please enter the 6-digit OTP code sent to your email address.
                            </p>
                        </div>

                        <form id="otp-send-form"
                              action="{{ route('admin.transfers.send-payment-with-otp', $transfer) }}" method="POST">
                            @csrf
                            <div class="mb-6">
                                <label for="otp_code_send"
                                       class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">OTP
                                    Code</label>
                                <input type="text"
                                       id="otp_code_send"
                                       name="otp_code"
                                       maxlength="6"
                                       placeholder="Enter 6-digit OTP"
                                       class="block w-full rounded-lg border-slate-200 py-3 text-center text-2xl font-mono tracking-widest bg-white dark:bg-slate-700 dark:border-slate-600 focus:border-indigo-500 focus:ring-indigo-500 dark:text-white">
                            </div>

                            <div class="flex gap-3">
                                <button type="button"
                                        id="resend-otp-send"
                                        class="px-4 py-2 text-sm font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors">
                                    Resend OTP
                                </button>
                                <button type="submit"
                                        class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 inline-flex items-center justify-center gap-2">
                                    <i class="material-symbols-outlined text-[20px]">send</i>
                                    Send Payment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endpush
