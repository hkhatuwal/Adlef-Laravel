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
        const networkFee = parseFloat($networkFeeInput.val()) || 0;
        const transactionCost = parseFloat($transactionCostAmountOtc.val()) || 0;
        const currencySymbol = $networkFeeInput.data('currency');

        if (isNaN(networkFee) || isNaN(transactionCost)) return;

        const finalAmount = amount + networkFee + transactionCost;

        // Update display with proper formatting
        $displayNetworkFee.html(
            `${formatNumber(networkFee, 8)} <span class="text-sm font-medium text-slate-500">${currencySymbol}</span>`
        );
        $displayTransactionCost.html(
            `${formatNumber(transactionCost, 8)} <span class="text-sm font-medium text-slate-500">${currencySymbol}</span>`
        );
        $displayFinalAmount.html(
            `${formatNumber(finalAmount, 8)} <span class="text-base font-medium ml-1">${currencySymbol}</span>`
        );
    }

    function formatNumber(number, decimals) {
        return number.toLocaleString('en-US', {
            minimumFractionDigits: decimals,
            maximumFractionDigits: decimals
        });
    }
});

