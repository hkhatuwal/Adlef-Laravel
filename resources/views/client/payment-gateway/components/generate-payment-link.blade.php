<!-- Generate Payment Link Component -->
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-lg">
        <div class="mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Generate Payment Link</h3>
            <p class="text-sm text-gray-600 mt-1">Create a payment link for your customer</p>
        </div>

        <form id="generate-payment-link-form" class="space-y-4">
            @csrf

            <!-- API Client Selection -->
            <div>
                <label for="api_client_id" class="block text-sm font-medium text-gray-700 mb-1">
                    Select API Client <span class="text-red-500">*</span>
                </label>
                <select id="api_client_id" name="api_client_id" required
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">-- Select API Client --</option>
                    @foreach($apiClients as $client)
                        <option value="{{ $client->id }}"
                                data-api-key="{{ $client->api_key }}"
                                data-secret-key="{{ $client->secret_key }}"
                                data-environment="{{ $client->is_sandbox ? 'Sandbox' : 'Production' }}"
                                {{ !$client->is_active ? 'disabled' : '' }}>
                            {{ $client->name }}
                            ({{ $client->is_sandbox ? 'Sandbox' : 'Production' }})
                            {{ !$client->is_active ? '- Inactive' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Amount and Currency Row -->
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">
                        Amount <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="amount" name="amount" step="0.01" min="0.01" required
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="0.00">
                </div>

                <div>
                    <label for="currency" class="block text-sm font-medium text-gray-700 mb-1">
                        Currency <span class="text-red-500">*</span>
                    </label>
                    <select id="currency" name="currency" required
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="USD">USD</option>
                    </select>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-1">
                        Customer Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="customer_name" name="customer_name" required maxlength="255"
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="John Doe">
                </div>

                <div>
                    <label for="customer_phone" class="block text-sm font-medium text-gray-700 mb-1">
                        Customer Phone <span class="text-red-500">*</span>
                    </label>
                    <input type="tel" id="customer_phone" name="customer_phone" required maxlength="20"
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="+1234567890">
                </div>
            </div>

            <div>
                <label for="customer_email" class="block text-sm font-medium text-gray-700 mb-1">
                    Customer Email <span class="text-red-500">*</span>
                </label>
                <input type="email" id="customer_email" name="customer_email" required maxlength="255"
                       class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="customer@example.com">
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                    Description (Optional)
                </label>
                <textarea id="description" name="description" rows="2" maxlength="255"
                          class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                          placeholder="Payment for services..."></textarea>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-2 pt-2">
                <button type="button" id="reset-form-btn"
                        class="px-4 py-2 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all">
                    Reset
                </button>
                <button type="submit" id="generate-link-btn"
                        class="px-4 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all">
                    <i class="fa-solid fa-link mr-2"></i>
                    Generate Link
                </button>
            </div>
        </form>

        <!-- Success Message -->
        <div id="payment-link-success" class="hidden mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="fa-solid fa-check-circle text-green-600 text-xl"></i>
                </div>
                <div class="ml-3 flex-1">
                    <h4 class="text-base font-semibold text-green-900 mb-2">Payment Link Generated!</h4>
                    <div class="space-y-2">
                        <div>
                            <label class="block text-xs font-medium text-green-800 mb-1">Transaction ID:</label>
                            <div class="flex items-center space-x-2">
                                <input type="text" id="result-transaction-id" readonly
                                       class="flex-1 px-2 py-1.5 text-sm bg-white border border-green-300 rounded">
                                <button onclick="copyToClipboard('result-transaction-id')"
                                        class="px-2 py-1.5 text-xs bg-green-600 text-white rounded hover:bg-green-700">
                                    <i class="fa-solid fa-copy"></i> Copy
                                </button>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-green-800 mb-1">Checkout URL:</label>
                            <div class="flex items-center space-x-2">
                                <input type="text" id="result-checkout-url" readonly
                                       class="flex-1 px-2 py-1.5 text-sm bg-white border border-green-300 rounded">
                                <button onclick="copyToClipboard('result-checkout-url')"
                                        class="px-2 py-1.5 text-xs bg-green-600 text-white rounded hover:bg-green-700">
                                    <i class="fa-solid fa-copy"></i> Copy
                                </button>
                                <a id="result-checkout-url-link" href="#" target="_blank"
                                   class="px-2 py-1.5 text-xs bg-blue-600 text-white rounded hover:bg-blue-700">
                                    <i class="fa-solid fa-external-link-alt"></i> Open
                                </a>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3 pt-1">
                            <div>
                                <span class="text-xs text-green-800">Amount:</span>
                                <span class="font-semibold text-sm text-green-900" id="result-amount"></span>
                            </div>
                            <div>
                                <span class="text-xs text-green-800">Status:</span>
                                <span class="font-semibold text-sm text-green-900" id="result-status"></span>
                            </div>
                        </div>
                        <p class="text-xs text-green-700 mt-2">
                            <i class="fa-solid fa-info-circle mr-1"></i>
                            Link expires in 24 hours. Share it with your customer to complete payment.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Error Message -->
        <div id="payment-link-error" class="hidden mt-4 p-3 bg-red-50 border border-red-200 rounded-lg">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="fa-solid fa-exclamation-circle text-red-600"></i>
                </div>
                <div class="ml-3">
                    <h4 class="text-sm font-semibold text-red-900">Error</h4>
                    <p class="text-sm text-red-700 mt-1" id="payment-link-error-message"></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Generate Payment Link Form Handler
    const generatePaymentForm = document.getElementById('generate-payment-link-form');
    const generateLinkBtn = document.getElementById('generate-link-btn');
    const resetFormBtn = document.getElementById('reset-form-btn');
    const successDiv = document.getElementById('payment-link-success');
    const errorDiv = document.getElementById('payment-link-error');
    const errorMessage = document.getElementById('payment-link-error-message');

    if (generatePaymentForm) {
        generatePaymentForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            // Hide previous messages
            successDiv.classList.add('hidden');
            errorDiv.classList.add('hidden');

            // Get selected API client
            const apiClientSelect = document.getElementById('api_client_id');
            const selectedOption = apiClientSelect.options[apiClientSelect.selectedIndex];

            if (!selectedOption.value) {
                errorMessage.textContent = 'Please select an API client';
                errorDiv.classList.remove('hidden');
                return;
            }

            const apiKey = selectedOption.dataset.apiKey;
            const secretKey = selectedOption.dataset.secretKey;

            // Disable button and show loading
            const originalBtnText = generateLinkBtn.innerHTML;
            generateLinkBtn.disabled = true;
            generateLinkBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i>Generating...';

            try {
                // Prepare form data
                const formData = {
                    amount: parseFloat(document.getElementById('amount').value),
                    currency: document.getElementById('currency').value,
                    customer_name: document.getElementById('customer_name').value,
                    customer_email: document.getElementById('customer_email').value,
                    customer_phone: document.getElementById('customer_phone').value,
                    description: document.getElementById('description').value || undefined,
                };

                // Call the API endpoint
                const response = await fetch('{{ route("api.payment.generate") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-API-Key': apiKey,
                        'X-Secret-Key': secretKey,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(formData)
                });

                const result = await response.json();

                if (result.success && result.data) {
                    // Show success message with details
                    document.getElementById('result-transaction-id').value = result.data.transaction_id;
                    document.getElementById('result-checkout-url').value = result.data.checkout_url;
                    document.getElementById('result-checkout-url-link').href = result.data.checkout_url;
                    document.getElementById('result-amount').textContent = result.data.amount + ' ' + result.data.currency;
                    document.getElementById('result-status').textContent = result.data.status;

                    successDiv.classList.remove('hidden');

                    // Scroll to success message
                    successDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });

                    // Reset form
                    generatePaymentForm.reset();
                } else {
                    // Show error message
                    errorMessage.textContent = result.message || 'Failed to generate payment link';
                    if (result.errors) {
                        const errorList = Object.values(result.errors).flat().join(', ');
                        errorMessage.textContent += ': ' + errorList;
                    }
                    errorDiv.classList.remove('hidden');
                    errorDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }

            } catch (error) {
                console.error('Error generating payment link:', error);
                errorMessage.textContent = 'An error occurred while generating the payment link. Please try again.';
                errorDiv.classList.remove('hidden');
                errorDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            } finally {
                // Re-enable button
                generateLinkBtn.disabled = false;
                generateLinkBtn.innerHTML = originalBtnText;
            }
        });

        // Reset form button
        resetFormBtn.addEventListener('click', function() {
            generatePaymentForm.reset();
            successDiv.classList.add('hidden');
            errorDiv.classList.add('hidden');
        });
    }
});
</script>

