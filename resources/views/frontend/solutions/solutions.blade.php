@extends('_partials.app',['title' => 'ADLEF GROUP | SOLUTIONS','description' => 'We integrate our service offerings to provide clients full-turnkey solutions.'])
@section('content')

    {{-- Section 1--}}
    <section>
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


    {{-- Section 2--}}
    <section>
        <div class="container mx-auto px-4 md:p-10 mt-10">
            <div class="flex flex-col mb-10 gap-14  font-visuletProLight">
                <div class="grid grid-cols-1 md:grid-cols-2 md:w-11/12 mx-auto">
                    <!-- Top Left -->
                    <div class="flex flex-col  p-8">
                        <div class="mb-4">
                            <!-- Icon 1 -->
                            <img src="{{asset('assets/images/commercial.svg')}}" alt="">
                        </div>
                        <h3 class="text-2xl font-semibold">OTC Servcies </h3>
                        <p class="text-black  font-inter   mt-2 text-start">
                            With our Digital Asset OTC (Over The Counter)  Services, we're committed to improve liquidity and financial innovation. These services enable secure, large-scale digital assets transactions. With advanced risk and treasury systems, CFOs and finance teams can explore new growth opportunities while maintaining transparency, regulatory compliance, and cash flow control.

                        </p>
                    </div>
                    <div class="flex flex-col  p-8 ">
                        <div class="mb-4">
                            <!-- Icon 1 -->
                            <img src="{{asset('assets/images/asset.svg')}}" alt="">
                        </div>
                        <h3 class="text-2xl font-semibold">FX Solutions</h3>
                        <p class="text-gray-600 mt-2  font-inter text-start">
                            Our FX Solutions empower multinational companies to manage foreign exchange and currency risks more effectively, meeting the growing demand for sophisticated financial strategies. We offer the tools needed to handle currency exposure and optimize global cash flow, including real-time monitoring, hedging, and forecasting. CFOs and treasury teams can navigate the complexities of global markets with ease, ensuring regulatory compliance and maintaining transparency in the process.

                        </p>
                    </div>
                    <div class="flex flex-col  p-8 ">
                        <div class="mb-4">
                            <!-- Icon 1 -->
                            <img src="{{asset('assets/images/platform.svg')}}" alt="">
                        </div>
                        <h3 class="text-2xl font-semibold">Cross-Border Payments
                        </h3>
                        <p class="text-gray-600 mt-2 font-inter text-start">
                            Our Cross-Border Payments solutions are designed to meet the increasing demand for seamless international transactions across various currencies and regions. By providing the infrastructure to support these financial needs, we streamline payments to make them faster, more secure, and cost-effective. With automated workflows, real-time transaction tracking, and adherence to global regulations, these services minimize risks associated with currency fluctuations and payment delays. Finance teams gain better visibility and control, optimizing cash flow in international markets.

                        </p>
                    </div>
                    <div class="flex flex-col  p-8 ">
                        <div class="mb-4">
                            <!-- Icon 1 -->
                            <img src="{{asset('assets/images/hnwi.svg')}}" alt="">
                        </div>
                        <h3 class="text-2xl font-semibold">Payment Gateway
                        </h3>
                        <p class="text-gray-600 mt-2 font-inter text-start">
                            Our Payment Gateway APIs cater to the increasing demand for secure and versatile payment processing across various platforms. As businesses adapt to new financial assets, our solutions support credit/debit cards, digital wallets, and bank transfers with instant approval and settlement. These APIs are flexible and scalable, integrating seamlessly into e-commerce sites, mobile apps, and subscription services. With robust security, fraud protection, and adherence to industry standards, businesses can ensure safe transactions without disrupting the customer experience.
                        </p>
                    </div>
                    <div class="flex flex-col  p-8 ">
                        <div class="mb-4">
                            <!-- Icon 1 -->
                            <img src="{{asset('assets/images/commercial.svg')}}" alt="">
                        </div>
                        <h3 class="text-2xl font-semibold">Bespoke Solutions
                        </h3>
                        <p class="text-gray-600 mt-2 font-inter text-start">
                            We offer tailored solutions to meet the unique needs of your company, aligning perfectly with your objectives. By working closely with your finance and treasury teams, we create customized solutions that optimize cash flow, reduce risks, and improve financial results. We provide the flexibility and precision you need for specialized reporting, customized system integration, or unique financial arrangements. Ensure continuous growth and operational efficiency with bespoke solutions that grow with your business.

                        </p>
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

    <section class="container flex flex-col md:flex-row items-center justify-between px-6 bg-white mx-auto">
        <div class="w-full md:w-1/2 md:flex-1">
            <img src="{{asset('assets/images/liquidity.svg')}}" alt="Vault" class="w-full h-3/4 p-12">
        </div>
        <div class="w-full md:w-1/2 mt-6 md:mt-0 md:ml-10  font-visuletProLight ">
            <h2 class="text-3xl font-bold text-gray-800 mb-4 object-center">Liquidity Management</h2>
            <p class="text-lg text-gray-600 mb-6">Maximize your company's cash position with real-time visibility across accounts, currencies, and geographies. Our liquidity management tools offer automated cash forecasting, centralized control, and precise fund allocation to ensure efficient capital utilization and enhanced financial stability</p>
        </div>
    </section>

    {{-- Section 5--}}
    <section class="container flex flex-col md:flex-row-reverse items-center justify-between px-6 bg-white mx-auto">
        <div class="w-full md:w-1/2">
            <img src="{{asset('assets/images/risk_management.svg')}}" alt="Vault" class="w-full h-auto p-12">
        </div>
        <div class="w-full md:w-1/2 mt-6 md:mt-0 md:ml-10 font-visuletProLight">
            <h2 class="text-3xl font-bold text-gray-800 mb-4 ">Risk Mitigation</h2>
            <p class="text-lg text-gray-600 mb-6">Our advanced risk management systems protect your business from market volatility, currency fluctuations, and regulatory changes. With predictive analytics and dynamic hedging strategies, you can safeguard against potential losses while maintaining compliance with international financial regulations.</p>
        </div>
    </section>
    {{-- Section 6--}}
    <section class="container flex flex-col md:flex-row-reverse items-center justify-between px-6 bg-white mx-auto">
        <div class="w-full md:w-1/2 mt-6 md:mt-0 md:ml-10 font-visuletProLight">
            <h2 class="text-3xl font-bold text-gray-800 mb-4 ">Financial Planning & Analysis</h2>
            <p class="text-lg text-gray-600 mb-6">Optimize your financial strategy with our Financial Planning & Analysis tools. Use real-time data, forecasting models, and scenario planning to align financial decisions with your company’s long-term goals. Our solutions help you stay agile in a rapidly changing market, allowing you to adjust strategies and maximize profitability.
            </p>
        </div>
        <div class="w-full md:w-1/2">
            <img src="{{asset('assets/images/planing.svg')}}" alt="Vault" class="w-full h-auto p-12">
        </div>
    </section>





@endsection
