<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <link rel="stylesheet" href="{{asset('assets/css/aos.css')}}">

    <title>Document</title>
</head>
<body class="flex items-center justify-center">
<div class="w-screen" data-aos-easing="ease" data-aos-duration="400" data-aos-delay="0">
    {{-- Navbar --}}
    <nav class="navbar mx-auto flex justify-between px-10  sticky top-0 bg-white w-full">
        <div class="logo flex justify-center items-center">
            <img class="h-20" src="{{ asset('assets/images/logo.svg') }}" alt="Logo">
        </div>
        <div class="sm:hidden flex items-center">
            <button id="menu-toggle" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 12h16m-7 6h7"></path>
                </svg>
            </button>
        </div>
        <ul id="menu-items"
            class="gap-3 md:gap-5 flex p-5 opacity-0    transition-opacity duration-300   flex-col  absolute sm:relative bg-white  w-full sm:w-auto top-10 sm:top-0 left-0 mt-12 z-20 sm:z-auto  sm:mt-0 sm:flex-row sm:flex sm:items-center sm:bg-transparent sm:opacity-100 sm:p-0 sm:overflow-visible">
            <li><a href="#">Solutions</a></li>
            <li><a href="#">News & Insights</a></li>
            <li>
                <div class=" inline-block">
                    <button class="text-gray-800 font-medium hover:text-gray-900 dropdown-button">Company</button>
                    <div class="hidden absolute bg-white rounded-md shadow-sm w-48 mt-2 dropdown-content z-[9999]">
                        <ul class="py-2">
                            <li class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"><a href="">About Us</a></li>
                            <li class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"><a href="">Careers</a></li>
                            <li class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"><a href="">Get in touch</a></li>
                        </ul>
                    </div>
                </div>
            </li>
            <li>
                <button class="btn-primary hidden md:block">Login</button>
            </li>
            <li>
                <button class="btn-secondary hidden md:block">Become a Client</button>
            </li>
        </ul>
    </nav>

    {{-- Hero Section --}}

    <section class="container mx-auto hero  flex flex-col-reverse  md:flex-row  items-center bg-white p-3 md:p-10">
        <div class="hero-content   flex-1 flex flex-col items-center md:items-start gap-4">
            <h3 class="text-3xl mt-4 md:text-[3.5rem] font-visuletProLight leading-tight text-center md:text-start">
                Corporate
                <mark class="font-semibold bg-accent">treasury services</mark>
                for global businesses.
            </h3>
            <p class="text-xl text-center md:text-start">
                Optimize your finances with our specialized platform designed for custody and asset servicing,
                seamlessly bridging traditional and digital financial ecosystems.
            </p>
            <button class="btn-dark btn-lg">Contact Sales</button>
        </div>
        <div class="hero-content  flex-1 flex flex-col justify-end items-end">
            <img src="{{asset("assets/images/hero.svg")}}" class="w-full h-full" alt="">
        </div>
    </section>

    {{--     Section 1 --}}
    <section class="bg-light px-4 py-16 md:p-16 xl:p-28 mt-10">
        <div class="container mx-auto flex flex-col ">
            <div class="flex mb-10  ">
                <div class="md:w-1/2">
                    <h2 class="font-visuletProLight text-3xl md:text-5xl leading-[1.15] ">
                        Empowering with
                        <mark class="font-semibold">businesses
                            to redefine Payment Infra
                        </mark>
                        technology .
                    </h2>
                </div>
            </div>
            <div class="flex flex-col md:flex-row my-12 gap-5">
                <div class="flex-1" data-aos="fade-up" data-aos-delay="100">
                    <h5 class="font-[500] text-xl">Multi-Asset Custody </h5>
                    <p data-ninja-font="inter_regular_normal" class="">Power your finances with our personalized
                        platform designe
                        d for
                        Multi asset servicing, seamlessly Connecting traditional and
                        digital financial ecosystems.</p>
                </div>
                <div class="flex-1" data-aos="fade-up" data-aos-delay="200">
                    <h5 class="font-[500] text-xl">Connected to Global Markets</h5>
                    <p data-ninja-font="inter_regular_normal" class="">Enhance your financial reach using our connected
                        platform, bridging you to intermediaries for global market access.</p>
                </div>
                <div class="flex-1" data-aos="fade-up" data-aos-delay="300">
                    <h5 class="font-[500] text-xl">Complementary Services
                    </h5>
                    <p data-ninja-font="inter_regular_normal" class="">Enhance your experience with our integrated
                        approach to FX and OTC - all ancillary to your custodial or trust account.</p>
                </div>

            </div>
        </div>
    </section>

    {{--     Section 2 --}}
    <section class="container mx-auto px-4 md:p-10 mt-10">
        <div class="flex flex-col mb-10 gap-14 ">
            <div class="md:w-1/2">
                <h2 class="font-visuletProLight  text-3xl md:text-6xl ">
                    How do
                    <span class="font-semibold">we differ</span>
                    from the rest.
                </h2>
                <p class="text-xl mt-4">
                    In an ever-evolving financial landscape, it's vital to partner with a team that not only understands
                    the intricacies but also stands out in its approach.
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 md:w-2/3 mx-auto">
                <!-- Top Left -->
                <div class="flex flex-col items-center justify-center p-8 border-b md:border-b-0 md:border-r">
                    <div class="mb-4">
                        <!-- Icon 1 -->
                        <img src="{{asset('assets/images/relationship.svg')}}" alt="">
                    </div>
                    <h3 class="text-lg font-semibold">Relationship-driven</h3>
                    <p class="text-gray-600 mt-2 text-center">
                        Our team of experts are committed to ensuring the best for our clients, providing innovative
                        solutions to ensure the best outcomes.
                    </p>
                </div>

                <!-- Top Right -->
                <div class="flex flex-col items-center justify-center p-8  md:border-l md:border-black">
                    <div class="mb-4">
                        <!-- Icon 2 -->
                        <img src="{{asset('assets/images/vertically_integrated.svg')}}" alt="">

                    </div>
                    <h3 class="text-lg font-semibold">Vertically integrated</h3>
                    <p class="text-gray-600 mt-2 text-center">
                        Our connectivity to traditional and next-generation financial services make us a one-stop-shop
                        for any project.
                    </p>
                </div>

                <!-- Bottom Left -->
                <div class="flex flex-col items-center justify-center p-8  md:border-t md:border-black">
                    <div class="mb-4">
                        <!-- Icon 3 -->
                        <img src="{{asset('assets/images/bridget.svg')}}" alt="">

                    </div>
                    <h3 class="text-lg font-semibold">Bridge to digital assets</h3>
                    <p class="text-gray-600 mt-2 text-center">
                        We acknowledge the growing significance of digital assets. As a regulated entity and experienced
                        fiduciary, we're equipped with the capabilities to safely store a wide array of digital assets,
                        assisting in navigating the evolving digital asset landscape.
                    </p>
                </div>

                <!-- Bottom Right -->
                <div class="flex flex-col items-center justify-center p-8 border-l  md:border-t md:border-black">
                    <div class="mb-4">
                        <!-- Icon 4 -->
                        <img src="{{asset('assets/images/love_techies.svg')}}" alt="">

                    </div>
                    <h3 class="text-lg font-semibold">Loved by techies</h3>
                    <p class="text-gray-600 mt-2 text-center">
                        We prioritize better ways of working together by adopting technology solutions that improve
                        workflow, enhance compliance oversight, and help our clients deliver a better experience for
                        their end-customers.
                    </p>
                </div>
            </div>

        </div>

    </section>


    {{--     Section 3 Partners --}}

    <section class="bg-black p-4 py-24 md:p-16 xl:p-28 mt-10">
        <div class="container mx-auto flex flex-col md:flex-row md:justify-between ">
            <div class="flex-1 mb-10  ">
                <div class="md:p-10">
                    <h2 class="font-visuletProLight text-3xl md:text-5xl leading-[1.15]  text-white">
                        <span class="font-bold "> Friends with everyone</span> you know.
                    </h2>
                </div>
            </div>
            <div class="flex-1 mb-10  ">
                <div class="md:p-10">
                    <p class="text-white">
                        Our ecosystem of best-in-class players includes technology providers, asset managers, law firms,
                        consultants and advisors, all supporting our clients to get things done.
                    </p>
                </div>
            </div>
        </div>


        <div class="logos flex flex-row  gap-6 overflow-x-auto">
            <div class="brand w-full  p-6 shrink-0 md:shrink">
                <img src="{{asset('assets/images/partner1.svg')}}" alt="" class="w-full h-auto">
            </div>
            <div class="brand w-full  p-6 shrink-0  md:shrink">
                <img src="{{asset('assets/images/partner2.svg')}}" alt="" class="w-full h-auto">
            </div>
            <div class="brand w-full  p-6 shrink-0  md:shrink">
                <img src="{{asset('assets/images/partner3.svg')}}" alt="" class="w-full h-auto">
            </div>
            <div class="brand w-full  p-6 shrink-0  md:shrink">
                <img src="{{asset('assets/images/partner4.svg')}}" alt="" class="w-full h-auto">
            </div>
        </div>
    </section>


    {{--     Section 4  --}}
    <section class="container mx-auto px-4 py-24 md:p-10 mt-10">
        <div class="flex flex-col mb-10 gap-14 ">
            <div class="md:w-2/3">
                <h2 class="font-visuletProLight  text-3xl md:text-6xl ">
                    Harness the power of
                    <span class="font-semibold">cutting-edge technology.</span>
                </h2>
                <p class="text-xl mt-4">
                    In an ever-evolving financial landscape, it's vital to partner with a team that not only understands
                    the intricacies but also stands out in its approach.
                </p>
            </div>

        </div>

        <div class="cards flex gap-3 flex-wrap">

            @include('_components.card',['image' =>"assets/images/client_portal_card.svg",'title'=>'Global Market Access','descriptiopn' =>"We connect you to global market access, ensuring that your investment opportunities are
                        accessible to all",'actionText' => "SIGN IN →" ])

            @include('_components.card',['image' =>"assets/images/client_portal_card.svg",'title'=>'Open Trust™ APIs','descriptiopn' =>"Your ideas, our tech—embed our services directly into your app, fintech platform or back-office operations and deliver exceptional results for your users.",'actionText' => "LEARN MORE →","theme"=>"dark","bg" => "black" ])
        </div>

    </section>


    {{--     Section 5  --}}
    <section class=" mx-auto px-4 py-24 md:p-28 mt-10 bg-light">
        <div class="container flex flex-col justify-center items-center gap-14">
            <h1 class="text-4xl md:text-6xl text-center md:text-start"><span class="font-semibold ">We're ready </span>when you are. </h1>
            <p class="text-2xl text-center">Talk to our experts today to learn more about our trustee services and how we can help you launch and grow your financial services business.</p>
            <button class="btn-dark btn-lg">Start a Conversation</button>
        </div>

    </section>


    {{--     Section 6  --}}
    <section class=" mx-auto px-4 md:p-10 mt-20 ">
        <div class="container flex flex-col justify-center ">
            <div class="md:w-1/2"><h1 class="text-3xl md:text-5xl">Featured <span class="font-semibold ">news and insights.</span>. </h1></div>
        </div>
        <div class="cards flex gap-3 flex-wrap mt-10">
            @include('_components.news_card',['image' =>"assets/images/news1.webp",'title'=>'Global Market Access','footerText' => "6 February 2023","theme"=>"light","bg" => "tranparent" ])

            @include('_components.news_card',['image' =>"assets/images/news2.webp",'title'=>'Guide to International Digital Assets Regulations','footerText' => "6 February 2023","theme"=>"light","bg" => "tranparent" ])

            @include('_components.news_card',['image' =>"assets/images/news3.jpg",'title'=>'Guide to International Digital Assets Regulations','footerText' => "6 February 2023","theme"=>"light","bg" => "tranparent" ])
        </div>
        <hr class="my-20">
    </section>

    <footer class="bg-white py-8 mt-32">
        <div class="max-w-7xl mx-auto px-4 flex flex-wrap">
            <!-- Left section -->
            <div class="w-full md:w-1/2 lg:w-1/3 mb-8 md:mb-0">
                <img src="{{asset('assets/images/logo.svg')}}" alt="First Digital" class="h-20 mb-4">
                <p class="text-gray-500">&copy; 2024 First Digital. All rights reserved.</p>
                <p class="mt-4 text-gray-700">Join our email list to receive articles, tips from industry experts, and more.</p>
                <div class="mt-4 flex">
                    <input type="email" placeholder="Enter your email" class="border border-gray-300 px-4 py-2 w-full">
                    <button class="bg-black text-white px-4 py-2">
                        <span>&#10140;</span>
                    </button>
                </div>
                <p class="mt-4 text-gray-500 text-sm">By submitting this form, you acknowledge that you have reviewed the terms of our <a href="#" class="text-gray-700 underline">Privacy Policy</a>.</p>
            </div>

            <!-- Right section -->
            <div class="w-full md:w-1/2 lg:w-2/3 flex flex-wrap justify-between text-black">
                <div class="mb-6">
                    <h3 class="font-bold text-black">Solutions</h3>
                    <ul class="mt-2 space-y-2">
                        <li><a href="#" class="text-black block">Open Trust APIs</a></li>
                    </ul>
                </div>
                <div class="mb-6">
                    <h3 class="font-bold text-black">Company</h3>
                    <ul class="mt-2 space-y-2">
                        <li><a href="#" class="text-black block">About Us</a></li>
                        <li><a href="#" class="text-black block">Careers</a></li>
                        <li><a href="#" class="text-black block">Contact Us</a></li>
                        <li><a href="#" class="text-black block">News & Insights</a></li>
                    </ul>
                </div>
                <div class="mb-6">
                    <h3 class="font-bold text-black">Compliance</h3>
                    <ul class="mt-2 space-y-2">
                        <li><a href="#" class="text-black block">Privacy Policy</a></li>
                        <li><a href="#" class="text-black block">Legal & Regulatory</a></li>
                        <li><a href="#" class="text-black block">Security</a></li>
                    </ul>
                </div>
                <div class="mb-6">
                    <h3 class="font-bold text-black">Social</h3>
                    <ul class="mt-2 space-y-2">
                        <li><a href="#" class="text-black block">LinkedIn</a></li>
                        <li><a href="#" class="text-black block">Twitter</a></li>
                        <li><a href="#" class="text-black block">YouTube</a></li>
                        <li><a href="#" class="text-black block">Crunchbase</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

</div>


</body>

<script src="{{asset("assets/js/aos.js")}}"></script>
<script src="{{asset("assets/js/script.js")}}"></script>

</html>
