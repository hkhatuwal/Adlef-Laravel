@extends('_partials.app',['title' => config('app.name').' | Terms of Use','description' => 'ADLEF GROUP Terms of Use - Read our legally binding agreement governing your access to and use of our cryptocurrency payment gateway, digital asset on-ramp/off-ramp, and related services.','image' => asset('assets/images/office.webp')])

@section('content')

    <!-- Terms of Use Header -->
    <section class="bg-gray-50 py-16">
        <div class="container px-4 md:px-10 mx-auto">
            <h1 class="text-5xl mb-6 font-visuletProLight">ADLEF GROUP - <span class="text-black font-bold">Terms of Use</span></h1>
            <p class="text-lg text-gray-600">Last Updated: 10th August 2025</p>
        </div>
    </section>

    <!-- Acceptance of Terms -->
    <section class="py-16">
        <div class="container px-4 md:px-10 mx-auto">
            <h2 class="text-4xl font-bold mb-8">1. ACCEPTANCE OF TERMS</h2>
            <div class="prose max-w-none font-visuletProLight">
                <p class="text-lg leading-relaxed mb-6">
                    These Terms of Use ("Terms") constitute a legally binding agreement between you ("User," "you") and ADLEF NETWORK LLC ("ADLEF GROUP," "we," "us," or "our") governing your access to and use of our cryptocurrency payment gateway, digital asset on-ramp/off-ramp, and related services (collectively, the "Services") available through <a href="https://www.adlefgroup.com" class="text-blue-600 hover:underline">www.adlefgroup.com</a> and associated platforms.
                </p>
                <div class="p-6 bg-yellow-50 border-l-4 border-yellow-400 rounded">
                    <p class="text-lg font-medium text-yellow-800">
                        By accessing, registering for, or using our Services, you acknowledge that you have read, understood, and agree to be bound by these Terms in their entirety. If you do not agree to these Terms, you must immediately cease all use of our Services.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Eligibility and Registration -->
    <section class="py-16 bg-gray-50">
        <div class="container px-4 md:px-10 mx-auto">
            <h2 class="text-4xl font-bold mb-8">2. ELIGIBILITY AND REGISTRATION</h2>

            <!-- Eligibility Requirements -->
            <div class="mb-12">
                <h3 class="text-2xl font-semibold mb-6">2.1 Eligibility Requirements</h3>
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <p class="text-lg leading-relaxed mb-4">To use our Services, you must:</p>
                    <ul class="list-disc list-inside space-y-2 text-lg">
                        <li>Be at least 18 years of age or the legal age of majority in your jurisdiction</li>
                        <li>Not be a resident of or located in any jurisdiction where our Services are prohibited (including but not limited to Cuba, Iran, North Korea, Syria, Crimea, and other restricted territories)</li>
                        <li>Not appear on any sanctions lists (e.g., OFAC, UN, EU)</li>
                        <li>Have full legal capacity to enter into binding agreements</li>
                    </ul>
                </div>
            </div>

            <!-- Account Registration -->
            <div>
                <h3 class="text-2xl font-semibold mb-6">2.2 Account Registration</h3>
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <p class="text-lg leading-relaxed mb-4">To access certain Services, you must complete our registration process which includes:</p>
                    <ul class="list-disc list-inside space-y-2 text-lg mb-6">
                        <li>Providing accurate and current personal information</li>
                        <li>Completing identity verification (KYC) and anti-money laundering (AML) checks</li>
                        <li>Maintaining the security of your account credentials</li>
                        <li>Immediately notifying us of any unauthorized account activity</li>
                    </ul>
                    <p class="text-lg font-medium text-gray-700">
                        We reserve the right to refuse service to anyone at our sole discretion.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Description -->
    <section class="py-16">
        <div class="container px-4 md:px-10 mx-auto">
            <h2 class="text-4xl font-bold mb-8">3. SERVICES DESCRIPTION</h2>

            <!-- Service Offerings -->
            <div class="mb-12">
                <h3 class="text-2xl font-semibold mb-6">3.1 Service Offerings</h3>
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <p class="text-lg leading-relaxed mb-4">ADLEF GROUP provides:</p>
                    <ul class="list-disc list-inside space-y-2 text-lg">
                        <li>Cryptocurrency on-ramp services (fiat-to-crypto conversions)</li>
                        <li>Cryptocurrency off-ramp services (crypto-to-fiat conversions)</li>
                        <li>Payment processing solutions for merchants</li>
                        <li>Digital wallet services (non-custodial)</li>
                        <li>Related financial technology services</li>
                    </ul>
                </div>
            </div>

            <!-- Service Limitations -->
            <div>
                <h3 class="text-2xl font-semibold mb-6">3.2 Service Limitations</h3>
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <p class="text-lg leading-relaxed mb-4">Our Services are subject to:</p>
                    <ul class="list-disc list-inside space-y-2 text-lg">
                        <li>Daily and monthly transaction limits based on verification level</li>
                        <li>Geographic restrictions</li>
                        <li>Cryptocurrency network conditions</li>
                        <li>Regulatory requirements</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- User Obligations and Restrictions -->
    <section class="py-16 bg-gray-50">
        <div class="container px-4 md:px-10 mx-auto">
            <h2 class="text-4xl font-bold mb-8">4. USER OBLIGATIONS AND RESTRICTIONS</h2>

            <!-- Permitted Use -->
            <div class="mb-12">
                <h3 class="text-2xl font-semibold mb-6">4.1 Permitted Use</h3>
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <p class="text-lg leading-relaxed">
                        You may use our Services only for lawful purposes and in accordance with these Terms.
                    </p>
                </div>
            </div>

            <!-- Prohibited Activities -->
            <div>
                <h3 class="text-2xl font-semibold mb-6">4.2 Prohibited Activities</h3>
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <p class="text-lg leading-relaxed mb-4">You expressly agree not to:</p>
                    <ul class="list-disc list-inside space-y-2 text-lg">
                        <li>Engage in any illegal activities including money laundering, terrorist financing, or fraud</li>
                        <li>Attempt to circumvent our security, verification, or geographic restriction systems</li>
                        <li>Use our Services to transmit or store malware</li>
                        <li>Engage in market manipulation or abusive trading practices</li>
                        <li>Reverse engineer, decompile, or disassemble any aspect of our Services</li>
                        <li>Use our Services to transact in prohibited cryptocurrencies (including but not limited to privacy coins)</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Fees and Payments -->
    <section class="py-16">
        <div class="container px-4 md:px-10 mx-auto">
            <h2 class="text-4xl font-bold mb-8">5. FEES AND PAYMENTS</h2>

            <!-- Fee Structure -->
            <div class="mb-12">
                <h3 class="text-2xl font-semibold mb-6">5.1 Fee Structure</h3>
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <p class="text-lg leading-relaxed mb-4">Our fee schedule includes:</p>
                    <ul class="list-disc list-inside space-y-2 text-lg mb-6">
                        <li>Transaction fees (percentage-based and/or flat fees)</li>
                        <li>Network/miner fees for blockchain transactions</li>
                        <li>Foreign exchange spreads when applicable</li>
                        <li>Third-party processing fees</li>
                    </ul>
                    <p class="text-lg leading-relaxed">
                        All applicable fees will be clearly disclosed prior to transaction confirmation. Fees are subject to change with notice.
                    </p>
                </div>
            </div>

            <!-- Payment Methods -->
            <div>
                <h3 class="text-2xl font-semibold mb-6">5.2 Payment Methods</h3>
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <p class="text-lg leading-relaxed mb-4">We accept various payment methods including but not limited to:</p>
                    <ul class="list-disc list-inside space-y-2 text-lg">
                        <li>Bank transfers (ACH, SEPA, SWIFT)</li>
                        <li>Credit/debit card payments</li>
                        <li>Other electronic payment methods</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Intellectual Property Rights -->
    <section class="py-16 bg-gray-50">
        <div class="container px-4 md:px-10 mx-auto">
            <h2 class="text-4xl font-bold mb-8">6. INTELLECTUAL PROPERTY RIGHTS</h2>
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <p class="text-lg leading-relaxed">
                    All content, trademarks, service marks, logos, and software comprising the Services are the exclusive property of ADLEF GROUP or its licensors. You are granted a limited, non-exclusive, non-transferable license to access and use the Services for their intended purposes only.
                </p>
            </div>
        </div>
    </section>

    <!-- Disclaimers and Limitation of Liability -->
    <section class="py-16">
        <div class="container px-4 md:px-10 mx-auto">
            <h2 class="text-4xl font-bold mb-8">7. DISCLAIMERS AND LIMITATION OF LIABILITY</h2>

            <!-- No Warranty -->
            <div class="mb-12">
                <h3 class="text-2xl font-semibold mb-6">7.1 No Warranty</h3>
                <div class="bg-red-50 p-6 rounded-lg shadow-sm border-l-4 border-red-400">
                    <p class="text-lg font-semibold text-red-800 uppercase">
                        THE SERVICES ARE PROVIDED "AS IS" AND "AS AVAILABLE" WITHOUT WARRANTIES OF ANY KIND, EITHER EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO IMPLIED WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE, AND NON-INFRINGEMENT.
                    </p>
                </div>
            </div>

            <!-- Limitation of Liability -->
            <div>
                <h3 class="text-2xl font-semibold mb-6">7.2 Limitation of Liability</h3>
                <div class="bg-red-50 p-6 rounded-lg shadow-sm border-l-4 border-red-400">
                    <p class="text-lg font-semibold text-red-800 mb-4 uppercase">
                        TO THE MAXIMUM EXTENT PERMITTED BY APPLICABLE LAW, ADLEF GROUP SHALL NOT BE LIABLE FOR:
                    </p>
                    <ul class="list-disc list-inside space-y-2 text-lg text-red-800">
                        <li>ANY INDIRECT, INCIDENTAL, SPECIAL, CONSEQUENTIAL, OR PUNITIVE DAMAGES</li>
                        <li>LOSS OF PROFITS, DATA, USE, GOODWILL, OR OTHER INTANGIBLE LOSSES</li>
                        <li>DAMAGES RESULTING FROM UNAUTHORIZED ACCESS TO OR USE OF OUR SYSTEMS</li>
                        <li>CRYPTOCURRENCY MARKET VOLATILITY OR VALUE FLUCTUATIONS</li>
                        <li>DELAYS OR FAILURES CAUSED BY THIRD-PARTY SERVICE PROVIDERS OR BLOCKCHAIN NETWORKS</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Indemnification -->
    <section class="py-16 bg-gray-50">
        <div class="container px-4 md:px-10 mx-auto">
            <h2 class="text-4xl font-bold mb-8">8. INDEMNIFICATION</h2>
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <p class="text-lg leading-relaxed mb-4">
                    You agree to defend, indemnify, and hold harmless ADLEF GROUP, its affiliates, officers, directors, employees, and agents from and against any claims, liabilities, damages, losses, and expenses arising from:
                </p>
                <ul class="list-disc list-inside space-y-2 text-lg">
                    <li>Your use of the Services</li>
                    <li>Your violation of these Terms</li>
                    <li>Your violation of any applicable laws or regulations</li>
                    <li>Your infringement of any third-party rights</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- Termination -->
    <section class="py-16">
        <div class="container px-4 md:px-10 mx-auto">
            <h2 class="text-4xl font-bold mb-8">9. TERMINATION</h2>

            <!-- By User -->
            <div class="mb-12">
                <h3 class="text-2xl font-semibold mb-6">9.1 By User</h3>
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <p class="text-lg leading-relaxed">
                        You may terminate your account at any time by contacting customer support.
                    </p>
                </div>
            </div>

            <!-- By ADLEF GROUP -->
            <div>
                <h3 class="text-2xl font-semibold mb-6">9.2 By ADLEF GROUP</h3>
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <p class="text-lg leading-relaxed mb-4">
                        We may suspend or terminate your access to the Services immediately without notice if:
                    </p>
                    <ul class="list-disc list-inside space-y-2 text-lg">
                        <li>You violate these Terms</li>
                        <li>We suspect fraudulent or illegal activity</li>
                        <li>Required by law or regulatory authority</li>
                        <li>We discontinue the Services</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Governing Law and Dispute Resolution -->
    <section class="py-16 bg-gray-50">
        <div class="container px-4 md:px-10 mx-auto">
            <h2 class="text-4xl font-bold mb-8">10. GOVERNING LAW AND DISPUTE RESOLUTION</h2>

            <!-- Governing Law -->
            <div class="mb-12">
                <h3 class="text-2xl font-semibold mb-6">10.1 Governing Law</h3>
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <p class="text-lg leading-relaxed">
                        These Terms shall be governed by and construed in accordance with the laws of Georgia, without regard to its conflict of law provisions.
                    </p>
                </div>
            </div>

            <!-- Dispute Resolution -->
            <div>
                <h3 class="text-2xl font-semibold mb-6">10.2 Dispute Resolution</h3>
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <ul class="list-disc list-inside space-y-3 text-lg">
                        <li><strong>For Users in the European Union:</strong> Disputes may be brought before the courts of your country of residence or Georgia, at your discretion.</li>
                        <li><strong>For Users in the United States:</strong> All claims and disputes shall be resolved by binding arbitration administered by the American Arbitration Association (AAA) under its Commercial Arbitration Rules.</li>
                        <li><strong>For Users in other jurisdictions:</strong> Disputes shall be resolved through binding arbitration in Georgia under the rules of the Georgian Arbitration Association.</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Miscellaneous -->
    <section class="py-16">
        <div class="container px-4 md:px-10 mx-auto">
            <h2 class="text-4xl font-bold mb-8">11. MISCELLANEOUS</h2>

            <div class="space-y-8">
                <!-- Amendments -->
                <div>
                    <h3 class="text-2xl font-semibold mb-4">11.1 Amendments</h3>
                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <p class="text-lg leading-relaxed">
                            We reserve the right to modify these Terms at any time. Material changes will be communicated via email or through our website.
                        </p>
                    </div>
                </div>

                <!-- Force Majeure -->
                <div>
                    <h3 class="text-2xl font-semibold mb-4">11.2 Force Majeure</h3>
                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <p class="text-lg leading-relaxed">
                            We shall not be liable for any failure or delay in performance due to events beyond our reasonable control.
                        </p>
                    </div>
                </div>

                <!-- Severability -->
                <div>
                    <h3 class="text-2xl font-semibold mb-4">11.3 Severability</h3>
                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <p class="text-lg leading-relaxed">
                            If any provision of these Terms is found to be invalid or unenforceable, the remaining provisions shall remain in full force and effect.
                        </p>
                    </div>
                </div>

                <!-- Entire Agreement -->
                <div>
                    <h3 class="text-2xl font-semibold mb-4">11.4 Entire Agreement</h3>
                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <p class="text-lg leading-relaxed">
                            These Terms constitute the entire agreement between you and ADLEF GROUP regarding the Services.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Information -->
    <section class="py-16 bg-gray-50">
        <div class="container px-4 md:px-10 mx-auto">
            <h2 class="text-4xl font-bold mb-8">12. CONTACT INFORMATION</h2>
            <div class="bg-white p-8 rounded-lg shadow-sm">
                <p class="text-lg leading-relaxed mb-6">For questions about these Terms, please contact:</p>
                <ul class="space-y-3 text-lg">
                    <li><strong>Legal Department:</strong> <a href="mailto:Legal@adlefgroup.com" class="text-blue-600 hover:underline">Legal@adlefgroup.com</a></li>
                    <li><strong>Registered Address:</strong> Office Space N3, N31g, Meskheti Str, Borjomi City, Georgia</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <section class="py-8 bg-black text-white">
        <div class="container px-4 md:px-10 mx-auto text-center">
            <p class="text-lg">© {{ date('Y') }} ADLEF NETWORK LLC. ALL RIGHTS RESERVED.</p>
        </div>
    </section>

@endsection


