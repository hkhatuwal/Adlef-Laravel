@extends('_partials.app',['title' => 'ADLEF GROUP | HOME','description' => ' Power your finances with our personalized platform designed for
                Multi asset servicing, seamlessly Connecting traditional and
                digital financial ecosystems','image' => asset('assets/images/savings.svg')])

@section('content')

    <section class="container">
        <div class="p-8 py-16">
            <div class="space-y-4">
                <h1 class="text-6xl ">Programmatic <br> access to our data <br> and services.</h1>
                <p class="text-xl text-black md:w-1/2">Plug in seamlessly via our industry-standard RESTful API, designed with client input to allow you to offer apps and services with greater financial transparency.</p>
            </div>
        </div>
    </section>
    <section class="bg-black">
        <div class=" text-white py-16 px-8 space-y-12 container">
            <div class="space-y-4">
                <h2 class="text-5xl leading-normal">A complete suite of <br> <span class="font-semibold">API endpoints.</span></h2>
                <p class="text-xl ">We bring together everything you need <br> to build next-generation financial services.</p>
            </div>
            <!-- API Endpoints Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Card 1 -->
                <div class="flex items-start space-x-4">
                    <div class="text-green-500 text-2xl">✔</div>
                    <div>
                        <h3 class="font-semibold">Client onboarding</h3>
                        <p>Business and individual client onboarding, entity and service setup.</p>
                    </div>
                </div>
                <!-- Card 2 -->
                <div class="flex items-start space-x-4">
                    <div class="text-green-500 text-2xl">✔</div>
                    <div>
                        <h3 class="font-semibold">Instruction initiation</h3>
                        <p>Enable your First Digital Trust clients to initiate instructions from your digital environment.</p>
                    </div>
                </div>
                <!-- Card 3 -->
                <div class="flex items-start space-x-4">
                    <div class="text-green-500 text-2xl">✔</div>
                    <div>
                        <h3 class="font-semibold">Authentication service</h3>
                        <p>Let users easily log into apps and services using our SSO experience in your enterprise app.</p>
                    </div>
                </div>
                <!-- Card 4 -->
                <div class="flex items-start space-x-4">
                    <div class="text-green-500 text-2xl">✔</div>
                    <div>
                        <h3 class="font-semibold">KYC & assured identity</h3>
                        <p>Remain compliant with on-demand access to AML/CTF data and documents.</p>
                    </div>
                </div>
                <!-- Card 5 -->
                <div class="flex items-start space-x-4">
                    <div class="text-green-500 text-2xl">✔</div>
                    <div>
                        <h3 class="font-semibold">Reporting</h3>
                        <p>Access balances and reporting in real-time, or retrieve years’ worth of detailed transaction histories.</p>
                    </div>
                </div>
                <!-- Card 6 -->
                <div class="flex items-start space-x-4">
                    <div class="text-green-500 text-2xl">✔</div>
                    <div>
                        <h3 class="font-semibold">Account information</h3>
                        <p>Access client and account lists and detailed, real-time account information.</p>
                    </div>
                </div>
                <!-- Card 7 -->
                <div class="flex items-start space-x-4">
                    <div class="text-green-500 text-2xl">✔</div>
                    <div>
                        <h3 class="font-semibold">Webhooks</h3>
                        <p>Sync notifications and automated responses across applications and accounts.</p>
                    </div>
                </div>
            </div>
        </div>

    </section>

@endsection
