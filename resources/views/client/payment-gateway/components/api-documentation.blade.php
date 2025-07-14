<div class="space-y-8">
    <div class="text-center">
        <h2 class="text-2xl font-bold text-gray-900 mb-3">API Integration Guide</h2>
        <p class="text-gray-600">Complete documentation for integrating with our payment gateway API</p>
    </div>

    <!-- Authentication Section -->
    <div class="bg-gray-50 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <i class="fa-solid fa-shield-halved text-blue-600 mr-2"></i>
            Authentication
        </h3>
        <p class="text-gray-700 mb-4">All API requests require authentication using your API key and secret key in the
            request headers:</p>
        <div class="bg-white rounded-lg border p-4">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-gray-900">Required Headers</span>
                <button class="copy-btn text-sm text-blue-600 hover:text-blue-800" data-copy="headers">
                    <i class="fa-solid fa-copy mr-1"></i>Copy
                </button>
            </div>
            <pre class="text-sm text-gray-800 bg-gray-50 p-3 rounded"><code>X-API-Key: your_api_key_here
X-Secret-Key: your_secret_key_here
Content-Type: application/json</code></pre>
        </div>
    </div>

    <!-- Payment Generation API -->
    <div class="bg-white border rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <i class="fa-solid fa-credit-card text-green-600 mr-2"></i>
            Generate Payment
        </h3>

        <div class="space-y-4">
            <div>
                <h4 class="font-medium text-gray-900 mb-2">Endpoint</h4>
                <div class="bg-gray-50 rounded-lg p-3 border">
                    <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-sm font-mono">POST</span>
                    <span class="ml-3 font-mono text-gray-700">{{ url('/api/v1/payment/generate') }}</span>
                </div>
            </div>

            <div>
                <h4 class="font-medium text-gray-900 mb-2">Request Body Parameters</h4>
                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-200 rounded-lg">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Parameter</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Required</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                        <tr>
                            <td class="px-4 py-3 text-sm font-mono text-gray-900">amount</td>
                            <td class="px-4 py-3 text-sm text-gray-700">number</td>
                            <td class="px-4 py-3 text-sm text-red-600">Required</td>
                            <td class="px-4 py-3 text-sm text-gray-700">Payment amount (e.g., 10.00)</td>
                        </tr>
                        <tr class="bg-gray-50">
                            <td class="px-4 py-3 text-sm font-mono text-gray-900">currency</td>
                            <td class="px-4 py-3 text-sm text-gray-700">string</td>
                            <td class="px-4 py-3 text-sm text-red-600">Required</td>
                            <td class="px-4 py-3 text-sm text-gray-700">Currency code (e.g., "USD", "EUR")</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 text-sm font-mono text-gray-900">description</td>
                            <td class="px-4 py-3 text-sm text-gray-700">string</td>
                            <td class="px-4 py-3 text-sm text-gray-500">Optional</td>
                            <td class="px-4 py-3 text-sm text-gray-700">Payment description</td>
                        </tr>
                        <tr class="bg-gray-50">
                            <td class="px-4 py-3 text-sm font-mono text-gray-900">customer_email</td>
                            <td class="px-4 py-3 text-sm text-gray-700">string</td>
                            <td class="px-4 py-3 text-sm text-red-600">Required</td>
                            <td class="px-4 py-3 text-sm text-gray-700">Customer's email address</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div>
                <h4 class="font-medium text-gray-900 mb-2">Example Request</h4>
                <div class="bg-gray-900 rounded-lg p-4 relative">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-green-400 text-sm font-medium">cURL Example</span>
                        <button class="copy-btn text-green-400 hover:text-green-300 text-sm" data-copy="curl">
                            <i class="fa-solid fa-copy mr-1"></i>Copy
                        </button>
                    </div>
                    <pre class="text-green-400 text-sm overflow-x-auto" id="curl-example"><code>curl --location '{{ url('/api/v1/payment/generate') }}' \
