document.addEventListener('DOMContentLoaded', function() {
    // Handle provider checkbox changes
    const providerCheckboxes = document.querySelectorAll('input[name^="allowed_payment_providers"]');
    
    providerCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const providerCard = this.closest('.bg-slate-50');
            const limitInputs = providerCard.querySelectorAll('input[name*="provider_limits"]');
            const statusSpan = providerCard.querySelector('span');
            
            if (this.checked) {
                // Show limit inputs
                limitInputs.forEach(input => {
                    input.disabled = false;
                    input.closest('div').style.display = 'block';
                });
                // Update status
                statusSpan.textContent = 'Enabled';
                statusSpan.className = statusSpan.className.replace('bg-slate-100 text-slate-800', 'bg-emerald-100 text-emerald-800').replace('dark:bg-slate-900/50 dark:text-slate-400', 'dark:bg-emerald-900/50 dark:text-emerald-400');
            } else {
                // Hide limit inputs
                limitInputs.forEach(input => {
                    input.disabled = true;
                    input.closest('div').style.display = 'none';
                });
                // Update status
                statusSpan.textContent = 'Disabled';
                statusSpan.className = statusSpan.className.replace('bg-emerald-100 text-emerald-800', 'bg-slate-100 text-slate-800').replace('dark:bg-slate-900/50 dark:text-slate-400', 'dark:bg-slate-900/50 dark:text-slate-400');
            }
        });
        
        // Trigger change event on page load to set initial state
        checkbox.dispatchEvent(new Event('change'));
    });

    // Handle settlement fee type changes
    const feeTypeRadios = document.querySelectorAll('input[name="fee_type"]');
    const percentageSection = document.getElementById('percentage_fee_section');
    const fixedSection = document.getElementById('fixed_fee_section');
    const feePercentageInput = document.getElementById('fee_percentage');
    const feeFixedInput = document.getElementById('fee_fixed');
    const feePreviewAmount = document.getElementById('fee_preview_amount');
    const feePreviewAmount1000 = document.getElementById('fee_preview_amount_1000');

    function toggleFeeSections() {
        const selectedType = document.querySelector('input[name="fee_type"]:checked').value;
        
        if (selectedType === 'percentage') {
            percentageSection.style.display = 'block';
            fixedSection.style.display = 'none';
            feeFixedInput.disabled = true;
            feePercentageInput.disabled = false;
        } else {
            percentageSection.style.display = 'none';
            fixedSection.style.display = 'block';
            feePercentageInput.disabled = true;
            feeFixedInput.disabled = false;
        }
        
        updateFeePreview();
    }

    function updateFeePreview() {
        const selectedType = document.querySelector('input[name="fee_type"]:checked').value;
        let feeAmount = 0;
        
        if (selectedType === 'percentage') {
            const percentage = parseFloat(feePercentageInput.value) || 0;
            feeAmount = (100 * percentage) / 100;
        } else {
            feeAmount = parseFloat(feeFixedInput.value) || 0;
        }
        
        // Update preview for $100 transaction
        feePreviewAmount.textContent = '$' + feeAmount.toFixed(2);
        
        // Update preview for $1000 transaction
        let feeAmount1000 = 0;
        if (selectedType === 'percentage') {
            const percentage = parseFloat(feePercentageInput.value) || 0;
            feeAmount1000 = (1000 * percentage) / 100;
        } else {
            feeAmount1000 = parseFloat(feeFixedInput.value) || 0;
        }
        feePreviewAmount1000.textContent = '$' + feeAmount1000.toFixed(2);
    }

    // Add event listeners for fee type changes
    feeTypeRadios.forEach(radio => {
        radio.addEventListener('change', toggleFeeSections);
    });

    // Add event listeners for fee value changes
    feePercentageInput.addEventListener('input', updateFeePreview);
    feeFixedInput.addEventListener('input', updateFeePreview);

    // Initialize on page load
    toggleFeeSections();
});
