$(document).ready(function () {


    // Verify Transfer Modal Functionality
    const $verifyModal = $('#verifyModal');
    const $holdModal = $('#holdModal');

    const $verifyForm = $('#verifyForm');
    const $feePercentage = $('#feePercentage');
    const $feeValue = $('#feeValue');
    const $feeAmount = $('#feeAmount');
    const $finalAmount = $('#finalAmount');
    const $transactionCostPercentage = $('#transactionCostPercentage');
    const $transactionCostValue = $('#transactionCostValue');

    // Network Fee Modal Functionality
    const $networkFeeModal = $('#networkFeeModal');
    const $processFeeForm = $('#processFeeForm');
    const $networkFeeInput = $('#networkFeeAmount');
    const $networkFeePercentage = $('#networkFeePercentage');
    const $displayNetworkFee = $('#displayNetworkFee');
    const $displayFinalAmount = $('#displayFinalAmount');
    const $transactionCostPercentageOtc = $('#transactionCostPercentage');
    const $transactionCostAmountOtc = $('#transactionCostAmount');
    const $displayTransactionCost = $('#displayTransactionCost');

    // Button Event Handlers for Verify Transfer

    $('#hold_otc').click(function () {
        openHoldModal()
    })

    $('#close-hold-modal, #cancel-hold').click(function () {
        closeHoldModal();
    });
    $('#verify_transfer').click(function () {
        openVerifyModal();
    });

    $('#close-verify-modal, #cancel-verify').click(function () {
        closeVerifyModal();
    });

    // Button Event Handlers for Network Fee
    $('#process_otc').click(function () {
        openNetworkFeeModal();
    });

    $('#close-network-fee-modal, #cancel-network-fee').click(function () {
        closeNetworkFeeModal();
    });

    // Close modals on outside click
    $verifyModal.click(function (e) {
        if (e.target === this) {
            closeVerifyModal();
        }
    });

    $networkFeeModal.click(function (e) {
        if (e.target === this) {
            closeNetworkFeeModal();
        }
    });

    // Fee Input Events for Verify Transfer
    $feePercentage.on('input', function () {
        const percentage = parseFloat($(this).val());
        const amount = parseFloat($(this).data('amount'));
        const feeAmount = (percentage * amount) / 100;

        $feeValue.val(feeAmount.toFixed(8));
        updateCalculations();
    });

    $feeValue.on('input', function () {
        const feeAmount = parseFloat($(this).val());
        const totalAmount = parseFloat($feePercentage.data('amount'));
        const percentage = (feeAmount / totalAmount) * 100;

        $feePercentage.val(percentage.toFixed(2));
        updateCalculations();
    });

    // Transaction Cost Input Events
    $transactionCostPercentage.on('input', function () {
        const percentage = parseFloat($(this).val());
        const amount = parseFloat($(this).data('amount'));
        const costAmount = (percentage * amount) / 100;

        $transactionCostValue.val(costAmount.toFixed(8));
        updateCalculations();
    });

    $transactionCostValue.on('input', function () {
        const costAmount = parseFloat($(this).val());
        const totalAmount = parseFloat($transactionCostPercentage.data('amount'));
        const percentage = (costAmount / totalAmount) * 100;
        $transactionCostPercentage.val(percentage.toFixed(2));
        updateCalculations();
    });

    // Network Fee Input Events
    $networkFeeInput.on('input', function () {
        const amount = parseFloat($(this).val());
        const totalAmount = parseFloat($(this).data('amount'));
        const percentage = (amount / totalAmount) * 100;

        $networkFeePercentage.val(percentage.toFixed(2));
        updateNetworkFeeCalculations();
    });

    $networkFeePercentage.on('input', function () {
        const percentage = parseFloat($(this).val());
        const totalAmount = parseFloat($networkFeeInput.data('amount'));
        const amount = (percentage * totalAmount) / 100;

        $networkFeeInput.val(amount.toFixed(8));
        updateNetworkFeeCalculations();
    });

    // Transaction Cost Input Events for OTC
    $transactionCostPercentageOtc.on('input', function () {
        const percentage = parseFloat($(this).val());
        const totalAmount = parseFloat($networkFeeInput.data('amount'));
        const amount = (percentage * totalAmount) / 100;

        $transactionCostAmountOtc.val(amount.toFixed(8));
        updateNetworkFeeCalculations();
    });

    $transactionCostAmountOtc.on('input', function () {
        const amount = parseFloat($(this).val());
        const totalAmount = parseFloat($networkFeeInput.data('amount'));
        const percentage = (amount / totalAmount) * 100;

        $transactionCostPercentageOtc.val(percentage.toFixed(2));
        updateNetworkFeeCalculations();
    });

    // Form Validation
    $verifyForm.on('submit', function (e) {
        const fee = parseFloat($feePercentage.val());
        const feeAmount = parseFloat($feeValue.val());
        const transactionCost = parseFloat($transactionCostPercentage.val());
        const transactionCostAmount = parseFloat($transactionCostValue.val());
        const totalAmount = parseFloat($feePercentage.data('amount'));

        if (isNaN(fee) || fee < 0 || isNaN(feeAmount) || feeAmount < 0 ||
            isNaN(transactionCost) || transactionCost < 0 || isNaN(transactionCostAmount) || transactionCostAmount < 0) {
            e.preventDefault();
            alert('Please enter valid fee and transaction cost values. Percentages should be between 0-100% and amounts should not exceed the transfer amount.');
        }
    });

    $processFeeForm.on('submit', function (e) {
        const fee = parseFloat($networkFeeInput.val());
        const amount = parseFloat($networkFeeInput.data('amount'));
        const percentage = parseFloat($networkFeePercentage.val());
        const transactionCost = parseFloat($transactionCostAmountOtc.val());
        const transactionCostPercentage = parseFloat($transactionCostPercentageOtc.val());

        if (isNaN(fee) || fee < 0 || isNaN(percentage) || percentage < 0 ||
            isNaN(transactionCost) || transactionCost < 0 ||
            isNaN(transactionCostPercentage) || transactionCostPercentage < 0) {
            e.preventDefault();
            alert('Please enter valid fee and transaction cost values. Percentages should be between 0-100% and amounts should not exceed the trade amount.');
        }
    });

    // Modal Functions
    function openVerifyModal() {
        $verifyModal.removeClass('hidden');
        updateCalculations();
    }

    function openHoldModal() {
        $holdModal.removeClass('hidden');
    }
    function closeHoldModal() {
        $holdModal.addClass('hidden');
    }


    function closeVerifyModal() {
        $verifyModal.addClass('hidden');
    }

    function openNetworkFeeModal() {
        $networkFeeModal.removeClass('hidden');
        updateNetworkFeeCalculations();
    }

    function closeNetworkFeeModal() {
        $networkFeeModal.addClass('hidden');
    }

    // Fee Preset Function for Verify Transfer
    window.setFeePreset = function (percentage) {
        const totalAmount = parseFloat($feePercentage.data('amount'));
        const feeAmount = (percentage * totalAmount) / 100;

        $feePercentage.val(percentage.toFixed(2));
        $feeValue.val(feeAmount.toFixed(8));
        updateCalculations();
    }

    // Network Fee Preset Function
    window.setNetworkFeePreset = function (percentage) {
        const totalAmount = parseFloat($networkFeeInput.data('amount'));
        const amount = (percentage * totalAmount) / 100;

        $networkFeePercentage.val(percentage.toFixed(2));
        $networkFeeInput.val(amount.toFixed(8));
        updateNetworkFeeCalculations();
    }

    function updateCalculations() {
        const amount = parseFloat($feePercentage.data('amount'));
        const fee = parseFloat($feeValue.val()) || 0;
        const currencySymbol = $feePercentage.data('currency');

        if (isNaN(fee)) return;

        const finalAmount = amount + fee;

        // Update display with proper formatting
        $feeAmount.html(
            `${formatNumber(fee, 8)} <span class="text-sm font-medium text-slate-500">${currencySymbol}</span>`
        );
        $finalAmount.html(
            `${formatNumber(finalAmount, 8)} <span class="text-base font-medium ml-1">${currencySymbol}</span>`
        );
    }

    function updateNetworkFeeCalculations() {
        const amount = parseFloat($networkFeeInput.data('amount'));
        const oldFee = parseFloat($networkFeeInput.data('old-fee')) || 0;
        const networkFee = parseFloat($networkFeeInput.val()) || 0;
        const transactionCost = parseFloat($transactionCostAmountOtc.val()) || 0;
        const currencySymbol = $networkFeeInput.data('currency');

        // Get exchange rate and to_amount from data attributes
        const exchangeRate = parseFloat($networkFeeInput.data('exchange-rate')) || 1;
        const toAmount = parseFloat($networkFeeInput.data('to-amount')) || 0;
        const toCurrencySymbol = $networkFeeInput.data('to-currency') || currencySymbol;

        if (isNaN(networkFee) || isNaN(transactionCost)) return;

        // Calculate the amount user will receive: to_amount - (fees converted to target currency)
        const totalFeesInFromCurrency = networkFee + transactionCost;
        const totalFeesInToCurrency = totalFeesInFromCurrency * exchangeRate;
        const finalAmountUserReceives = toAmount+oldFee  - totalFeesInToCurrency;

        // Update display with proper formatting
        $displayNetworkFee.html(
            `${formatNumber(networkFee, 8)} <span class="text-sm font-medium text-slate-500">${currencySymbol}</span>`
        );
        $displayTransactionCost.html(
            `${formatNumber(transactionCost, 8)} <span class="text-sm font-medium text-slate-500">${currencySymbol}</span>`
        );
        $displayFinalAmount.html(
            `${formatNumber(finalAmountUserReceives, 8)} <span class="text-base font-medium ml-1">${toCurrencySymbol}</span>`
        );
    }

    function formatNumber(number, decimals) {
        return number.toLocaleString('en-US', {
            minimumFractionDigits: decimals,
            maximumFractionDigits: decimals
        });
    }

    // OTP Modal Elements
    const $createPaymentBtn = $('#create-payment-btn');
    const $sendPaymentBtn = $('#send-payment-btn');
    const $retryPaymentBtn = $('#retry-payment-btn');
    const $otpCreateModal = $('#otpCreateModal');
    const $otpSendModal = $('#otpSendModal');

    // Create Payment OTP Flow
    $createPaymentBtn.on('click', function() {
        $otpCreateModal.removeClass('hidden');
    });
    $retryPaymentBtn.on('click', function() {
        $otpCreateModal.removeClass('hidden');
    });

    // Send Payment OTP Flow
    $sendPaymentBtn.on('click', function() {
        $otpSendModal.removeClass('hidden');
    });

    // Close Create OTP Modal
    $('#close-otp-create-modal').on('click', function() {
        $otpCreateModal.addClass('hidden');
        resetOtpCreateModal();
    });

    // Close Send OTP Modal
    $('#close-otp-send-modal').on('click', function() {
        $otpSendModal.addClass('hidden');
        resetOtpSendModal();
    });

    // Request OTP for Create Payment
    $('#request-otp-create').on('click', function() {
        const $button = $(this);
        $button.prop('disabled', true);
        $button.html('<i class="material-symbols-outlined text-[20px] mr-2 animate-spin">sync</i>Sending OTP...');

        $.ajax({
            url: $button.data('url'), // You'll need to set data-url attribute in HTML
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                if (data.success) {
                    $('#otp-request-section').addClass('hidden');
                    $('#otp-verify-section').removeClass('hidden');
                } else {
                    alert(data.message || 'Failed to send OTP. Please try again.');
                    $button.prop('disabled', false);
                    $button.html('<i class="material-symbols-outlined text-[20px] mr-2">send</i>Send OTP to Email');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
                $button.prop('disabled', false);
                $button.html('<i class="material-symbols-outlined text-[20px] mr-2">send</i>Send OTP to Email');
            }
        });
    });

    // Request OTP for Send Payment
    $('#request-otp-send').on('click', function() {
        const $button = $(this);
        $button.prop('disabled', true);
        $button.html('<i class="material-symbols-outlined text-[20px] mr-2 animate-spin">sync</i>Sending OTP...');

        $.ajax({
            url: $button.data('url'), // You'll need to set data-url attribute in HTML
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                if (data.success) {
                    $('#otp-send-request-section').addClass('hidden');
                    $('#otp-send-verify-section').removeClass('hidden');
                } else {
                    alert(data.message || 'Failed to send OTP. Please try again.');
                    $button.prop('disabled', false);
                    $button.html('<i class="material-symbols-outlined text-[20px] mr-2">send</i>Send OTP to Email');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
                $button.prop('disabled', false);
                $button.html('<i class="material-symbols-outlined text-[20px] mr-2">send</i>Send OTP to Email');
            }
        });
    });

    // Resend OTP for Create Payment
    $('#resend-otp-create').on('click', function() {
        const $button = $(this);
        $button.prop('disabled', true);
        $button.text('Sending...');

        $.ajax({
            url: $button.data('url'), // You'll need to set data-url attribute in HTML
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                if (data.success) {
                    alert('OTP sent successfully!');
                } else {
                    alert(data.message || 'Failed to send OTP. Please try again.');
                }
                $button.prop('disabled', false);
                $button.text('Resend OTP');
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
                $button.prop('disabled', false);
                $button.text('Resend OTP');
            }
        });
    });

    // Resend OTP for Send Payment
    $('#resend-otp-send').on('click', function() {
        const $button = $(this);
        $button.prop('disabled', true);
        $button.text('Sending...');

        $.ajax({
            url: $button.data('url'), // You'll need to set data-url attribute in HTML
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                if (data.success) {
                    alert('OTP sent successfully!');
                } else {
                    alert(data.message || 'Failed to send OTP. Please try again.');
                }
                $button.prop('disabled', false);
                $button.text('Resend OTP');
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
                $button.prop('disabled', false);
                $button.text('Resend OTP');
            }
        });
    });

    // Auto-focus and format OTP inputs
    const otpInputs = ['#otp_code_create', '#otp_code_send'];
    otpInputs.forEach(function(inputId) {
        $(inputId).on('input', function() {
            $(this).val($(this).val().replace(/\D/g, ''));
        });
    });

    // Reset Create OTP Modal
    function resetOtpCreateModal() {
        $('#otp-request-section').removeClass('hidden');
        $('#otp-verify-section').addClass('hidden');
        $('#otp_code_create').val('');
        $('#request-otp-create').prop('disabled', false);
        $('#request-otp-create').html('<i class="material-symbols-outlined text-[20px] mr-2">send</i>Send OTP to Email');
    }

    // Reset Send OTP Modal
    function resetOtpSendModal() {
        $('#otp-send-request-section').removeClass('hidden');
        $('#otp-send-verify-section').addClass('hidden');
        $('#otp_code_send').val('');
        $('#request-otp-send').prop('disabled', false);
        $('#request-otp-send').html('<i class="material-symbols-outlined text-[20px] mr-2">send</i>Send OTP to Email');
    }

    // Close modals when clicking outside
    $otpCreateModal.on('click', function(e) {
        if (e.target === this) {
            $(this).addClass('hidden');
            resetOtpCreateModal();
        }
    });

    $otpSendModal.on('click', function(e) {
        if (e.target === this) {
            $(this).addClass('hidden');
            resetOtpSendModal();
        }
    });

    console.log("OTP functionality loaded");
});

