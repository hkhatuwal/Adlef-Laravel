@extends('_partials.app',['title' => config('app.name').' | Cookie Policy','description' => 'ADLEF GROUP Cookie Policy - Learn how we use cookies to ensure proper functionality, enhance your experience, and analyze site usage on our website.','image' => asset('assets/images/office.webp')])

@section('content')

    <!-- Cookie Policy Header -->
    <section class="bg-gray-50 py-16">
        <div class="container px-4 md:px-10 mx-auto">
            <h1 class="text-5xl mb-6 font-visuletProLight">ADLEF GROUP - <span class="text-black font-bold">Cookie Policy</span></h1>
            <p class="text-lg text-gray-600">Last Updated: [Insert Date]</p>
        </div>
    </section>

    <!-- What Are Cookies -->
    <section class="py-16">
        <div class="container px-4 md:px-10 mx-auto">
            <h2 class="text-4xl font-bold mb-8">1. What Are Cookies?</h2>
            <div class="prose max-w-none font-visuletProLight">
                <p class="text-lg leading-relaxed mb-6">
                    Our website, <a href="https://www.adlefgroup.com" class="text-blue-600 hover:underline">www.adlefgroup.com</a>, uses cookies—small text files stored on your device—to ensure proper functionality, enhance your experience, and analyze site usage. Cookies allow us to recognize your device, remember preferences, and track interactions (e.g., logins, transactions).
                </p>
                <p class="text-lg leading-relaxed">
                    For general information about cookies, visit <a href="https://www.allaboutcookies.org" class="text-blue-600 hover:underline">All About Cookies</a>.
                </p>
            </div>
        </div>
    </section>

    <!-- How We Use Cookies -->
    <section class="py-16 bg-gray-50">
        <div class="container px-4 md:px-10 mx-auto">
            <h2 class="text-4xl font-bold mb-8">2. How We Use Cookies</h2>
            <div class="prose max-w-none">
                <p class="text-lg leading-relaxed mb-6">We use cookies to:</p>
                <ul class="list-disc list-inside space-y-3 text-lg mb-6">
                    <li><strong>Operate our Services:</strong> Enable secure logins, transactions, and account management.</li>
                    <li><strong>Improve Performance:</strong> Analyze traffic and optimize site speed.</li>
                    <li><strong>Personalize Content:</strong> Remember language/currency preferences.</li>
                    <li><strong>Prevent Fraud:</strong> Monitor suspicious activity (e.g., repeated login attempts).</li>
                </ul>
                <div class="p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded">
                    <p class="text-lg font-medium text-yellow-800">
                        Disabling cookies may break certain features (e.g., checkout processes).
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Types of Cookies -->
    <section class="py-16">
        <div class="container px-4 md:px-10 mx-auto">
            <h2 class="text-4xl font-bold mb-8">3. Types of Cookies We Use</h2>
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
                            <td class="px-6 py-4">Required for core functionality (e.g., secure sessions, payments).</td>
                            <td class="px-6 py-4">Session IDs, CSRF tokens</td>
                            <td class="px-6 py-4">24 hours</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 font-medium">Analytics</td>
                            <td class="px-6 py-4">Anonymized tracking of site usage (e.g., page visits, bounce rates).</td>
                            <td class="px-6 py-4">Google Analytics (IP anonymized)</td>
                            <td class="px-6 py-4">14 months</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 font-medium">Preferences</td>
                            <td class="px-6 py-4">Remember user settings (e.g., language, dark mode).</td>
                            <td class="px-6 py-4">Local storage settings</td>
                            <td class="px-6 py-4">30 days</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 font-medium">Marketing</td>
                            <td class="px-6 py-4">Deliver targeted ads (only with opt-in consent for EEA/UK users).</td>
                            <td class="px-6 py-4">Meta Pixel, LinkedIn Insights</td>
                            <td class="px-6 py-4">90 days</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Third-Party Cookies -->
    <section class="py-16 bg-gray-50">
        <div class="container px-4 md:px-10 mx-auto">
            <h2 class="text-4xl font-bold mb-8">4. Third-Party Cookies</h2>
            <div class="prose max-w-none">
                <p class="text-lg leading-relaxed mb-6">We partner with trusted providers for:</p>
                <div class="space-y-4">
                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <h3 class="text-xl font-semibold mb-3">Analytics:</h3>
                        <p class="text-lg">Google Analytics (14 months), Hotjar (12 months).</p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <h3 class="text-xl font-semibold mb-3">Fraud Prevention:</h3>
                        <p class="text-lg">Chainalysis.</p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <h3 class="text-xl font-semibold mb-3">Advertising:</h3>
                        <p class="text-lg">Meta Pixel.</p>
                    </div>
                </div>
                <p class="text-lg leading-relaxed mt-6">
                    These parties may place cookies subject to their own policies.
                </p>
            </div>
        </div>
    </section>

    <!-- Your Cookie Choices -->
    <section class="py-16">
        <div class="container px-4 md:px-10 mx-auto">
            <h2 class="text-4xl font-bold mb-8">5. Your Cookie Choices</h2>
            
            <!-- Browser Settings -->
            <div class="mb-12">
                <h3 class="text-2xl font-semibold mb-6">A. Browser Settings</h3>
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <p class="text-lg leading-relaxed mb-4">You can block or delete cookies via your browser:</p>
                    <ul class="list-disc list-inside space-y-2 text-lg">
                        <li><strong>Chrome:</strong> Settings > Privacy > Clear browsing data</li>
                        <li><strong>Firefox:</strong> Options > Privacy & Security > Cookies</li>
                        <li><strong>Safari:</strong> Preferences > Privacy > Manage Website Data</li>
                    </ul>
                </div>
            </div>

            <!-- Consent Management -->
            <div>
                <h3 class="text-2xl font-semibold mb-6">B. Consent Management (EEA/UK Users)</h3>
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <ul class="list-disc list-inside space-y-2 text-lg">
                        <li>Use our Cookie Preference Center (<a href="#" class="text-blue-600 hover:underline">link</a>) to toggle non-essential cookies.</li>
                        <li>Essential cookies cannot be disabled without disrupting Services.</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Changes to This Policy -->
    <section class="py-16 bg-gray-50">
        <div class="container px-4 md:px-10 mx-auto">
            <h2 class="text-4xl font-bold mb-8">6. Changes to This Policy</h2>
            <div class="prose max-w-none">
                <p class="text-lg leading-relaxed">
                    We may update this policy to reflect technical or legal changes. Significant updates will be notified via email or website banners.
                </p>
            </div>
        </div>
    </section>

    <!-- Contact Us -->
    <section class="py-16">
        <div class="container px-4 md:px-10 mx-auto">
            <h2 class="text-4xl font-bold mb-8">7. Contact Us</h2>
            <div class="bg-white p-8 rounded-lg shadow-sm">
                <p class="text-lg leading-relaxed mb-6">For questions about cookies:</p>
                <ul class="space-y-3 text-lg">
                    <li><strong>Email:</strong> <a href="mailto:Privacy@adlefgroup.com" class="text-blue-600 hover:underline">Privacy@adlefgroup.com</a></li>
                    <li><strong>DPO:</strong> <a href="mailto:dpo@adlefgroup.com" class="text-blue-600 hover:underline">dpo@adlefgroup.com</a></li>
                </ul>
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
