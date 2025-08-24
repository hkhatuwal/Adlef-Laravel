@extends('_partials.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-white">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-20">
        <div class="container mx-auto px-6">
            <div class="text-center">
                <h1 class="text-5xl font-bold mb-6" data-aos="fade-up">
                    API Documentation
                </h1>
                <p class="text-xl opacity-90 max-w-3xl mx-auto leading-relaxed" data-aos="fade-up" data-aos-delay="100">
                    Integrate our powerful payment gateway API into your applications. 
                    Accept payments seamlessly with our comprehensive REST API.
                </p>
                <div class="mt-8" data-aos="fade-up" data-aos-delay="200">
                    <a href="#getting-started" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition duration-300 inline-flex items-center">
                        <i class="fa-solid fa-rocket mr-2"></i>
                        Get Started
                    </a>
                    <a href="#examples" class="ml-4 border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition duration-300 inline-flex items-center">
                        <i class="fa-solid fa-code mr-2"></i>
                        View Examples
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Pills -->
    <div class="sticky top-0 bg-white shadow-md z-40">
        <div class="container mx-auto px-6">
            <nav class="flex space-x-8 py-4 overflow-x-auto">
                <a href="#getting-started" class="nav-pill whitespace-nowrap px-4 py-2 rounded-lg text-gray-600 hover:text-blue-600 hover:bg-blue-50 transition duration-300">
                    <i class="fa-solid fa-play mr-2"></i>Getting Started
                </a>
                <a href="#authentication" class="nav-pill whitespace-nowrap px-4 py-2 rounded-lg text-gray-600 hover:text-blue-600 hover:bg-blue-50 transition duration-300">
                    <i class="fa-solid fa-shield-halved mr-2"></i>Authentication
                </a>
                <a href="#generate-payment" class="nav-pill whitespace-nowrap px-4 py-2 rounded-lg text-gray-600 hover:text-blue-600 hover:bg-blue-50 transition duration-300">
                    <i class="fa-solid fa-credit-card mr-2"></i>Generate Payment
                </a>
                <a href="#payment-details" class="nav-pill whitespace-nowrap px-4 py-2 rounded-lg text-gray-600 hover:text-blue-600 hover:bg-blue-50 transition duration-300">
                    <i class="fa-solid fa-search mr-2"></i>Payment Details
                </a>
                <a href="#webhooks" class="nav-pill whitespace-nowrap px-4 py-2 rounded-lg text-gray-600 hover:text-blue-600 hover:bg-blue-50 transition duration-300">
                    <i class="fa-solid fa-webhook mr-2"></i>Webhooks
                </a>
                <a href="#response-codes" class="nav-pill whitespace-nowrap px-4 py-2 rounded-lg text-gray-600 hover:text-blue-600 hover:bg-blue-50 transition duration-300">
                    <i class="fa-solid fa-code mr-2"></i>Response Codes
                </a>
            </nav>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-6 py-12">
        <div class="max-w-6xl mx-auto space-y-16">

            <!-- Getting Started Section -->
            <section id="getting-started" class="scroll-mt-24" data-aos="fade-up">
                <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                    <div class="flex items-center mb-6">
                        <div class="bg-green-100 p-3 rounded-lg mr-4">
                            <i class="fa-solid fa-play text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-3xl font-bold text-gray-900">Getting Started</h2>
                            <p class="text-gray-600 mt-1">Everything you need to begin integrating our API</p>
                        </div>
                    </div>
                    
                    <div class="grid md:grid-cols-3 gap-6">
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-xl">
                            <div class="bg-blue-600 w-12 h-12 rounded-lg flex items-center justify-center mb-4">
                                <i class="fa-solid fa-key text-white"></i>
                            </div>
                            <h3 class="font-semibold text-gray-900 mb-2">1. Get API Keys</h3>
                            <p class="text-gray-600 text-sm">Sign up and generate your API keys from the client dashboard</p>
                        </div>
                        <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-6 rounded-xl">
                            <div class="bg-purple-600 w-12 h-12 rounded-lg flex items-center justify-center mb-4">
                                <i class="fa-solid fa-code text-white"></i>
                            </div>
                            <h3 class="font-semibold text-gray-900 mb-2">2. Make API Calls</h3>
                            <p class="text-gray-600 text-sm">Use our RESTful API endpoints to create and manage payments</p>
                        </div>
                        <div class="bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-xl">
                            <div class="bg-green-600 w-12 h-12 rounded-lg flex items-center justify-center mb-4">
                                <i class="fa-solid fa-webhook text-white"></i>
                            </div>
                            <h3 class="font-semibold text-gray-900 mb-2">3. Handle Webhooks</h3>
                            <p class="text-gray-600 text-sm">Receive real-time notifications about payment status changes</p>
                        </div>
                    </div>

                    <div class="mt-8 p-6 bg-yellow-50 border-l-4 border-yellow-400 rounded-lg">
                        <div class="flex items-start">
                            <i class="fa-solid fa-lightbulb text-yellow-600 mr-3 mt-1"></i>
                            <div>
                                <h4 class="font-semibold text-yellow-800 mb-2">Quick Start Tip</h4>
                                <p class="text-yellow-700 text-sm">
                                    All API endpoints use HTTPS and require authentication headers. 
                                    Test your integration in sandbox mode before going live.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Authentication Section -->
            <section id="authentication" class="scroll-mt-24" data-aos="fade-up">
                <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                    <div class="flex items-center mb-6">
                        <div class="bg-blue-100 p-3 rounded-lg mr-4">
                            <i class="fa-solid fa-shield-halved text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-3xl font-bold text-gray-900">Authentication</h2>
                            <p class="text-gray-600 mt-1">Secure your API requests with proper authentication</p>
                        </div>
                    </div>
                    
                    <p class="text-gray-700 mb-6 text-lg">
                        All API requests require authentication using your API key and secret key in the request headers:
                    </p>
                    
                    <div class="bg-gray-900 rounded-xl p-6 relative">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-green-400 font-medium flex items-center">
                                <i class="fa-solid fa-terminal mr-2"></i>Required Headers
                            </span>
                            <button class="copy-btn bg-gray-800 hover:bg-gray-700 text-green-400 px-3 py-1 rounded text-sm transition duration-200" data-copy="headers">
                                <i class="fa-solid fa-copy mr-1"></i>Copy
                            </button>
                        </div>
                        <pre class="text-green-400 text-sm overflow-x-auto" id="headers-example"><code>X-API-Key: your_api_key_here
X-Secret-Key: your_secret_key_here
Content-Type: application/json</code></pre>
                    </div>

                    <div class="mt-6 grid md:grid-cols-2 gap-6">
                        <div class="bg-blue-50 p-6 rounded-xl">
                            <h4 class="font-semibold text-blue-900 mb-3 flex items-center">
                                <i class="fa-solid fa-key mr-2"></i>API Key
                            </h4>
                            <p class="text-blue-800 text-sm">
                                Your public API key that identifies your application. 
                                This key is safe to use in client-side code.
                            </p>
                        </div>
                        <div class="bg-red-50 p-6 rounded-xl">
                            <h4 class="font-semibold text-red-900 mb-3 flex items-center">
                                <i class="fa-solid fa-lock mr-2"></i>Secret Key
                            </h4>
                            <p class="text-red-800 text-sm">
                                Your private secret key that authenticates your requests. 
                                Keep this key secure and never expose it in client-side code.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Generate Payment Section -->
            <section id="generate-payment" class="scroll-mt-24" data-aos="fade-up">
                <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                    <div class="flex items-center mb-6">
                        <div class="bg-green-100 p-3 rounded-lg mr-4">
                            <i class="fa-solid fa-credit-card text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-3xl font-bold text-gray-900">Generate Payment</h2>
                            <p class="text-gray-600 mt-1">Create payment links for your customers</p>
                        </div>
                    </div>

                    <div class="space-y-8">
                        <!-- Endpoint -->
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-4">Endpoint</h3>
                            <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-xl p-4 border-l-4 border-green-500">
                                <div class="flex items-center flex-wrap gap-3">
                                    <span class="bg-green-600 text-white px-3 py-1 rounded-lg text-sm font-mono font-bold">POST</span>
                                    <code class="text-gray-800 font-mono bg-white px-3 py-1 rounded">{{ url('/api/v1/payment/generate') }}</code>
                                </div>
                            </div>
                        </div>

                        <!-- Parameters -->
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-4">Request Parameters</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white border border-gray-200 rounded-xl overflow-hidden">
                                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                                        <tr>
                                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Parameter</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Type</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Required</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Description</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        <tr class="hover:bg-gray-50 transition duration-200">
                                            <td class="px-6 py-4 text-sm font-mono font-bold text-gray-900">amount</td>
                                            <td class="px-6 py-4 text-sm text-gray-700">number</td>
                                            <td class="px-6 py-4">
                                                <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs font-semibold">Required</span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-700">Payment amount (e.g., 10.00)</td>
                                        </tr>
                                        <tr class="hover:bg-gray-50 transition duration-200 bg-gray-25">
                                            <td class="px-6 py-4 text-sm font-mono font-bold text-gray-900">currency</td>
                                            <td class="px-6 py-4 text-sm text-gray-700">string</td>
                                            <td class="px-6 py-4">
                                                <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs font-semibold">Required</span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-700">Currency code (e.g., "USD", "EUR")</td>
                                        </tr>
                                        <tr class="hover:bg-gray-50 transition duration-200">
                                            <td class="px-6 py-4 text-sm font-mono font-bold text-gray-900">description</td>
                                            <td class="px-6 py-4 text-sm text-gray-700">string</td>
                                            <td class="px-6 py-4">
                                                <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded-full text-xs font-semibold">Optional</span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-700">Payment description</td>
                                        </tr>
                                        <tr class="hover:bg-gray-50 transition duration-200 bg-gray-25">
                                            <td class="px-6 py-4 text-sm font-mono font-bold text-gray-900">customer_email</td>
                                            <td class="px-6 py-4 text-sm text-gray-700">string</td>
                                            <td class="px-6 py-4">
                                                <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs font-semibold">Required</span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-700">Customer's email address</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Example Request -->
                        <div id="examples">
                            <h3 class="text-xl font-semibold text-gray-900 mb-4">Example Request</h3>
                            <div class="bg-gray-900 rounded-xl p-6 relative">
                                <div class="flex items-center justify-between mb-4">
                                    <span class="text-green-400 font-medium flex items-center">
                                        <i class="fa-solid fa-terminal mr-2"></i>cURL Example
                                    </span>
                                    <button class="copy-btn bg-gray-800 hover:bg-gray-700 text-green-400 px-3 py-1 rounded text-sm transition duration-200" data-copy="curl">
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

                        <!-- Response Format -->
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-4">Response Format</h3>
                            <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-6 border">
                                <div class="flex items-center justify-between mb-4">
                                    <span class="text-gray-700 font-medium flex items-center">
                                        <i class="fa-solid fa-code mr-2"></i>JSON Response
                                    </span>
                                    <button class="copy-btn bg-white hover:bg-gray-100 text-gray-600 px-3 py-1 rounded text-sm transition duration-200 border" data-copy="response">
                                        <i class="fa-solid fa-copy mr-1"></i>Copy
                                    </button>
                                </div>
                                <pre class="text-sm text-gray-800 bg-white p-4 rounded-lg border overflow-x-auto" id="response-example"><code>{
    "success": true,
    "message": "Payment link generated successfully",
    "data": {
        "transaction_id": "txn_AWx7Dd8yE7QQ80ro",
        "checkout_session_id": "cs_8cu2Ue2DCQ68IyewKAbd",
        "checkout_url": "{{ url('/payment/checkout/') }}/cs_8cu2Ue2DCQ68IyewKAbd",
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
            </section>

            <!-- Payment Details Section -->
            <section id="payment-details" class="scroll-mt-24" data-aos="fade-up">
                <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                    <div class="flex items-center mb-6">
                        <div class="bg-blue-100 p-3 rounded-lg mr-4">
                            <i class="fa-solid fa-search text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-3xl font-bold text-gray-900">Get Payment Details</h2>
                            <p class="text-gray-600 mt-1">Retrieve information about existing payments</p>
                        </div>
                    </div>

                    <div class="space-y-8">
                        <!-- Endpoint -->
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-4">Endpoint</h3>
                            <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl p-4 border-l-4 border-blue-500">
                                <div class="flex items-center flex-wrap gap-3">
                                    <span class="bg-blue-600 text-white px-3 py-1 rounded-lg text-sm font-mono font-bold">GET</span>
                                    <code class="text-gray-800 font-mono bg-white px-3 py-1 rounded">{{ url('/api/v1/payment/details/{transactionId}') }}</code>
                                </div>
                            </div>
                        </div>

                        <!-- Path Parameters -->
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-4">Path Parameters</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white border border-gray-200 rounded-xl overflow-hidden">
                                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                                        <tr>
                                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Parameter</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Type</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Required</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Description</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="hover:bg-gray-50 transition duration-200">
                                            <td class="px-6 py-4 text-sm font-mono font-bold text-gray-900">transactionId</td>
                                            <td class="px-6 py-4 text-sm text-gray-700">string</td>
                                            <td class="px-6 py-4">
                                                <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs font-semibold">Required</span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-700">Transaction ID (e.g., txn_tIO5XClz1fmjQ57a)</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Example Request -->
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-4">Example Request</h3>
                            <div class="bg-gray-900 rounded-xl p-6 relative">
                                <div class="flex items-center justify-between mb-4">
                                    <span class="text-green-400 font-medium flex items-center">
                                        <i class="fa-solid fa-terminal mr-2"></i>cURL Example
                                    </span>
                                    <button class="copy-btn bg-gray-800 hover:bg-gray-700 text-green-400 px-3 py-1 rounded text-sm transition duration-200" data-copy="curl-details">
                                        <i class="fa-solid fa-copy mr-1"></i>Copy
                                    </button>
                                </div>
                                <pre class="text-green-400 text-sm overflow-x-auto" id="curl-details-example"><code>curl --location '{{ url('/api/v1/payment/details/txn_tIO5XClz1fmjQ57a') }}' \
--header 'X-API-Key: ak_BOV8leQRfljCq68WcHB9Zm6dzMM9eEZH' \
--header 'X-Secret-Key: sk_Ov0yxZwW5gnxFbfDCaudJWp2ADSxE6Fo' \
--header 'Content-Type: application/json'</code></pre>
                            </div>
                        </div>

                        <!-- Response Format -->
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-4">Response Format</h3>
                            <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-6 border">
                                <div class="flex items-center justify-between mb-4">
                                    <span class="text-gray-700 font-medium flex items-center">
                                        <i class="fa-solid fa-code mr-2"></i>JSON Response
                                    </span>
                                    <button class="copy-btn bg-white hover:bg-gray-100 text-gray-600 px-3 py-1 rounded text-sm transition duration-200 border" data-copy="details-response">
                                        <i class="fa-solid fa-copy mr-1"></i>Copy
                                    </button>
                                </div>
                                <pre class="text-sm text-gray-800 bg-white p-4 rounded-lg border overflow-x-auto" id="details-response-example"><code>{
    "success": true,
    "data": {
        "transaction_id": "txn_tIO5XClz1fmjQ57a",
        "status": "pending",
        "amount": "9.99",
        "currency": "USDT",
        "description": "Test payment",
        "customer_email": "customer@example.com",
        "customer_name": null,
        "payment_url": "{{ url('/payment/crypto/') }}/TASkPTT2od6fmtKhGyexSJwzF4c3DVHdi6",
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
            </section>

            <!-- Webhooks Section -->
            <section id="webhooks" class="scroll-mt-24" data-aos="fade-up">
                <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                    <div class="flex items-center mb-6">
                        <div class="bg-orange-100 p-3 rounded-lg mr-4">
                            <i class="fa-solid fa-webhook text-orange-600 text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-3xl font-bold text-gray-900">Webhooks</h2>
                            <p class="text-gray-600 mt-1">Real-time notifications for payment events</p>
                        </div>
                    </div>

                    <div class="space-y-8">
                        <div class="bg-gradient-to-r from-orange-50 to-yellow-50 p-6 rounded-xl border-l-4 border-orange-400">
                            <p class="text-gray-700 text-lg leading-relaxed">
                                Webhooks are HTTP callbacks that notify your application when payment events occur.
                                Our system will automatically send webhook notifications to your registered webhook URLs
                                when payment status changes.
                            </p>
                        </div>

                        <!-- Configuration -->
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-4">Webhook Configuration</h3>
                            <div class="bg-blue-50 p-6 rounded-xl">
                                <p class="text-blue-800 mb-4">
                                    Configure your webhook URLs in the API Keys section of your client dashboard. 
                                    You can add multiple webhook URLs to receive notifications at different endpoints.
                                </p>
                                <div class="bg-blue-100 p-4 rounded-lg">
                                    <p class="text-blue-900 text-sm font-medium">
                                        💡 <strong>Pro Tip:</strong> Test your webhook endpoints thoroughly before going live. 
                                        Use tools like ngrok for local development testing.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Request Format -->
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-4">Webhook Request Format</h3>
                            <p class="text-gray-700 mb-4">
                                When a payment event occurs, we'll send a POST request to your webhook URL with the following headers:
                            </p>
                            <div class="bg-gray-900 rounded-xl p-6 relative">
                                <div class="flex items-center justify-between mb-4">
                                    <span class="text-green-400 font-medium flex items-center">
                                        <i class="fa-solid fa-terminal mr-2"></i>Webhook Headers
                                    </span>
                                    <button class="copy-btn bg-gray-800 hover:bg-gray-700 text-green-400 px-3 py-1 rounded text-sm transition duration-200" data-copy="webhook-headers">
                                        <i class="fa-solid fa-copy mr-1"></i>Copy
                                    </button>
                                </div>
                                <pre class="text-green-400 text-sm overflow-x-auto" id="webhook-headers-example"><code>X-Signature: generated_signature_hash
X-Event-Type: payment.{status}
Content-Type: application/json</code></pre>
                            </div>
                        </div>

                        <!-- Event Types -->
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-4">Event Types</h3>
                            <div class="grid md:grid-cols-2 gap-4">
                                <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                                    <div class="flex items-center mb-2">
                                        <span class="bg-yellow-600 w-3 h-3 rounded-full mr-3"></span>
                                        <code class="font-mono text-sm font-bold text-yellow-800">payment.pending</code>
                                    </div>
                                    <p class="text-yellow-700 text-sm">Payment is awaiting completion</p>
                                </div>
                                <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                                    <div class="flex items-center mb-2">
                                        <span class="bg-green-600 w-3 h-3 rounded-full mr-3"></span>
                                        <code class="font-mono text-sm font-bold text-green-800">payment.completed</code>
                                    </div>
                                    <p class="text-green-700 text-sm">Payment has been successfully completed</p>
                                </div>
                                <div class="bg-red-50 p-4 rounded-lg border border-red-200">
                                    <div class="flex items-center mb-2">
                                        <span class="bg-red-600 w-3 h-3 rounded-full mr-3"></span>
                                        <code class="font-mono text-sm font-bold text-red-800">payment.failed</code>
                                    </div>
                                    <p class="text-red-700 text-sm">Payment has failed</p>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                    <div class="flex items-center mb-2">
                                        <span class="bg-gray-600 w-3 h-3 rounded-full mr-3"></span>
                                        <code class="font-mono text-sm font-bold text-gray-800">payment.expired</code>
                                    </div>
                                    <p class="text-gray-700 text-sm">Payment has expired</p>
                                </div>
                            </div>
                        </div>

                        <!-- Webhook Payload -->
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-4">Webhook Payload</h3>
                            <p class="text-gray-700 mb-4">
                                The webhook payload contains the complete transaction information:
                            </p>
                            <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-6 border">
                                <div class="flex items-center justify-between mb-4">
                                    <span class="text-gray-700 font-medium flex items-center">
                                        <i class="fa-solid fa-code mr-2"></i>Example Webhook Payload
                                    </span>
                                    <button class="copy-btn bg-white hover:bg-gray-100 text-gray-600 px-3 py-1 rounded text-sm transition duration-200 border" data-copy="webhook-payload">
                                        <i class="fa-solid fa-copy mr-1"></i>Copy
                                    </button>
                                </div>
                                <pre class="text-sm text-gray-800 bg-white p-4 rounded-lg border overflow-x-auto" id="webhook-payload-example"><code>{
    "transaction_id": "txn_tIO5XClz1fmjQ57a",
    "status": "completed",
    "amount": "9.99",
    "currency": "USDT",
    "description": "Test payment",
    "customer_email": "customer@example.com",
    "customer_name": null,
    "payment_url": "{{ url('/payment/crypto/') }}/TASkPTT2od6fmtKhGyexSJwzF4c3DVHdi6",
    "gateway_transaction_id": "TASkPTT2od6fmtKhGyexSJwzF4c3DVHdi6",
    "client_order_id": null,
    "created_at": "2025-06-30T11:51:35.000000Z",
    "updated_at": "2025-06-30T11:51:41.000000Z"
}</code></pre>
                            </div>
                        </div>

                        <!-- Important Notes -->
                        <div class="bg-amber-50 border border-amber-200 rounded-xl p-6">
                            <div class="flex items-start">
                                <i class="fa-solid fa-exclamation-triangle text-amber-600 mr-3 mt-1"></i>
                                <div>
                                    <h4 class="font-semibold text-amber-800 mb-3">Important Notes</h4>
                                    <ul class="text-sm text-amber-700 space-y-2">
                                        <li class="flex items-start">
                                            <span class="w-2 h-2 bg-amber-600 rounded-full mr-3 mt-2 flex-shrink-0"></span>
                                            <span>Webhook delivery timeout is 30 seconds</span>
                                        </li>
                                        <li class="flex items-start">
                                            <span class="w-2 h-2 bg-amber-600 rounded-full mr-3 mt-2 flex-shrink-0"></span>
                                            <span>Handle duplicate webhook deliveries gracefully using idempotency</span>
                                        </li>
                                        <li class="flex items-start">
                                            <span class="w-2 h-2 bg-amber-600 rounded-full mr-3 mt-2 flex-shrink-0"></span>
                                            <span>Always respond with 200 status code for successful processing</span>
                                        </li>
                                        <li class="flex items-start">
                                            <span class="w-2 h-2 bg-amber-600 rounded-full mr-3 mt-2 flex-shrink-0"></span>
                                            <span>Verify webhook signatures to ensure authenticity</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Response Codes Section -->
            <section id="response-codes" class="scroll-mt-24" data-aos="fade-up">
                <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                    <div class="flex items-center mb-8">
                        <div class="bg-purple-100 p-3 rounded-lg mr-4">
                            <i class="fa-solid fa-code text-purple-600 text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-3xl font-bold text-gray-900">Response Codes</h2>
                            <p class="text-gray-600 mt-1">HTTP status codes and their meanings</p>
                        </div>
                    </div>
                    
                    <div class="grid lg:grid-cols-2 gap-8">
                        <!-- Success Codes -->
                        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6 border border-green-200">
                            <h3 class="text-xl font-semibold text-green-800 mb-6 flex items-center">
                                <i class="fa-solid fa-check-circle mr-2"></i>
                                Success Codes
                            </h3>
                            <div class="space-y-4">
                                <div class="bg-white p-4 rounded-lg border border-green-200 flex items-center justify-between">
                                    <div class="flex items-center">
                                        <span class="font-mono bg-green-600 text-white px-3 py-1 rounded-lg font-bold text-lg">200</span>
                                        <div class="ml-4">
                                            <p class="font-semibold text-green-800">OK</p>
                                            <p class="text-green-700 text-sm">Request successful</p>
                                        </div>
                                    </div>
                                    <i class="fa-solid fa-check text-green-600"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Error Codes -->
                        <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl p-6 border border-red-200">
                            <h3 class="text-xl font-semibold text-red-800 mb-6 flex items-center">
                                <i class="fa-solid fa-exclamation-circle mr-2"></i>
                                Error Codes
                            </h3>
                            <div class="space-y-4">
                                <div class="bg-white p-4 rounded-lg border border-red-200 flex items-center justify-between">
                                    <div class="flex items-center">
                                        <span class="font-mono bg-red-600 text-white px-3 py-1 rounded-lg font-bold text-lg">400</span>
                                        <div class="ml-4">
                                            <p class="font-semibold text-red-800">Bad Request</p>
                                            <p class="text-red-700 text-sm">Invalid request format</p>
                                        </div>
                                    </div>
                                    <i class="fa-solid fa-times text-red-600"></i>
                                </div>
                                <div class="bg-white p-4 rounded-lg border border-red-200 flex items-center justify-between">
                                    <div class="flex items-center">
                                        <span class="font-mono bg-red-600 text-white px-3 py-1 rounded-lg font-bold text-lg">401</span>
                                        <div class="ml-4">
                                            <p class="font-semibold text-red-800">Unauthorized</p>
                                            <p class="text-red-700 text-sm">Invalid API credentials</p>
                                        </div>
                                    </div>
                                    <i class="fa-solid fa-lock text-red-600"></i>
                                </div>
                                <div class="bg-white p-4 rounded-lg border border-red-200 flex items-center justify-between">
                                    <div class="flex items-center">
                                        <span class="font-mono bg-red-600 text-white px-3 py-1 rounded-lg font-bold text-lg">422</span>
                                        <div class="ml-4">
                                            <p class="font-semibold text-red-800">Validation Error</p>
                                            <p class="text-red-700 text-sm">Request data validation failed</p>
                                        </div>
                                    </div>
                                    <i class="fa-solid fa-exclamation-triangle text-red-600"></i>
                                </div>
                                <div class="bg-white p-4 rounded-lg border border-red-200 flex items-center justify-between">
                                    <div class="flex items-center">
                                        <span class="font-mono bg-red-600 text-white px-3 py-1 rounded-lg font-bold text-lg">500</span>
                                        <div class="ml-4">
                                            <p class="font-semibold text-red-800">Internal Server Error</p>
                                            <p class="text-red-700 text-sm">Unexpected server error</p>
                                        </div>
                                    </div>
                                    <i class="fa-solid fa-server text-red-600"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Get Started CTA -->
            <section class="text-center py-16" data-aos="fade-up">
                <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl p-12 text-white">
                    <h2 class="text-4xl font-bold mb-4">Ready to Get Started?</h2>
                    <p class="text-xl opacity-90 mb-8 max-w-2xl mx-auto">
                        Join thousands of developers who trust our payment gateway API for their applications.
                    </p>
                    <div class="space-x-4">
                        <a href="{{ route('client-registration') }}" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition duration-300 inline-flex items-center">
                            <i class="fa-solid fa-user-plus mr-2"></i>
                            Create Account
                        </a>
                        <a href="{{ route('frontend.contact-us') }}" class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition duration-300 inline-flex items-center">
                            <i class="fa-solid fa-headset mr-2"></i>
                            Contact Support
                        </a>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
@endsection

@section('post-script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Copy to clipboard functionality
    const copyButtons = document.querySelectorAll('.copy-btn');
    
    copyButtons.forEach(button => {
        button.addEventListener('click', function() {
            const copyType = this.getAttribute('data-copy');
            let textToCopy = '';
            
            switch(copyType) {
                case 'headers':
                    textToCopy = document.getElementById('headers-example').textContent;
                    break;
                case 'curl':
                    textToCopy = document.getElementById('curl-example').textContent;
                    break;
                case 'response':
                    textToCopy = document.getElementById('response-example').textContent;
                    break;
                case 'curl-details':
                    textToCopy = document.getElementById('curl-details-example').textContent;
                    break;
                case 'details-response':
                    textToCopy = document.getElementById('details-response-example').textContent;
                    break;
                case 'webhook-headers':
                    textToCopy = document.getElementById('webhook-headers-example').textContent;
                    break;
                case 'webhook-payload':
                    textToCopy = document.getElementById('webhook-payload-example').textContent;
                    break;
            }
            
            // Copy to clipboard
            navigator.clipboard.writeText(textToCopy).then(() => {
                // Show success feedback
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="fa-solid fa-check mr-1"></i>Copied!';
                this.classList.add('bg-green-600', 'text-white');
                
                setTimeout(() => {
                    this.innerHTML = originalText;
                    this.classList.remove('bg-green-600', 'text-white');
                }, 2000);
            }).catch(err => {
                console.error('Failed to copy text: ', err);
            });
        });
    });
    
    // Smooth scrolling for navigation
    const navLinks = document.querySelectorAll('a[href^="#"]');
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href').substring(1);
            const targetElement = document.getElementById(targetId);
            
            if (targetElement) {
                const offsetTop = targetElement.offsetTop - 100; // Account for sticky nav
                window.scrollTo({
                    top: offsetTop,
                    behavior: 'smooth'
                });
            }
        });
    });
    
    // Active navigation highlighting
    window.addEventListener('scroll', function() {
        const sections = document.querySelectorAll('section[id]');
        const navPills = document.querySelectorAll('.nav-pill');
        
        let currentSection = '';
        sections.forEach(section => {
            const sectionTop = section.offsetTop - 120;
            const sectionHeight = section.offsetHeight;
            if (window.pageYOffset >= sectionTop && window.pageYOffset < sectionTop + sectionHeight) {
                currentSection = section.getAttribute('id');
            }
        });
        
        navPills.forEach(pill => {
            pill.classList.remove('text-blue-600', 'bg-blue-50');
            pill.classList.add('text-gray-600');
            
            if (pill.getAttribute('href') === '#' + currentSection) {
                pill.classList.remove('text-gray-600');
                pill.classList.add('text-blue-600', 'bg-blue-50');
            }
        });
    });
    
    // Initialize AOS
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 800,
            easing: 'ease-out-cubic',
            once: true,
            offset: 50
        });
    }
});
</script>
@endsection
