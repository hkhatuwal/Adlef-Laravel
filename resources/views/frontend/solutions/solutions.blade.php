@extends('_partials.app')

@section('content')

    {{-- Section 1--}}
    <section>

        <div class="container mx-auto px-4 md:p-10 mt-10 flex flex-col mb-10 gap-14 ">
            <div class="md:w-1/2">
                <h2 class="font-visuletProLight  text-3xl md:text-6xl ">
                    Solutions tailored to you.
                </h2>
                <p class="text-xl mt-4">
                    We integrate our service offerings to provide clients full-turnkey solutions.
                </p>
            </div>
        </div>


    </section>


    {{-- Section 2--}}
    <section>
        <div class="container mx-auto px-4 md:p-10 mt-10">
            <div class="flex flex-col mb-10 gap-14  font-visuletProLight">
                <div class="md:w-1/2">
                    <h2 class="font-visuletProLight  text-3xl md:text-6xl ">
                        Helping <span class="font-semibold">a wide</span> variety of clients.
                    </h2>
                    <p class="text-xl mt-4">
                        We’re a strategic ally to a range of incredible organizations from start-ups to large
                        institutions
                        across number of key verticals and sectors. </p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 md:w-11/12 mx-auto">
                    <!-- Top Left -->
                    <div class="flex flex-col  p-8">
                        <div class="mb-4">
                            <!-- Icon 1 -->
                            <img src="{{asset('assets/images/commercial.svg')}}" alt="">
                        </div>
                        <h3 class="text-2xl font-semibold">Commercial & Enterprises </h3>
                        <p class="text-black  font-inter   mt-2 text-start">
                            Streamlining businesses' cross-asset treasury operations through ancillary settlement &
                            clearing, cross-border netting, and ancillary financial solutions.
                        </p>
                    </div>
                    <div class="flex flex-col  p-8 ">
                        <div class="mb-4">
                            <!-- Icon 1 -->
                            <img src="{{asset('assets/images/asset.svg')}}" alt="">
                        </div>
                        <h3 class="text-2xl font-semibold">Asset Managers</h3>
                        <p class="text-gray-600 mt-2  font-inter text-start">
                            Delivering expertise in fund administration and accounting, custody, trustee, asset transfer
                            and structuring – so you can focus on management, performance and distribution.
                        </p>
                    </div>
                    <div class="flex flex-col  p-8 ">
                        <div class="mb-4">
                            <!-- Icon 1 -->
                            <img src="{{asset('assets/images/platform.svg')}}" alt="">
                        </div>
                        <h3 class="text-2xl font-semibold">Platform Providers</h3>
                        <p class="text-gray-600 mt-2 font-inter text-start">
                            Handling the complex issues fintech companies face when bridging traditional finance and the
                            evolving blockchain ecosystem.
                        </p>
                    </div>
                    <div class="flex flex-col  p-8 ">
                        <div class="mb-4">
                            <!-- Icon 1 -->
                            <img src="{{asset('assets/images/hnwi.svg')}}" alt="">
                        </div>
                        <h3 class="text-2xl font-semibold">HNWI & Family Offices</h3>
                        <p class="text-gray-600 mt-2 font-inter text-start">
                            Protecting and nurturing private capital in real estate, financial and alternative assets
                            across regions and generations. </p>
                    </div>


                </div>

            </div>
        </div>


    </section>


    {{-- Section 3--}}

    <section class="container flex flex-col md:flex-row items-center justify-between p-6 bg-white mx-auto">
        <div class="w-full md:w-1/2 md:flex-1">
            <img src="{{asset('assets/images/savings.svg')}}" alt="Vault" class="w-full h-3/4]">
        </div>
        <div class="w-full md:w-1/2 mt-6 md:mt-0 md:ml-10  font-visuletProLight ">
            <h2 class="text-3xl font-bold text-gray-800 mb-4 object-center">Digital Savings </h2>
            <p class="text-lg text-gray-600 mb-6">Safekeeping and servicing of your digital assets.</p>
            <ul class="space-y-4">
                <li class="flex items-start">
                    <svg class="w-6 h-6 text-green-500 mr-2" fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span class="text-gray-700">All major asset classes.</span>
                </li>
                <li class="flex items-start">
                    <svg class="w-6 h-6 text-green-500 mr-2" fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span class="text-gray-700">Over 2,500+ tokens and NFTs supported.</span>
                </li>
                <li class="flex items-start">
                    <svg class="w-6 h-6 text-green-500 mr-2" fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span class="text-gray-700">15 major blockchains covered.</span>
                </li>
            </ul>
        </div>
    </section>

    {{-- Section 4--}}
    <section class="container flex flex-col md:flex-row-reverse items-center justify-between p-6 bg-white mx-auto">
        <div class="w-full md:w-1/2">
            <img src="{{asset('assets/images/path.svg')}}" alt="Vault" class="w-full h-auto">
        </div>
        <div class="w-full md:w-1/2 mt-6 md:mt-0 md:ml-10 font-visuletProLight">
            <h2 class="text-3xl font-bold text-gray-800 mb-4 ">Custody for the digital age</h2>
            <p class="text-lg text-gray-600 mb-6">Safekeeping and servicing of your digital assets.</p>
            <ul class="space-y-4">
                <li class="flex items-start">
                    <svg class="w-6 h-6 text-green-500 mr-2" fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span class="text-gray-700">All major asset classes.</span>
                </li>
                <li class="flex items-start">
                    <svg class="w-6 h-6 text-green-500 mr-2" fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span class="text-gray-700">Over 2,500+ tokens and NFTs supported.</span>
                </li>
                <li class="flex items-start">
                    <svg class="w-6 h-6 text-green-500 mr-2" fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span class="text-gray-700">15 major blockchains covered.</span>
                </li>
            </ul>
        </div>
    </section>

@endsection
