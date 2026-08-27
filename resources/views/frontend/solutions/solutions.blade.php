@extends('_partials.app',['isDark'=>true,'title' => config('app.name').' | SOLUTIONS','description' => 'We build the financial infrastructure behind your business — OTC desks, FX APIs, cross-border rails and payment gateways, engineered for you.'])
@section('content')

    {{-- Section 1 - Hero --}}
    <section>
        <div class="container mx-auto px-4 md:p-10 mt-10 flex flex-col mb-10 gap-14 ">
            <div class="md:w-2/3">
                <p class="uppercase tracking-widest text-sm text-gray-400 font-inter mb-4">What we build for you</p>
                <h2 class="font-visuletProLight  text-3xl md:text-6xl ">
                    We build the infrastructure.<br>
                    You run the business.
                </h2>
                <p class="text-xl mt-4">
                    From OTC desks and FX APIs to cross-border rails and payment gateways, we engineer the financial
                    infrastructure your products run on — and keep it running.
                </p>
                <p class="text-lg mt-6 text-gray-400 font-inter">
                    Your Progress is our Succession Plan
                </p>
            </div>
        </div>
    </section>

    {{-- Section 2 - How we work --}}
    <section class="py-10">
        <div class="container mx-auto px-4 md:px-10">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 font-visuletProLight">
                <div class="border border-gray-700/50 rounded-2xl p-8">
                    <h3 class="text-2xl font-bold text-white mb-3">Infrastructure, built</h3>
                    <p class="text-gray-300 font-inter">
                        We stand up the trading, treasury and settlement systems your desk needs, on your brand and
                        under your control — not a product you rent from us.
                    </p>
                </div>
                <div class="border border-gray-700/50 rounded-2xl p-8">
                    <h3 class="text-2xl font-bold text-white mb-3">APIs, shipped</h3>
                    <p class="text-gray-300 font-inter">
                        Documented, versioned endpoints for pricing, execution, payouts and reconciliation, ready to
                        drop into the platform your team already maintains.
                    </p>
                </div>
                <div class="border border-gray-700/50 rounded-2xl p-8">
                    <h3 class="text-2xl font-bold text-white mb-3">Operations, supported</h3>
                    <p class="text-gray-300 font-inter">
                        Once it is live we stay on it — monitoring, liquidity partners, compliance reporting and
                        changes as your volumes and markets grow.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Section 3 - What We Build --}}
    <section class="py-20">
        <div class="container mx-auto px-4">
            <div class="space-y-32 font-visuletProLight">

                <!-- OTC Infrastructure - Left Aligned -->
                <div class="flex flex-col lg:flex-row items-center gap-16 lg:gap-20">
                    <div class="lg:w-1/2 space-y-6">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                <img src="{{asset('assets/images/commercial.svg')}}" alt="OTC Infrastructure" class="w-8 h-8">
                            </div>
                            <div>
                                <p class="uppercase tracking-widest text-xs text-gray-400 font-inter">We build for you</p>
                                <h3 class="text-3xl lg:text-4xl font-bold text-white">OTC Trading Infrastructure</h3>
                                <div class="w-24 h-1 bg-gradient-to-r from-blue-500 to-purple-600 mt-2"></div>
                            </div>
                        </div>
                        <p class="text-lg text-gray-300 leading-relaxed font-inter">
                            We build the desk you trade from. Adlef engineers the full digital asset OTC stack for your
                            business — RFQ and quoting workflows, aggregated liquidity from our partner network, trade
                            capture, settlement and custody connectivity — so you can move size securely without
                            building any of it in-house. Risk limits, treasury controls and audit trails are wired in
                            from day one, giving your CFO and finance team transparency, regulatory comfort and cash
                            flow control.
                        </p>
                        <ul class="flex flex-wrap gap-3 font-inter text-sm text-gray-300">
                            <li class="border border-gray-700/50 rounded-full px-4 py-2">RFQ &amp; quoting engine</li>
                            <li class="border border-gray-700/50 rounded-full px-4 py-2">Liquidity aggregation</li>
                            <li class="border border-gray-700/50 rounded-full px-4 py-2">Settlement &amp; custody links</li>
                            <li class="border border-gray-700/50 rounded-full px-4 py-2">Risk &amp; limit controls</li>
                        </ul>
                    </div>
                    <div class="lg:w-1/2">
                        <div class="relative group">
                            <div class="absolute inset-0 bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl blur opacity-25 group-hover:opacity-40 transition duration-300"></div>
                            <img src="{{asset('assets/images/otc.png')}}"
                                 alt="OTC Trading Infrastructure"
                                 class="relative rounded-2xl shadow-2xl w-full h-[400px] object-cover border border-gray-700/50">
                        </div>
                    </div>
                </div>

                <!-- FX APIs - Right Aligned -->
                <div class="flex flex-col lg:flex-row-reverse items-center gap-16 lg:gap-20">
                    <div class="lg:w-1/2 space-y-6">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-teal-600 rounded-full flex items-center justify-center">
                                <img src="{{asset('assets/images/asset.svg')}}" alt="FX APIs" class="w-8 h-8">
                            </div>
                            <div>
                                <p class="uppercase tracking-widest text-xs text-gray-400 font-inter">We build for you</p>
                                <h3 class="text-3xl lg:text-4xl font-bold text-white">APIs for FX Solutions</h3>
                                <div class="w-24 h-1 bg-gradient-to-r from-green-500 to-teal-600 mt-2"></div>
                            </div>
                        </div>
                        <p class="text-lg text-gray-300 leading-relaxed font-inter">
                            We build the FX layer inside your product. Our APIs deliver live and forward rates,
                            multi-currency balances, hedging instructions and exposure reporting straight into the
                            systems your treasury already uses. Instead of buying a fixed FX service, your team calls
                            endpoints we build and maintain for you — so multinational currency risk, global cash flow
                            and forecasting are handled where your business actually operates.
                        </p>
                        <ul class="flex flex-wrap gap-3 font-inter text-sm text-gray-300">
                            <li class="border border-gray-700/50 rounded-full px-4 py-2">Live &amp; forward rate APIs</li>
                            <li class="border border-gray-700/50 rounded-full px-4 py-2">Multi-currency accounts</li>
                            <li class="border border-gray-700/50 rounded-full px-4 py-2">Hedging workflows</li>
                            <li class="border border-gray-700/50 rounded-full px-4 py-2">Exposure &amp; forecasting feeds</li>
                        </ul>
                    </div>
                    <div class="lg:w-1/2">
                        <div class="relative group">
                            <div class="absolute inset-0 bg-gradient-to-r from-green-500 to-teal-600 rounded-2xl blur opacity-25 group-hover:opacity-40 transition duration-300"></div>
                            <img src="{{asset('assets/images/fx-services.png')}}"
                                 alt="FX APIs"
                                 class="relative rounded-2xl shadow-2xl w-full max-h-[400px] object-cover border border-gray-700/50">
                        </div>
                    </div>
                </div>

                <!-- Cross-Border Rails - Left Aligned -->
                <div class="flex flex-col lg:flex-row items-center gap-16 lg:gap-20">
                    <div class="lg:w-1/2 space-y-6">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-16 h-16 bg-gradient-to-r from-orange-500 to-red-600 rounded-full flex items-center justify-center">
                                <img src="{{asset('assets/images/platform.svg')}}" alt="Cross-Border Payment Rails" class="w-8 h-8">
                            </div>
                            <div>
                                <p class="uppercase tracking-widest text-xs text-gray-400 font-inter">We build for you</p>
                                <h3 class="text-3xl lg:text-4xl font-bold text-white">Cross-Border Payment Rails</h3>
                                <div class="w-24 h-1 bg-gradient-to-r from-orange-500 to-red-600 mt-2"></div>
                            </div>
                        </div>
                        <p class="text-lg text-gray-300 leading-relaxed font-inter">
                            We build the rails your money moves on. Adlef connects your business to banking and
                            settlement partners across currencies and regions, then builds the routing, FX conversion,
                            payout and reconciliation layer on top — with screening and reporting built in. Your
                            customers get international payments that are faster, cheaper and traceable; you get the
                            infrastructure without the correspondent-banking build.
                        </p>
                        <ul class="flex flex-wrap gap-3 font-inter text-sm text-gray-300">
                            <li class="border border-gray-700/50 rounded-full px-4 py-2">Payout &amp; collection rails</li>
                            <li class="border border-gray-700/50 rounded-full px-4 py-2">Smart routing</li>
                            <li class="border border-gray-700/50 rounded-full px-4 py-2">Automated reconciliation</li>
                            <li class="border border-gray-700/50 rounded-full px-4 py-2">Screening &amp; reporting</li>
                        </ul>
                    </div>
                    <div class="lg:w-1/2">
                        <div class="relative group">
                            <div class="absolute inset-0 bg-gradient-to-r from-orange-500 to-red-600 rounded-2xl blur opacity-25 group-hover:opacity-40 transition duration-300"></div>
                            <img src="{{asset('assets/images/otc.png')}}"
                                 alt="Cross-Border Payment Rails"
                                 class="relative rounded-2xl shadow-2xl w-full max-h-[400px] object-cover border border-gray-700/50">
                        </div>
                    </div>
                </div>

                <!-- Payment Gateway APIs - Right Aligned -->
                <div class="flex flex-col lg:flex-row-reverse items-center gap-16 lg:gap-20">
                    <div class="lg:w-1/2 space-y-6">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-600 rounded-full flex items-center justify-center">
                                <img src="{{asset('assets/images/hnwi.svg')}}" alt="Payment Gateway APIs" class="w-8 h-8">
                            </div>
                            <div>
                                <p class="uppercase tracking-widest text-xs text-gray-400 font-inter">We build for you</p>
                                <h3 class="text-3xl lg:text-4xl font-bold text-white">Payment Gateway APIs</h3>
                                <div class="w-24 h-1 bg-gradient-to-r from-purple-500 to-pink-600 mt-2"></div>
                            </div>
                        </div>
                        <p class="text-lg text-gray-300 leading-relaxed font-inter">
                            We build the checkout behind your brand. Adlef delivers gateway APIs and hosted flows for
                            cards, digital wallets and bank transfers, with instant authorisation, tokenised
                            credentials, webhooks and settlement reporting. As your business adopts new payment and
                            digital asset types, we extend the same integration rather than asking your engineers to
                            start again.
                        </p>
                        <ul class="flex flex-wrap gap-3 font-inter text-sm text-gray-300">
                            <li class="border border-gray-700/50 rounded-full px-4 py-2">Card, wallet &amp; bank transfer</li>
                            <li class="border border-gray-700/50 rounded-full px-4 py-2">Tokenised credentials</li>
                            <li class="border border-gray-700/50 rounded-full px-4 py-2">Webhooks &amp; SDKs</li>
                            <li class="border border-gray-700/50 rounded-full px-4 py-2">Settlement reporting</li>
                        </ul>
                    </div>
                    <div class="lg:w-1/2">
                        <div class="relative group">
                            <div class="absolute inset-0 bg-gradient-to-r from-purple-500 to-pink-600 rounded-2xl blur opacity-25 group-hover:opacity-40 transition duration-300"></div>
                            <img src="{{asset('assets/images/pg-api.png')}}"
                                 alt="Payment Gateway APIs"
                                 class="relative rounded-2xl shadow-2xl w-full max-h-[400px] object-cover border border-gray-700/50">
                        </div>
                    </div>
                </div>

                <!-- Bespoke Builds - Centered Feature -->
                <div class="text-center">
                    <div class="max-w-4xl mx-auto">
                        <div class="flex flex-col items-center gap-8 mb-12">
                            <div class="w-20 h-20 bg-gradient-to-r from-indigo-500 to-cyan-600 rounded-full flex items-center justify-center">
                                <img src="{{asset('assets/images/commercial.svg')}}" alt="Bespoke Builds" class="w-10 h-10">
                            </div>
                            <div>
                                <p class="uppercase tracking-widest text-xs text-gray-400 font-inter mb-2">We build for you</p>
                                <h3 class="text-4xl lg:text-5xl font-bold text-white mb-4">Bespoke Builds</h3>
                                <div class="w-32 h-1 bg-gradient-to-r from-indigo-500 to-cyan-600 mx-auto"></div>
                            </div>
                        </div>
                        <p class="text-xl text-gray-300 leading-relaxed font-inter mb-12 max-w-3xl mx-auto">
                            When the system you need does not exist yet, we build it. Working alongside your finance,
                            treasury and engineering teams, we scope, build and integrate infrastructure shaped around
                            your objectives — the workflows, dashboards and controls that optimise cash flow, reduce
                            risk and improve financial results in your business specifically.
                        </p>
                        <div class="relative group max-w-2xl mx-auto">
                            <div class="absolute inset-0 bg-gradient-to-r from-indigo-500 to-cyan-600 rounded-3xl blur opacity-25 group-hover:opacity-40 transition duration-300"></div>
                            <img src="{{asset('assets/images/dashboard.png')}}"
                                 alt="Bespoke Builds"
                                 class="relative rounded-3xl shadow-2xl w-full h-96 object-cover border border-gray-700/50">
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>


    {{-- Section 4 - Treasury modules intro --}}
    <section>
        <div class="container mx-auto px-4 md:p-10 mt-10 flex flex-col mb-10 gap-14 ">
            <div class="w-full xl:w-1/2">
                <p class="uppercase tracking-widest text-sm text-gray-400 font-inter mb-4">Modules we build in</p>
                <h2 class="font-visuletProLight  text-3xl md:text-6xl ">
                    The treasury layer we build into your stack
                </h2>
                <p class="text-xl mt-4">
                    Every build ships with the treasury capability your operation needs to manage liquidity, mitigate
                    risk and plan ahead. These modules are configured for your entities, currencies and controls — not
                    delivered as a fixed package.
                </p>
            </div>
        </div>
    </section>

    {{-- Section 5 - Liquidity --}}

    <section class="container flex flex-col md:flex-row items-center justify-between px-6 mx-auto">
        <div class="w-full md:w-1/2 md:flex-1">
            <img src="{{asset('assets/images/liquidity.svg')}}" alt="Vault" class="w-full h-3/4 p-12">
        </div>
        <div class="w-full md:w-1/2 mt-6 md:mt-0 md:ml-10  font-visuletProLight ">
            <h2 class="text-3xl font-bold  mb-4 object-center">Liquidity Management</h2>
            <p class="text-lg text-white mb-6">We build you a real-time view of cash across accounts, currencies and
                geographies, with automated forecasting, centralised control and precise fund allocation — so your
                capital is working where it should and your cash position is never a question.</p>
        </div>
    </section>

    {{-- Section 6 - Risk --}}
    <section class="container flex flex-col md:flex-row-reverse items-center justify-between px-6  mx-auto">
        <div class="w-full md:w-1/2">
            <img src="{{asset('assets/images/risk_management.svg')}}" alt="Vault" class="w-full h-auto p-12">
        </div>
        <div class="w-full md:w-1/2 mt-6 md:mt-0 md:ml-10 font-visuletProLight">
            <h2 class="text-3xl font-bold text-white mb-4 ">Risk Mitigation</h2>
            <p class="text-lg text-white mb-6">We build the controls that shield your business from market volatility,
                currency swings and regulatory change — predictive analytics, dynamic hedging strategies and limit
                frameworks that keep you compliant across the jurisdictions you operate in.</p>
        </div>
    </section>
    {{-- Section 7 - FP&A --}}
    <section class="container flex flex-col md:flex-row-reverse items-center justify-between px-6  mx-auto">
        <div class="w-full md:w-1/2 mt-6 md:mt-0 md:ml-10 font-visuletProLight">
            <h2 class="text-3xl font-bold text-white mb-4 ">Financial Planning &amp; Analysis</h2>
            <p class="text-lg text-white mb-6">We build the reporting and modelling layer your strategy runs on —
                real-time data, forecasting models and scenario planning tied to your long-term goals, so your team can
                adjust quickly as markets move and protect profitability.
            </p>
        </div>
        <div class="w-full md:w-1/2">
            <img src="{{asset('assets/images/planing.svg')}}" alt="Vault" class="w-full h-auto p-12">
        </div>
    </section>

    {{-- Section 8 - How we build --}}
    <section class="py-20">
        <div class="container mx-auto px-4 md:px-10">
            <div class="w-full xl:w-1/2 mb-12">
                <h2 class="font-visuletProLight text-3xl md:text-5xl">How we build with you</h2>
                <p class="text-xl mt-4">A working engagement, from first scoping call to live infrastructure.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-8 font-visuletProLight">
                <div class="border-t border-gray-700/50 pt-6">
                    <p class="text-sm text-gray-400 font-inter mb-3">01</p>
                    <h3 class="text-2xl font-bold text-white mb-3">Scope</h3>
                    <p class="text-gray-300 font-inter">We map your flows, entities, currencies and regulatory footprint
                        with your finance and engineering teams.</p>
                </div>
                <div class="border-t border-gray-700/50 pt-6">
                    <p class="text-sm text-gray-400 font-inter mb-3">02</p>
                    <h3 class="text-2xl font-bold text-white mb-3">Build</h3>
                    <p class="text-gray-300 font-inter">We engineer the infrastructure and APIs, connect liquidity and
                        banking partners, and configure your controls.</p>
                </div>
                <div class="border-t border-gray-700/50 pt-6">
                    <p class="text-sm text-gray-400 font-inter mb-3">03</p>
                    <h3 class="text-2xl font-bold text-white mb-3">Integrate</h3>
                    <p class="text-gray-300 font-inter">We ship into your environment with documentation, sandbox
                        access and support for your developers through go-live.</p>
                </div>
                <div class="border-t border-gray-700/50 pt-6">
                    <p class="text-sm text-gray-400 font-inter mb-3">04</p>
                    <h3 class="text-2xl font-bold text-white mb-3">Run</h3>
                    <p class="text-gray-300 font-inter">We monitor, report and extend the build as your volumes,
                        markets and product lines grow.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Section 9 - CTA --}}
    <section class="py-20">
        <div class="container mx-auto px-4 md:px-10">
            <div class="border border-gray-700/50 rounded-2xl p-10 md:p-16 flex flex-col lg:flex-row gap-8 justify-between">
                <div class="lg:w-2/3">
                    <h2 class="font-visuletProLight text-3xl md:text-5xl">Tell us what you need built.</h2>
                    <p class="text-xl mt-4 text-gray-300 font-inter">Bring us the flow you are trying to launch and we
                        will come back with the infrastructure to support it.</p>
                </div>
                <div>
                    <a href="{{route('frontend.contact-us.business-enquiry')}}"
                       class="inline-block bg-white text-black px-6 py-4 rounded-full font-inter whitespace-nowrap hover:bg-gray-200 transition duration-300">
                        Talk to our team
                    </a>
                </div>
            </div>
        </div>
    </section>

@endsection
