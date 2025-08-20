@extends('_partials.app',['title' => config('app.name').' | Privacy Policy','description' => 'ADLEF GROUP Privacy Policy - Learn how we collect, use, disclose, and safeguard your information when you use our payment gateway, digital asset on-ramp/off-ramp, and crypto payment services.','image' => asset('assets/images/office.webp')])

@section('content')

    <!-- Privacy Policy Header -->
    <section class="bg-gray-50 py-16">
        <div class="container px-4 md:px-10 mx-auto">
            <h1 class="text-5xl mb-6 font-visuletProLight">ADLEF GROUP - <span class="text-black font-bold">Privacy Policy</span></h1>
            <p class="text-lg text-gray-600">Last Updated: 14th August 2025</p>
        </div>
    </section>

    <!-- Introduction -->
    <section class="py-16">
        <div class="container px-4 md:px-10 mx-auto">
            <div class="prose max-w-none font-visuletProLight">
                <p class="text-lg leading-relaxed mb-8">
                    ADLEF NETWORK LLC ("ADLEF GROUP," "we," "us," or "our") is committed to protecting your privacy. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you use our payment gateway, digital asset on-ramp/off-ramp, and crypto payment services (collectively, the "Services") via our website <a href="https://www.adlefgroup.com" class="text-blue-600 hover:underline">www.adlefgroup.com</a>.
                </p>
                <p class="text-lg leading-relaxed mb-8">
                    By using our Services, you consent to the practices described in this policy.
                </p>
            </div>
        </div>
    </section>

    <!-- Controller & Contact Information -->
    <section class="py-16 bg-gray-50">
        <div class="container px-4 md:px-10 mx-auto">
            <h2 class="text-4xl font-bold mb-8">1. CONTROLLER & CONTACT INFORMATION</h2>
            <div class="bg-white p-8 rounded-lg shadow-sm">
                <h3 class="text-2xl font-semibold mb-6">Data Controller:</h3>
                <div class="space-y-4">
                    <p class="text-lg"><strong>ADLEF NETWORK LLC</strong></p>
                    <ul class="space-y-2 text-lg">
                        <li><strong>License No.:</strong> 426125212 (Money Service License)</li>
                        <li><strong>Address:</strong> Office Space N3, N31g, Meskheti Str, Borjomi City, Georgia</li>
                        <li><strong>Email:</strong> <a href="mailto:Contact@adlefgroup.com" class="text-blue-600 hover:underline">Contact@adlefgroup.com</a></li>
                        <li><strong>Legal Inquiries:</strong> <a href="mailto:Legal@adlefgroup.com" class="text-blue-600 hover:underline">Legal@adlefgroup.com</a></li>
                        <li><strong>Privacy Requests:</strong> <a href="mailto:Privacy@adlefgroup.com" class="text-blue-600 hover:underline">Privacy@adlefgroup.com</a></li>
                        <li><strong>Data Protection Officer (DPO):</strong> <a href="mailto:dpo@adlefgroup.com" class="text-blue-600 hover:underline">dpo@adlefgroup.com</a></li>
                    </ul>
                    <p class="text-lg mt-4">
                        For EU/UK users, our GDPR Representative: ANI BAIK, <a href="mailto:anibaik@gdpr-info.eu" class="text-blue-600 hover:underline">anibaik@gdpr-info.eu</a>
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Changes to Policy -->
    <section class="py-16">
        <div class="container px-4 md:px-10 mx-auto">
            <h2 class="text-4xl font-bold mb-8">2. CHANGES TO THIS POLICY</h2>
            <div class="prose max-w-none">
                <p class="text-lg leading-relaxed mb-4">
                    We may update this Privacy Policy periodically. Material changes (e.g., processing purposes, controller identity, or rights exercise methods) will be:
                </p>
                <ul class="list-disc list-inside space-y-2 text-lg mb-4">
                    <li>Posted on our website with a revised "Last Updated" date.</li>
                    <li>Communicated via email (if we have your contact details).</li>
                </ul>
                <p class="text-lg leading-relaxed">
                    Your continued use of our Services constitutes acceptance of the updated policy.
                </p>
            </div>
        </div>
    </section>

    <!-- Personal Data Collection -->
    <section class="py-16 bg-gray-50">
        <div class="container px-4 md:px-10 mx-auto">
            <h2 class="text-4xl font-bold mb-8">3. PERSONAL DATA WE COLLECT</h2>
            <p class="text-lg leading-relaxed mb-8">
                We collect data necessary to provide our Services, comply with laws (e.g., AML/KYC), and prevent fraud.
            </p>

            <!-- Data You Provide -->
            <div class="mb-12">
                <h3 class="text-2xl font-semibold mb-6">A. Data You Provide</h3>
                <div class="overflow-x-auto">
                    <table class="w-full bg-white rounded-lg shadow-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-6 py-4 text-left text-lg font-semibold">Category</th>
                                <th class="px-6 py-4 text-left text-lg font-semibold">Examples</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr>
                                <td class="px-6 py-4 font-medium">Identity Verification</td>
                                <td class="px-6 py-4">Full name, date of birth, nationality, government-issued ID (passport, driver's license), selfie/video for biometric verification.</td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 font-medium">Financial Information</td>
                                <td class="px-6 py-4">Bank account details, payment card info (PAN), transaction history, cryptocurrency wallet addresses.</td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 font-medium">Contact Details</td>
                                <td class="px-6 py-4">Email, phone number, residential address.</td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 font-medium">Institutional Data</td>
                                <td class="px-6 py-4">Employer Identification Number (EIN), business registration documents, beneficial ownership details (for corporate clients).</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Data Collected Automatically -->
            <div class="mb-12">
                <h3 class="text-2xl font-semibold mb-6">B. Data Collected Automatically</h3>
                <div class="overflow-x-auto">
                    <table class="w-full bg-white rounded-lg shadow-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-6 py-4 text-left text-lg font-semibold">Category</th>
                                <th class="px-6 py-4 text-left text-lg font-semibold">Examples</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr>
                                <td class="px-6 py-4 font-medium">Device/Technical Data</td>
                                <td class="px-6 py-4">IP address, browser type, operating system, device identifiers.</td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 font-medium">Usage Data</td>
                                <td class="px-6 py-4">Pages visited, session duration, transaction timestamps, clickstream data.</td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 font-medium">Blockchain Data</td>
                                <td class="px-6 py-4">Public wallet addresses, transaction hashes (for fraud monitoring).</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Data from Third Parties -->
            <div>
                <h3 class="text-2xl font-semibold mb-6">C. Data from Third Parties</h3>
                <ul class="list-disc list-inside space-y-2 text-lg bg-white p-6 rounded-lg shadow-sm">
                    <li><strong>KYC/AML Providers:</strong> Identity verification, sanctions screening (e.g., SumSub, Jumio).</li>
                    <li><strong>Public Databases:</strong> Sanctions lists (e.g., OFAC, UN), corporate registries.</li>
                    <li><strong>Payment Processors:</strong> Bank/fiat transaction confirmations.</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- How We Use Your Data -->
    <section class="py-16">
        <div class="container px-4 md:px-10 mx-auto">
            <h2 class="text-4xl font-bold mb-8">4. HOW WE USE YOUR DATA</h2>
            <div class="overflow-x-auto">
                <table class="w-full bg-white rounded-lg shadow-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-lg font-semibold">Purpose</th>
                            <th class="px-6 py-4 text-left text-lg font-semibold">Legal Basis (GDPR)</th>
                            <th class="px-6 py-4 text-left text-lg font-semibold">Data Types Used</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr>
                            <td class="px-6 py-4">Account creation & verification</td>
                            <td class="px-6 py-4">Contractual necessity</td>
                            <td class="px-6 py-4">Identity, contact, financial data</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4">Transaction processing</td>
                            <td class="px-6 py-4">Contractual necessity</td>
                            <td class="px-6 py-4">Wallet addresses, payment details</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4">Fraud prevention & security</td>
                            <td class="px-6 py-4">Legitimate interest</td>
                            <td class="px-6 py-4">Device data, transaction history</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4">AML/CFT compliance</td>
                            <td class="px-6 py-4">Legal obligation</td>
                            <td class="px-6 py-4">ID documents, sanctions checks</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4">Marketing (opt-in only)</td>
                            <td class="px-6 py-4">Consent</td>
                            <td class="px-6 py-4">Email, preferences</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4">Customer support</td>
                            <td class="px-6 py-4">Contractual necessity</td>
                            <td class="px-6 py-4">Communications, account details</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Data Sharing -->
    <section class="py-16 bg-gray-50">
        <div class="container px-4 md:px-10 mx-auto">
            <h2 class="text-4xl font-bold mb-8">5. DATA SHARING & DISCLOSURES</h2>
            <p class="text-lg leading-relaxed mb-8">We may share data with:</p>
            
            <div class="space-y-6">
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h3 class="text-xl font-semibold mb-4">Regulators & Law Enforcement:</h3>
                    <p class="text-lg">To comply with legal requests (e.g., tax authorities, financial intelligence units).</p>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h3 class="text-xl font-semibold mb-4">Service Providers:</h3>
                    <ul class="list-disc list-inside space-y-2 text-lg">
                        <li><strong>KYC Providers:</strong> SumSub, Jumio.</li>
                        <li><strong>Payment Processors:</strong> Banks, card networks.</li>
                        <li><strong>Cloud Hosting:</strong> AWS, Google Cloud (encrypted storage).</li>
                    </ul>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h3 class="text-xl font-semibold mb-4">Affiliates/Partners:</h3>
                    <p class="text-lg">For operational support (e.g., customer service).</p>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h3 class="text-xl font-semibold mb-4">Business Transfers:</h3>
                    <p class="text-lg">In mergers/acquisitions (data anonymized where possible).</p>
                </div>
            </div>

            <div class="mt-8 p-6 bg-blue-50 rounded-lg">
                <p class="text-lg font-semibold text-blue-800">We do not sell personal data.</p>
            </div>
        </div>
    </section>

    <!-- International Data Transfers -->
    <section class="py-16">
        <div class="container px-4 md:px-10 mx-auto">
            <h2 class="text-4xl font-bold mb-8">6. INTERNATIONAL DATA TRANSFERS</h2>
            <div class="prose max-w-none">
                <p class="text-lg leading-relaxed mb-6">
                    Data may be transferred outside your jurisdiction (e.g., Georgia → EU/US). We use safeguards:
                </p>
                <ul class="list-disc list-inside space-y-2 text-lg">
                    <li><strong>EU/UK Transfers:</strong> Standard Contractual Clauses (SCCs) + UK Addendum.</li>
                    <li><strong>Other Regions:</strong> Adequacy decisions or contractual protections.</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- Data Retention -->
    <section class="py-16 bg-gray-50">
        <div class="container px-4 md:px-10 mx-auto">
            <h2 class="text-4xl font-bold mb-8">7. DATA RETENTION</h2>
            <div class="prose max-w-none">
                <p class="text-lg leading-relaxed mb-6">We retain data as required by:</p>
                <ul class="list-disc list-inside space-y-2 text-lg mb-6">
                    <li><strong>Legal Obligations:</strong> AML laws (typically 5–10 years).</li>
                    <li><strong>Business Needs:</strong> Transaction disputes, fraud investigations.</li>
                </ul>
                <p class="text-lg leading-relaxed">
                    After retention periods, data is anonymized or deleted.
                </p>
            </div>
        </div>
    </section>

    <!-- Cookies -->
    <section class="py-16">
        <div class="container px-4 md:px-10 mx-auto">
            <h2 class="text-4xl font-bold mb-8">8. COOKIES & TRACKING TECHNOLOGIES</h2>
            
            <div class="mb-12">
                <h3 class="text-2xl font-semibold mb-6">A. Types of Cookies</h3>
                <div class="overflow-x-auto">
                    <table class="w-full bg-white rounded-lg shadow-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-6 py-4 text-left text-lg font-semibold">Category</th>
                                <th class="px-6 py-4 text-left text-lg font-semibold">Purpose</th>
                                <th class="px-6 py-4 text-left text-lg font-semibold">Examples</th>
                                <th class="px-6 py-4 text-left text-lg font-semibold">Lifespan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr>
                                <td class="px-6 py-4 font-medium">Essential</td>
                                <td class="px-6 py-4">Site functionality (e.g., login sessions)</td>
                                <td class="px-6 py-4">Session cookies</td>
                                <td class="px-6 py-4">24 hours</td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 font-medium">Analytics</td>
                                <td class="px-6 py-4">Improve website performance</td>
                                <td class="px-6 py-4">Google Analytics (anonymized)</td>
                                <td class="px-6 py-4">14 months</td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 font-medium">Marketing</td>
                                <td class="px-6 py-4">Targeted ads (opt-in required)</td>
                                <td class="px-6 py-4">Meta Pixel</td>
                                <td class="px-6 py-4">90 days</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div>
                <h3 class="text-2xl font-semibold mb-6">B. Consent Management</h3>
                <ul class="list-disc list-inside space-y-2 text-lg bg-white p-6 rounded-lg shadow-sm">
                    <li><strong>EEA/UK Users:</strong> Non-essential cookies require prior consent (managed via cookie banner).</li>
                    <li><strong>Opt-Out:</strong> Adjust settings in our Cookie Preference Center or browser settings.</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- Your Rights -->
    <section class="py-16 bg-gray-50">
        <div class="container px-4 md:px-10 mx-auto">
            <h2 class="text-4xl font-bold mb-8">9. YOUR RIGHTS</h2>
            <div class="prose max-w-none">
                <p class="text-lg leading-relaxed mb-6">
                    Depending on jurisdiction (e.g., GDPR, CCPA), you may:
                </p>
                <ul class="list-disc list-inside space-y-2 text-lg mb-8">
                    <li>Access, Correct, or Delete your data.</li>
                    <li>Withdraw Consent (for marketing).</li>
                    <li>Object to Processing (e.g., profiling).</li>
                    <li>Data Portability (request a machine-readable copy).</li>
                </ul>

                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h3 class="text-xl font-semibold mb-4">To Exercise Rights:</h3>
                    <p class="text-lg">
                        Email <a href="mailto:Privacy@adlefgroup.com" class="text-blue-600 hover:underline">Privacy@adlefgroup.com</a>. We respond within 30 days and may request identity verification.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Security Measures -->
    <section class="py-16">
        <div class="container px-4 md:px-10 mx-auto">
            <h2 class="text-4xl font-bold mb-8">10. SECURITY MEASURES</h2>
            <div class="prose max-w-none">
                <p class="text-lg leading-relaxed mb-6">We implement:</p>
                <ul class="list-disc list-inside space-y-2 text-lg mb-6">
                    <li><strong>Encryption:</strong> TLS 1.2+ for data in transit, AES-256 for storage.</li>
                    <li><strong>Access Controls:</strong> Role-based permissions, 2FA for employees.</li>
                    <li><strong>Audits:</strong> Regular penetration testing.</li>
                </ul>
                <p class="text-lg">
                    Report Security Issues: <a href="mailto:security@adlefgroup.com" class="text-blue-600 hover:underline">security@adlefgroup.com</a>.
                </p>
            </div>
        </div>
    </section>

    <!-- Children's Privacy -->
    <section class="py-16 bg-gray-50">
        <div class="container px-4 md:px-10 mx-auto">
            <h2 class="text-4xl font-bold mb-8">11. CHILDREN'S PRIVACY</h2>
            <p class="text-lg leading-relaxed">
                Our Services are not for users under 18. We do not knowingly collect their data.
            </p>
        </div>
    </section>

    <!-- Third-Party Links -->
    <section class="py-16">
        <div class="container px-4 md:px-10 mx-auto">
            <h2 class="text-4xl font-bold mb-8">12. THIRD-PARTY LINKS</h2>
            <p class="text-lg leading-relaxed">
                Our website may link to external sites (e.g., partners). We are not responsible for their privacy practices.
            </p>
        </div>
    </section>

    <!-- Contact Us -->
    <section class="py-16 bg-gray-50">
        <div class="container px-4 md:px-10 mx-auto">
            <h2 class="text-4xl font-bold mb-8">13. CONTACT US</h2>
            <div class="bg-white p-8 rounded-lg shadow-sm">
                <p class="text-lg leading-relaxed mb-6">For questions or complaints:</p>
                <ul class="space-y-2 text-lg mb-8">
                    <li><strong>Email:</strong> <a href="mailto:Privacy@adlefgroup.com" class="text-blue-600 hover:underline">Privacy@adlefgroup.com</a></li>
                    <li><strong>Postal Address:</strong> Office Space N3, N31g, Meskheti Str, Borjomi City, Georgia</li>
                    <li><strong>DPO:</strong> <a href="mailto:dpo@adlefgroup.com" class="text-blue-600 hover:underline">dpo@adlefgroup.com</a></li>
                </ul>

                <div>
                    <h3 class="text-xl font-semibold mb-4">EU/UK Supervisory Authorities:</h3>
                    <ul class="space-y-2 text-lg">
                        <li><strong>EEA:</strong> <a href="#" class="text-blue-600 hover:underline">List of DPAs</a></li>
                        <li><strong>UK:</strong> <a href="#" class="text-blue-600 hover:underline">ICO</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <section class="py-8 bg-black text-white">
        <div class="container px-4 md:px-10 mx-auto text-center">
            <p class="text-lg">© ADLEF NETWORK LLC | <a href="https://www.adlefgroup.com" class="text-blue-400 hover:underline">www.adlefgroup.com</a></p>
        </div>
    </section>

@endsection

