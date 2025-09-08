@extends('_partials.app',['title' => config('app.name').' | FAQ','description' => 'ADLEF GROUP Frequently Asked Questions - Get answers to common questions about our cryptocurrency payment solutions, OTC trading, verification process, fees, and security measures.','image' => asset('assets/images/office.webp')])

@section('content')

    <!-- FAQ Header -->
    <section class="bg-gray-50 py-16">
        <div class="container px-4 md:px-10 mx-auto">
            <h1 class="text-5xl mb-6 font-visuletProLight">ADLEF GROUP - <span class="text-black font-bold">Frequently Asked Questions</span></h1>
            <p class="text-lg text-gray-600">Find answers to common questions about our services</p>
        </div>
    </section>

    <!-- General Questions -->
    <section class="py-16">
        <div class="container px-4 md:px-10 mx-auto">
            <h2 class="text-4xl font-bold mb-8">1. General Questions</h2>

            <div class="space-y-6">
                <!-- Q1: What is ADLEF GROUP? -->
                <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-blue-500">
                    <h3 class="text-xl font-semibold mb-4 text-blue-600">Q: What is ADLEF GROUP?</h3>
                    <p class="text-lg leading-relaxed text-gray-700">
                        <strong>A:</strong> ADLEF GROUP is a licensed financial technology company specializing in cryptocurrency payment solutions, OTC trading, and institutional digital asset services. We provide secure and compliant on-ramp/off-ramp services for businesses and individual investors.
                    </p>
                </div>

                <!-- Q2: Is ADLEF GROUP regulated? -->
                <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-blue-500">
                    <h3 class="text-xl font-semibold mb-4 text-blue-600">Q: Is ADLEF GROUP regulated?</h3>
                    <p class="text-lg leading-relaxed text-gray-700">
                        <strong>A:</strong> Yes, we are a fully licensed Money Service Business (MSB) and comply with AML/CFT regulations in multiple jurisdictions, including Georgia and other key markets.
                    </p>
                </div>

                <!-- Q3: Who can use ADLEF GROUP's services? -->
                <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-blue-500">
                    <h3 class="text-xl font-semibold mb-4 text-blue-600">Q: Who can use ADLEF GROUP's services?</h3>
                    <div class="text-lg leading-relaxed text-gray-700">
                        <p class="mb-3"><strong>A:</strong> Our services are available to:</p>
                        <ul class="list-disc list-inside space-y-2 ml-4">
                            <li>Retail users (individuals buying/selling crypto)</li>
                            <li>Businesses & institutions (merchants, exchanges, OTC desks)</li>
                            <li>Partners (payment processors, fintech platforms)</li>
                        </ul>
                        <p class="mt-3 font-medium text-orange-600">Restrictions apply in prohibited jurisdictions.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Account & Verification -->
    <section class="py-16 bg-gray-50">
        <div class="container px-4 md:px-10 mx-auto">
            <h2 class="text-4xl font-bold mb-8">2. Account & Verification</h2>

            <div class="space-y-6">
                <!-- Q1: Do I need to complete KYC? -->
                <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-green-500">
                    <h3 class="text-xl font-semibold mb-4 text-green-600">Q: Do I need to complete KYC?</h3>
                    <p class="text-lg leading-relaxed text-gray-700">
                        <strong>A:</strong> Yes. To comply with global regulations, we require identity verification (KYC) for all transactions.
                    </p>
                </div>

                <!-- Q2: What documents are needed for verification? -->
                <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-green-500">
                    <h3 class="text-xl font-semibold mb-4 text-green-600">Q: What documents are needed for verification?</h3>
                    <div class="text-lg leading-relaxed text-gray-700">
                        <p class="mb-3"><strong>A:</strong></p>
                        <ul class="list-disc list-inside space-y-2 ml-4">
                            <li><strong>Individuals:</strong> Government-issued ID (passport, driver's license) + proof of address</li>
                            <li><strong>Businesses:</strong> Certificate of incorporation, beneficial ownership details, and authorized signer IDs</li>
                        </ul>
                    </div>
                </div>

                <!-- Q3: How long does verification take? -->
                <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-green-500">
                    <h3 class="text-xl font-semibold mb-4 text-green-600">Q: How long does verification take?</h3>
                    <p class="text-lg leading-relaxed text-gray-700">
                        <strong>A:</strong> Most accounts are approved within 15-30 minutes, but complex cases may take up to 24 hours.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Transactions & Payments -->
    <section class="py-16">
        <div class="container px-4 md:px-10 mx-auto">
            <h2 class="text-4xl font-bold mb-8">3. Transactions & Payments</h2>

            <div class="space-y-6">
                <!-- Q1: What payment methods do you accept? -->
                <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-purple-500">
                    <h3 class="text-xl font-semibold mb-4 text-purple-600">Q: What payment methods do you accept?</h3>
                    <div class="text-lg leading-relaxed text-gray-700">
                        <p class="mb-3"><strong>A:</strong></p>
                        <ul class="list-disc list-inside space-y-2 ml-4">
                            <li>Bank transfers (SWIFT, SEPA, ACH)</li>
                            <li>Credit/debit cards (Visa, Mastercard)</li>
                            <li>Stablecoins (USDT, USDC)</li>
                            <li>OTC settlements (for large-volume trades)</li>
                        </ul>
                    </div>
                </div>

                <!-- Q2: Are there transaction limits? -->
                <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-purple-500">
                    <h3 class="text-xl font-semibold mb-4 text-purple-600">Q: Are there transaction limits?</h3>
                    <div class="text-lg leading-relaxed text-gray-700">
                        <p class="mb-3"><strong>A:</strong> Yes, limits vary based on:</p>
                        <ul class="list-disc list-inside space-y-2 ml-4">
                            <li>Account verification level</li>
                            <li>Payment method</li>
                            <li>Jurisdiction</li>
                        </ul>
                        <p class="mt-3 font-medium text-purple-600">Higher limits available for institutional clients.</p>
                    </div>
                </div>

                <!-- Q3: How long do transactions take? -->
                <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-purple-500">
                    <h3 class="text-xl font-semibold mb-4 text-purple-600">Q: How long do transactions take?</h3>
                    <div class="text-lg leading-relaxed text-gray-700">
                        <p class="mb-3"><strong>A:</strong></p>
                        <ul class="list-disc list-inside space-y-2 ml-4">
                            <li><strong>Crypto purchases:</strong> Instant (card) or 1-3 business days (bank transfer)</li>
                            <li><strong>Crypto sales:</strong> 1-5 business days (varies by banking partner)</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Security & Compliance -->
    <section class="py-16 bg-gray-50">
        <div class="container px-4 md:px-10 mx-auto">
            <h2 class="text-4xl font-bold mb-8">4. Security & Compliance</h2>

            <div class="space-y-6">
                <!-- Q1: Is ADLEF GROUP a custodial service? -->
                <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-red-500">
                    <h3 class="text-xl font-semibold mb-4 text-red-600">Q: Is ADLEF GROUP a custodial service?</h3>
                    <p class="text-lg leading-relaxed text-gray-700">
                        <strong>A:</strong> No. We are non-custodial—you retain full control of your crypto assets.
                    </p>
                </div>

                <!-- Q2: How does ADLEF GROUP protect my data? -->
                <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-red-500">
                    <h3 class="text-xl font-semibold mb-4 text-red-600">Q: How does ADLEF GROUP protect my data?</h3>
                    <div class="text-lg leading-relaxed text-gray-700">
                        <p class="mb-3"><strong>A:</strong> We use:</p>
                        <ul class="list-disc list-inside space-y-2 ml-4">
                            <li>Bank-grade encryption (AES-256, TLS 1.3)</li>
                            <li>PCI DSS-compliant payment processing</li>
                            <li>Multi-signature & cold storage for partner funds</li>
                        </ul>
                    </div>
                </div>

                <!-- Q3: What happens if I send funds to the wrong address? -->
                <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-red-500">
                    <h3 class="text-xl font-semibold mb-4 text-red-600">Q: What happens if I send funds to the wrong address?</h3>
                    <div class="text-lg leading-relaxed text-gray-700">
                        <p><strong>A:</strong> Blockchain transactions are irreversible. Always double-check wallet addresses before sending.</p>
                        <div class="mt-3 p-3 bg-yellow-50 border-l-4 border-yellow-400 rounded">
                            <p class="text-yellow-800 font-medium">⚠️ Important: Always verify addresses before confirming transactions!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Fees & Pricing -->
    <section class="py-16">
        <div class="container px-4 md:px-10 mx-auto">
            <h2 class="text-4xl font-bold mb-8">5. Fees & Pricing</h2>

            <div class="space-y-6">
                <!-- Q1: What are your fees? -->
                <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-orange-500">
                    <h3 class="text-xl font-semibold mb-4 text-orange-600">Q: What are your fees?</h3>
                    <div class="text-lg leading-relaxed text-gray-700">
                        <p class="mb-3"><strong>A:</strong> Fees depend on:</p>
                        <ul class="list-disc list-inside space-y-2 ml-4">
                            <li>Transaction type (buy/sell/trade)</li>
                            <li>Payment method (card fees are higher than bank transfers)</li>
                            <li>Volume discounts (for institutional clients)</li>
                        </ul>
                        <p class="mt-3 font-medium text-orange-600">All fees are displayed before confirmation.</p>
                    </div>
                </div>

                <!-- Q2: Are there hidden charges? -->
                <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-orange-500">
                    <h3 class="text-xl font-semibold mb-4 text-orange-600">Q: Are there hidden charges?</h3>
                    <div class="text-lg leading-relaxed text-gray-700">
                        <p class="mb-3"><strong>A:</strong> No. We disclose all fees upfront. Note:</p>
                        <ul class="list-disc list-inside space-y-2 ml-4">
                            <li>Bank/network fees may apply (outside our control)</li>
                            <li>Dynamic miner fees for blockchain transactions</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Institutional & OTC Services -->
    <section class="py-16 bg-gray-50">
        <div class="container px-4 md:px-10 mx-auto">
            <h2 class="text-4xl font-bold mb-8">6. Institutional & OTC Services</h2>

            <div class="space-y-6">
                <!-- Q1: What OTC services do you offer? -->
                <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-indigo-500">
                    <h3 class="text-xl font-semibold mb-4 text-indigo-600">Q: What OTC services do you offer?</h3>
                    <div class="text-lg leading-relaxed text-gray-700">
                        <p class="mb-3"><strong>A:</strong></p>
                        <ul class="list-disc list-inside space-y-2 ml-4">
                            <li>Large-volume trades (min. $50K)</li>
                            <li>Custom settlement solutions</li>
                            <li>24/7 dedicated support</li>
                        </ul>
                    </div>
                </div>

                <!-- Q2: How do I contact your institutional team? -->
                <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-indigo-500">
                    <h3 class="text-xl font-semibold mb-4 text-indigo-600">Q: How do I contact your institutional team?</h3>
                    <p class="text-lg leading-relaxed text-gray-700">
                        <strong>A:</strong> Email <a href="mailto:support@adlefgroup.com" class="text-indigo-600 hover:underline font-medium">support@adlefgroup.com</a> for tailored solutions.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Troubleshooting & Support -->
    <section class="py-16">
        <div class="container px-4 md:px-10 mx-auto">
            <h2 class="text-4xl font-bold mb-8">7. Troubleshooting & Support</h2>

            <div class="space-y-6">
                <!-- Q1: My transaction is stuck. What should I do? -->
                <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-teal-500">
                    <h3 class="text-xl font-semibold mb-4 text-teal-600">Q: My transaction is stuck. What should I do?</h3>
                    <div class="text-lg leading-relaxed text-gray-700">
                        <p class="mb-3"><strong>A:</strong></p>
                        <ol class="list-decimal list-inside space-y-2 ml-4">
                            <li>Check blockchain explorer for on-chain status</li>
                            <li>Contact support with your Transaction ID (TXID)</li>
                        </ol>
                    </div>
                </div>

                <!-- Q2: How do I contact customer support? -->
                <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-teal-500">
                    <h3 class="text-xl font-semibold mb-4 text-teal-600">Q: How do I contact customer support?</h3>
                    <div class="text-lg leading-relaxed text-gray-700">
                        <p class="mb-3"><strong>A:</strong></p>
                        <ul class="list-disc list-inside space-y-2 ml-4">
                            <li><strong>Email:</strong> <a href="mailto:support@adlefgroup.com" class="text-teal-600 hover:underline">support@adlefgroup.com</a></li>
                            <li><strong>Live Chat:</strong> Available on <a href="https://www.adlefgroup.com" class="text-teal-600 hover:underline">www.adlefgroup.com</a></li>
                            <li><strong>Response time:</strong> < 24 hours (usually faster)</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Need More Help -->
    <section class="py-16 bg-gray-50">
        <div class="container px-4 md:px-10 mx-auto text-center">
            <div class="bg-white p-8 rounded-lg shadow-sm max-w-2xl mx-auto">
                <h3 class="text-2xl font-bold mb-4">Need More Help?</h3>
                <p class="text-lg text-gray-700 mb-6">
                    Can't find the answer you're looking for? We're here to help!
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('frontend.help-center') }}" class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        Visit Help Center
                    </a>
                    <a href="{{ route('frontend.contact-us') }}" class="inline-flex items-center justify-center px-6 py-3 bg-gray-600 text-white font-medium rounded-lg hover:bg-gray-700 transition-colors">
                        Contact Us
                    </a>
                </div>
            </div>
        </div>
    </section>



@endsection