--header 'X-API-Key: ak_BOV8leQRfljCq68WcHB9Zm6dzMM9eEZH' \
--header 'X-Secret-Key: sk_Ov0yxZwW5gnxFbfDCaudJWp2ADSxE6Fo' \
--header 'Content-Type: application/json' \
--data-raw '{
     "amount": 10.00,
     "currency": "USD",
     "description": "Test payment",
     "customer_email": "customer@example.com"
}'</code></pre>
                </div>
            </div>

            <div>
                <h4 class="font-medium text-gray-900 mb-2">Response Format</h4>
                <div class="bg-gray-50 rounded-lg p-4">
                    <pre class="text-sm text-gray-800"><code>{
    "success": true,
    "message": "Payment link generated successfully",
    "data": {
        "transaction_id": "txn_AWx7Dd8yE7QQ80ro",
        "checkout_session_id": "cs_8cu2Ue2DCQ68IyewKAbd",
        "checkout_url": "http://localhost:8000/payment/checkout/cs_8cu2Ue2DCQ68IyewKAbd",
        "amount": "10.00",
        "currency": "USD",
        "status": "checkout_pending",
        "expires_at": "2025-06-28T10:23:28.020690Z",
        "created_at": "2025-06-27T10:23:28.000000Z"
    }
}</code></pre>
                </div>
            </div>
        </div>
    </div>

    <!-- Get Payment Details API -->
    <div class="bg-white border rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <i class="fa-solid fa-search text-blue-600 mr-2"></i>
            Get Payment Details
        </h3>

        <div class="space-y-4">
            <div>
                <h4 class="font-medium text-gray-900 mb-2">Endpoint</h4>
                <div class="bg-gray-50 rounded-lg p-3 border">
                    <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-sm font-mono">GET</span>
                    <span class="ml-3 font-mono text-gray-700">{{ url('/api/v1/payment/details/{transactionId}') }}</span>
                </div>
            </div>

            <div>
                <h4 class="font-medium text-gray-900 mb-2">Path Parameters</h4>
                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-200 rounded-lg">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Parameter</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Required</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                        <tr>
                            <td class="px-4 py-3 text-sm font-mono text-gray-900">transactionId</td>
                            <td class="px-4 py-3 text-sm text-gray-700">string</td>
                            <td class="px-4 py-3 text-sm text-red-600">Required</td>
                            <td class="px-4 py-3 text-sm text-gray-700">Transaction ID (e.g., txn_tIO5XClz1fmjQ57a)</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div>
                <h4 class="font-medium text-gray-900 mb-2">Example Request</h4>
                <div class="bg-gray-900 rounded-lg p-4 relative">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-green-400 text-sm font-medium">cURL Example</span>
                        <button class="copy-btn text-green-400 hover:text-green-300 text-sm" data-copy="curl-details">
                            <i class="fa-solid fa-copy mr-1"></i>Copy
                        </button>
                    </div>
                    <pre class="text-green-400 text-sm overflow-x-auto" id="curl-details-example"><code>curl --location '{{ url('/api/v1/payment/details/txn_tIO5XClz1fmjQ57a') }}' \
--header 'X-API-Key: ak_BOV8leQRfljCq68WcHB9Zm6dzMM9eEZH' \
--header 'X-Secret-Key: sk_Ov0yxZwW5gnxFbfDCaudJWp2ADSxE6Fo' \
--header 'Content-Type: application/json'</code></pre>
                </div>
            </div>

            <div>
                <h4 class="font-medium text-gray-900 mb-2">Response Format</h4>
                <div class="bg-gray-50 rounded-lg p-4">
                    <pre class="text-sm text-gray-800"><code>{
    "success": true,
    "data": {
        "transaction_id": "txn_tIO5XClz1fmjQ57a",
        "status": "pending",
        "amount": "9.99",
        "currency": "USDT",
        "description": "Test payment",
        "customer_email": "customer@example.com",
        "customer_name": null,
        "payment_url": "http://localhost:8000/payment/crypto/TASkPTT2od6fmtKhGyexSJwzF4c3DVHdi6",
        "gateway_transaction_id": "TASkPTT2od6fmtKhGyexSJwzF4c3DVHdi6",
        "client_order_id": null,
        "created_at": "2025-06-30T11:51:35.000000Z",
        "updated_at": "2025-06-30T11:51:41.000000Z"
    }
}</code></pre>
                </div>
            </div>
        </div>
    </div>

    <!-- Webhooks Section -->
    <div class="bg-white border rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <i class="fa-solid fa-webhook text-orange-600 mr-2"></i>
            Webhooks
        </h3>

        <div class="space-y-6">
            <div>
                <p class="text-gray-700 mb-4">
                    Webhooks are HTTP callbacks that notify your application when payment events occur.
                    Our system will automatically send webhook notifications to your registered webhook URLs
                    when payment status changes.
                </p>
            </div>

            <div>
                <h4 class="font-medium text-gray-900 mb-2">Webhook Configuration</h4>
                <p class="text-gray-700 mb-4">
                    Configure your webhook URLs in the API Keys section. You can add multiple webhook URLs
                    to receive notifications at different endpoints.
                </p>
            </div>

            <div>
                <h4 class="font-medium text-gray-900 mb-2">Webhook Request Format</h4>
                <p class="text-gray-700 mb-4">
                    When a payment event occurs, we'll send a POST request to your webhook URL with the following headers:
                </p>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-900">Webhook Headers</span>
                        <button class="copy-btn text-sm text-blue-600 hover:text-blue-800" data-copy="webhook-headers">
                            <i class="fa-solid fa-copy mr-1"></i>Copy
                        </button>
                    </div>
                    <pre class="text-sm text-gray-800 bg-white p-3 rounded border"><code>X-Signature: generated_signature_hash
