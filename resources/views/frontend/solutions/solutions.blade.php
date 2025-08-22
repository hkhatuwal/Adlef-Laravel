@extends('_partials.app',['isDark'=>true,'title' => config('app.name').' | SOLUTIONS','description' => 'We integrate our service offerings to provide clients full-turnkey solutions.'])
@section('content')

    {{-- Section 1--}}
    <section >
        <div class="container mx-auto px-4 md:p-10 mt-10 flex flex-col mb-10 gap-14 ">
            <div class="md:w-1/2">
                <h2 class="font-visuletProLight  text-3xl md:text-6xl ">
                    Solutions tailored to you.
                </h2>
                <p class="text-xl mt-4">
                    Your Progress is our Succession Plan
                </p>
            </div>
        </div>
    </section>


    {{-- Section 2 - Services Showcase --}}
    <section class="py-20">
        <div class="container mx-auto px-4">
            <div class="space-y-32 font-visuletProLight">

                <!-- OTC Services - Left Aligned -->
                <div class="flex flex-col lg:flex-row items-center gap-16 lg:gap-20">
                    <div class="lg:w-1/2 space-y-6">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                <img src="{{asset('assets/images/commercial.svg')}}" alt="OTC Services" class="w-8 h-8">
                            </div>
                            <div>
                                <h3 class="text-3xl lg:text-4xl font-bold text-white">OTC Services</h3>
                                <div class="w-24 h-1 bg-gradient-to-r from-blue-500 to-purple-600 mt-2"></div>
                            </div>
                        </div>
                        <p class="text-lg text-gray-300 leading-relaxed font-inter">
                            With our Digital Asset OTC (Over The Counter) Services, we're committed to improve liquidity and financial innovation. These services enable secure, large-scale digital assets transactions. With advanced risk and treasury systems, CFOs and finance teams can explore new growth opportunities while maintaining transparency, regulatory compliance, and cash flow control.
                        </p>
                    </div>
                    <div class="lg:w-1/2">
                        <div class="relative group">
                            <div class="absolute inset-0 bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl blur opacity-25 group-hover:opacity-40 transition duration-300"></div>
                            <img src="{{asset('assets/images/otc.png')}}"
                                 alt="OTC Services Dashboard"
                                 class="relative rounded-2xl shadow-2xl w-full h-[400px] object-cover border border-gray-700/50">
                        </div>
                    </div>
                </div>

                <!-- FX Solutions - Right Aligned -->
                <div class="flex flex-col lg:flex-row-reverse items-center gap-16 lg:gap-20">
                    <div class="lg:w-1/2 space-y-6">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-teal-600 rounded-full flex items-center justify-center">
                                <img src="{{asset('assets/images/asset.svg')}}" alt="FX Solutions" class="w-8 h-8">
                            </div>
                            <div>
                                <h3 class="text-3xl lg:text-4xl font-bold text-white">FX Solutions</h3>
                                <div class="w-24 h-1 bg-gradient-to-r from-green-500 to-teal-600 mt-2"></div>
                            </div>
                        </div>
                        <p class="text-lg text-gray-300 leading-relaxed font-inter">
                            Our FX Solutions empower multinational companies to manage foreign exchange and currency risks more effectively, meeting the growing demand for sophisticated financial strategies. We offer the tools needed to handle currency exposure and optimize global cash flow, including real-time monitoring, hedging, and forecasting.
                        </p>
                    </div>
                    <div class="lg:w-1/2">
                        <div class="relative group">
                            <div class="absolute inset-0 bg-gradient-to-r from-green-500 to-teal-600 rounded-2xl blur opacity-25 group-hover:opacity-40 transition duration-300"></div>
                            <img src="{{asset('assets/images/fx-services.png')}}"
                                 alt="FX Solutions Platform"
                                 class="relative rounded-2xl shadow-2xl w-full max-h-[400px]] object-cover border border-gray-700/50">
                        </div>
                    </div>
                </div>

                <!-- Cross-Border Payments - Left Aligned -->
                <div class="flex flex-col lg:flex-row items-center gap-16 lg:gap-20">
                    <div class="lg:w-1/2 space-y-6">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-16 h-16 bg-gradient-to-r from-orange-500 to-red-600 rounded-full flex items-center justify-center">
                                <img src="{{asset('assets/images/platform.svg')}}" alt="Cross-Border Payments" class="w-8 h-8">
                            </div>
                            <div>
                                <h3 class="text-3xl lg:text-4xl font-bold text-white">Cross-Border Payments</h3>
                                <div class="w-24 h-1 bg-gradient-to-r from-orange-500 to-red-600 mt-2"></div>
                            </div>
                        </div>
                        <p class="text-lg text-gray-300 leading-relaxed font-inter">
                            Our Cross-Border Payments solutions are designed to meet the increasing demand for seamless international transactions across various currencies and regions. By providing the infrastructure to support these financial needs, we streamline payments to make them faster, more secure, and cost-effective.
                        </p>
                    </div>
                    <div class="lg:w-1/2">
                        <div class="relative group">
                            <div class="absolute inset-0 bg-gradient-to-r from-orange-500 to-red-600 rounded-2xl blur opacity-25 group-hover:opacity-40 transition duration-300"></div>
                            <img src="{{asset('assets/images/otc.png')}}"
                                 alt="Cross-Border Payments System"
                                 class="relative rounded-2xl shadow-2xl w-full max-h-[400px] object-cover border border-gray-700/50">
                        </div>
                    </div>
                </div>

                <!-- Payment Gateway - Right Aligned -->
                <div class="flex flex-col lg:flex-row-reverse items-center gap-16 lg:gap-20">
                    <div class="lg:w-1/2 space-y-6">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-600 rounded-full flex items-center justify-center">
                                <img src="{{asset('assets/images/hnwi.svg')}}" alt="Payment Gateway" class="w-8 h-8">
                            </div>
                            <div>
                                <h3 class="text-3xl lg:text-4xl font-bold text-white">Payment Gateway</h3>
                                <div class="w-24 h-1 bg-gradient-to-r from-purple-500 to-pink-600 mt-2"></div>
                            </div>
                        </div>
                        <p class="text-lg text-gray-300 leading-relaxed font-inter">
                            Our Payment Gateway APIs cater to the increasing demand for secure and versatile payment processing across various platforms. As businesses adapt to new financial assets, our solutions support credit/debit cards, digital wallets, and bank transfers with instant approval and settlement.
                        </p>
                    </div>
                    <div class="lg:w-1/2">
                        <div class="relative group">
                            <div class="absolute inset-0 bg-gradient-to-r from-purple-500 to-pink-600 rounded-2xl blur opacity-25 group-hover:opacity-40 transition duration-300"></div>
                            <img src="{{asset('assets/images/pg-api.png')}}"
                                 alt="Payment Gateway API"
                                 class="relative rounded-2xl shadow-2xl w-full max-h-[400px] object-cover border border-gray-700/50">
                        </div>
                    </div>
                </div>

                <!-- Bespoke Solutions - Centered Feature -->
                <div class="text-center">
                    <div class="max-w-4xl mx-auto">
                        <div class="flex flex-col items-center gap-8 mb-12">
                            <div class="w-20 h-20 bg-gradient-to-r from-indigo-500 to-cyan-600 rounded-full flex items-center justify-center">
                                <img src="{{asset('assets/images/commercial.svg')}}" alt="Bespoke Solutions" class="w-10 h-10">
                            </div>
                            <div>
                                <h3 class="text-4xl lg:text-5xl font-bold text-white mb-4">Bespoke Solutions</h3>
                                <div class="w-32 h-1 bg-gradient-to-r from-indigo-500 to-cyan-600 mx-auto"></div>
                            </div>
                        </div>
                        <p class="text-xl text-gray-300 leading-relaxed font-inter mb-12 max-w-3xl mx-auto">
                            We offer tailored solutions to meet the unique needs of your company, aligning perfectly with your objectives. By working closely with your finance and treasury teams, we create customized solutions that optimize cash flow, reduce risks, and improve financial results.
                        </p>
                        <div class="relative group max-w-2xl mx-auto">
                            <div class="absolute inset-0 bg-gradient-to-r from-indigo-500 to-cyan-600 rounded-3xl blur opacity-25 group-hover:opacity-40 transition duration-300"></div>
                            <img src="{{asset('assets/images/dashboard.png')}}"
                                 alt="Bespoke Solutions"
                                 class="relative rounded-3xl shadow-2xl w-full h-96 object-cover border border-gray-700/50">
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>


    {{-- Section 3--}}
    <section>
        <div class="container mx-auto px-4 md:p-10 mt-10 flex flex-col mb-10 gap-14 ">
            <div class="w-full xl:w-1/2">
                <h2 class="font-visuletProLight  text-3xl md:text-6xl ">
                    Our Comprehensive Treasury Solutions
                </h2>
                <p class="text-xl mt-4">
                    Our Comprehensive Treasury Solutions are designed to provide businesses with end-to-end support for managing liquidity, mitigating risk, and optimizing financial operations. Below are the core features of our solutions
                </p>
            </div>
        </div>
    </section>

    {{-- Section 4--}}

    <section class="container flex flex-col md:flex-row items-center justify-between px-6 mx-auto">
        <div class="w-full md:w-1/2 md:flex-1">
            <img src="{{asset('assets/images/liquidity.svg')}}" alt="Vault" class="w-full h-3/4 p-12">
        </div>
        <div class="w-full md:w-1/2 mt-6 md:mt-0 md:ml-10  font-visuletProLight ">
            <h2 class="text-3xl font-bold  mb-4 object-center">Liquidity Management</h2>
            <p class="text-lg text-white mb-6">Maximize your company's cash position with real-time visibility across accounts, currencies, and geographies. Our liquidity management tools offer automated cash forecasting, centralized control, and precise fund allocation to ensure efficient capital utilization and enhanced financial stability</p>
        </div>
    </section>

    {{-- Section 5--}}
    <section class="container flex flex-col md:flex-row-reverse items-center justify-between px-6  mx-auto">
        <div class="w-full md:w-1/2">
            <img src="{{asset('assets/images/risk_management.svg')}}" alt="Vault" class="w-full h-auto p-12">
        </div>
        <div class="w-full md:w-1/2 mt-6 md:mt-0 md:ml-10 font-visuletProLight">
            <h2 class="text-3xl font-bold text-white mb-4 ">Risk Mitigation</h2>
            <p class="text-lg text-white mb-6">Our advanced risk management systems protect your business from market volatility, currency fluctuations, and regulatory changes. With predictive analytics and dynamic hedging strategies, you can safeguard against potential losses while maintaining compliance with international financial regulations.</p>
        </div>
    </section>
    {{-- Section 6--}}
    <section class="container flex flex-col md:flex-row-reverse items-center justify-between px-6  mx-auto">
        <div class="w-full md:w-1/2 mt-6 md:mt-0 md:ml-10 font-visuletProLight">
            <h2 class="text-3xl font-bold text-white mb-4 ">Financial Planning & Analysis</h2>
            <p class="text-lg text-white mb-6">Optimize your financial strategy with our Financial Planning & Analysis tools. Use real-time data, forecasting models, and scenario planning to align financial decisions with your company’s long-term goals. Our solutions help you stay agile in a rapidly changing market, allowing you to adjust strategies and maximize profitability.
            </p>
        </div>
        <div class="w-full md:w-1/2">
            <img src="{{asset('assets/images/planing.svg')}}" alt="Vault" class="w-full h-auto p-12">
        </div>
    </section>





@endsection

