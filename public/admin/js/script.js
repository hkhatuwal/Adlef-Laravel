$(document).ready(function () {
    // Verify Transfer Modal Functionality
    const $verifyModal = $('#verifyModal');
    const $verifyForm = $('#verifyForm');
    const $feePercentage = $('#feePercentage');
    const $feeValue = $('#feeValue');
    const $feeAmount = $('#feeAmount');
    const $finalAmount = $('#finalAmount');

    // Network Fee Modal Functionality
    const $networkFeeModal = $('#networkFeeModal');
    const $processFeeForm = $('#processFeeForm');
    const $networkFeeInput = $('#networkFeeAmount');
    const $networkFeePercentage = $('#networkFeePercentage');
    const $displayNetworkFee = $('#displayNetworkFee');
    const $displayFinalAmount = $('#displayFinalAmount');

    // Button Event Handlers for Verify Transfer
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

    // Form Validation
    $verifyForm.on('submit', function (e) {
        const fee = parseFloat($feePercentage.val());
        const feeAmount = parseFloat($feeValue.val());
        const totalAmount = parseFloat($feePercentage.data('amount'));
        
        if (isNaN(fee) || fee < 0 || fee > 100 || isNaN(feeAmount) || feeAmount < 0 || feeAmount > totalAmount) {
            e.preventDefault();
            alert('Please enter valid fee values. Percentage should be between 0-100% and amount should not exceed the transfer amount.');
        }
    });

    $processFeeForm.on('submit', function (e) {
        const fee = parseFloat($networkFeeInput.val());
        const amount = parseFloat($networkFeeInput.data('amount'));
        const percentage = parseFloat($networkFeePercentage.val());
        
        if (isNaN(fee) || fee < 0 || fee > amount || isNaN(percentage) || percentage < 0 || percentage > 100) {
            e.preventDefault();
            alert('Please enter valid fee values. Percentage should be between 0-100% and amount should not exceed the trade amount.');
        }
    });

    // Modal Functions
    function openVerifyModal() {
        $verifyModal.removeClass('hidden');
        updateCalculations();
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
    window.setFeePreset = function(percentage) {
        const totalAmount = parseFloat($feePercentage.data('amount'));
        const feeAmount = (percentage * totalAmount) / 100;
        
        $feePercentage.val(percentage.toFixed(2));
        $feeValue.val(feeAmount.toFixed(8));
        updateCalculations();
    }

    // Network Fee Preset Function
    window.setNetworkFeePreset = function(percentage) {
        const totalAmount = parseFloat($networkFeeInput.data('amount'));
        const amount = (percentage * totalAmount) / 100;
        
        $networkFeePercentage.val(percentage.toFixed(2));
        $networkFeeInput.val(amount.toFixed(8));
        updateNetworkFeeCalculations();
    }

    function updateCalculations() {
        const amount = parseFloat($feePercentage.data('amount'));
        const fee = parseFloat($feeValue.val());
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
        const networkFee = parseFloat($networkFeeInput.val());
        const currencySymbol = $networkFeeInput.data('currency');
        
        if (isNaN(networkFee)) return;
        
        const finalAmount = amount + networkFee;
        
        // Update display with proper formatting
        $displayNetworkFee.html(
            `${formatNumber(networkFee, 8)} <span class="text-sm font-medium text-slate-500">${currencySymbol}</span>`
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