X-Event-Type: payment.{status}
Content-Type: application/json</code></pre>
                </div>
            </div>

            <div>
                <h4 class="font-medium text-gray-900 mb-2">Event Types</h4>
                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-200 rounded-lg">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Event Type</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                        <tr>
                            <td class="px-4 py-3 text-sm font-mono text-gray-900">payment.pending</td>
                            <td class="px-4 py-3 text-sm text-gray-700">Payment is awaiting completion</td>
                        </tr>
                        <tr class="bg-gray-50">
                            <td class="px-4 py-3 text-sm font-mono text-gray-900">payment.completed</td>
                            <td class="px-4 py-3 text-sm text-gray-700">Payment has been successfully completed</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 text-sm font-mono text-gray-900">payment.failed</td>
                            <td class="px-4 py-3 text-sm text-gray-700">Payment has failed</td>
                        </tr>
                        <tr class="bg-gray-50">
                            <td class="px-4 py-3 text-sm font-mono text-gray-900">payment.expired</td>
                            <td class="px-4 py-3 text-sm text-gray-700">Payment has expired</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div>
                <h4 class="font-medium text-gray-900 mb-2">Webhook Payload</h4>
                <p class="text-gray-700 mb-4">
                    The webhook payload contains the complete transaction information:
                </p>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-900">Example Webhook Payload</span>
                        <button class="copy-btn text-sm text-blue-600 hover:text-blue-800" data-copy="webhook-payload">
                            <i class="fa-solid fa-copy mr-1"></i>Copy
                        </button>
                    </div>
                    <pre class="text-sm text-gray-800 bg-white p-3 rounded border"><code>{
    "transaction_id": "txn_tIO5XClz1fmjQ57a",
    "status": "completed",
    "amount": "9.99",
    "currency": "USDT",
    "description": "Test payment",
    "customer_email": "customer@example.com",
    "customer_name": null,
    "payment_url": "http://localhost:8000/payment/crypto/TASkPTT2od6fmtKhGyexSJwzF4c3DVHdi6",
    "gateway_transaction_id": "TASkPTT2od6fmtKhGyexSJwzF4c3DVHdi6",
    "client_order_id": null,
    "created_at": "2025-06-30T11:51:35.000000Z",
    "updated_at": "2025-06-30T11:51:41.000000Z"
}</code></pre>
                </div>
            </div>



            <div>
                <h4 class="font-medium text-gray-900 mb-2">Webhook Response</h4>
                <p class="text-gray-700 mb-4">
                    Your webhook endpoint should respond with a 200 status code to acknowledge receipt.
                    If we don't receive a 200 response within 30 seconds, we'll consider the webhook delivery failed.
                </p>
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <i class="fa-solid fa-exclamation-triangle text-yellow-600 mr-2 mt-1"></i>
                        <div>
                            <h5 class="font-medium text-yellow-800">Important Notes</h5>
                            <ul class="text-sm text-yellow-700 mt-1 space-y-1">
                                <li>• Webhook delivery timeout is 30 seconds</li>
                                <li>• Handle duplicate webhook deliveries gracefully</li>
                                <li>• Respond with 200 status code for successful processing</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Response Codes Section -->
    <div class="bg-white border rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
            <i class="fa-solid fa-code text-purple-600 mr-2"></i>
            Response Codes
        </h3>
        <div class="grid md:grid-cols-2 gap-8">
            <div class="bg-green-50 rounded-lg p-4">
                <h4 class="font-medium text-green-700 mb-4">Success Codes</h4>
                <div class="space-y-3">
                    <div class="flex items-center">
                        <span
                            class="font-mono bg-green-100 text-green-800 px-2 py-1 rounded w-16 text-center">200</span>
                        <span class="text-gray-700 ml-4">Payment generated successfully</span>
                    </div>
                </div>
            </div>
            <div class="bg-red-50 rounded-lg p-4">
                <h4 class="font-medium text-red-700 mb-4">Error Codes</h4>
                <div class="space-y-3">
                    <div class="flex items-center">
                        <span class="font-mono bg-red-100 text-red-800 px-2 py-1 rounded w-16 text-center">400</span>
                        <span class="text-gray-700 ml-4">Bad Request</span>
                    </div>
                    <div class="flex items-center">
                        <span class="font-mono bg-red-100 text-red-800 px-2 py-1 rounded w-16 text-center">401</span>
                        <span class="text-gray-700 ml-4">Unauthorized</span>
                    </div>
                    <div class="flex items-center">
                        <span class="font-mono bg-red-100 text-red-800 px-2 py-1 rounded w-16 text-center">422</span>
                        <span class="text-gray-700 ml-4">Validation Error</span>
                    </div>
                    <div class="flex items-center">
                        <span class="font-mono bg-red-100 text-red-800 px-2 py-1 rounded w-16 text-center">500</span>
                        <span class="text-gray-700 ml-4">Internal Server Error</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
