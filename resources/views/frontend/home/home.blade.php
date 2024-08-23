@extends('_partials.app',['title' => 'ADLEF GROUP | HOME','description' => ' Power your finances with our personalized platform designed for
                Multi asset servicing, seamlessly Connecting traditional and
                digital financial ecosystems','image' => asset('assets/images/savings.svg')])

@section('content')

    {{-- Hero Section --}}
    <section class="container mx-auto hero flex flex-col-reverse md:flex-row items-center bg-white p-3 md:p-10">
        <div class="hero-content flex-1 flex flex-col items-center md:items-start gap-4">
            <h3 class="text-3xl mt-4 md:text-[3.5rem] font-visuletProLight leading-tight text-center md:text-start">
                Corporate
                <mark class="font-semibold bg-accent">Payment
                    Services for
                </mark>
                for global businesses.
            </h3>
            <p class="text-xl text-center md:text-start">
                Power your finances with our personalized platform designe
                d for
                Multi asset servicing, seamlessly Connecting traditional and
                digital financial ecosystems.
            </p>
            <button class="btn-dark btn-lg">Contact Sales</button>
        </div>
        <div class="hero-content flex-1 flex flex-col justify-end items-end">
            <img src="{{ asset('assets/images/hero.svg') }}" class="w-full h-full" alt="Hero Image">
        </div>
    </section>

    {{-- Section 1 --}}
    <section class="bg-light px-4 py-16 md:p-16 xl:p-28 mt-10">
        <div class="container mx-auto">
            <div class="mb-10 md:w-1/2">
                <h2 class="font-visuletProLight text-3xl md:text-5xl leading-[1.15]">
                    Empowering
                    <mark class="font-semibold">businesses</mark>
                    to
                    <mark class="font-semibold"> redefine Payment Infra technology.</mark>
                </h2>
            </div>
            <div class="flex flex-col md:flex-row my-12 gap-5">
                <div class="flex-1" data-aos="fade-up" data-aos-delay="100">
                    <h5 class="font-[500] text-xl">Multi-Asset Custody</h5>
                    <p class="inter_regular_normal">
                        Power your finances with our personalized platform designed for multi-asset servicing,
                        seamlessly
                        connecting traditional and digital financial ecosystems.
                    </p>
                </div>
                <div class="flex-1" data-aos="fade-up" data-aos-delay="200">
                    <h5 class="font-[500] text-xl">Connected to Global Markets</h5>
                    <p class="inter_regular_normal">
                        Enhance your financial reach using our connected platform, bridging you to intermediaries for
                        global market access.
                    </p>
                </div>
                <div class="flex-1" data-aos="fade-up" data-aos-delay="300">
                    <h5 class="font-[500] text-xl">Complementary Services</h5>
                    <p class="inter_regular_normal">
                        Enhance your experience with our integrated approach to FX and OTC—all ancillary to your
                        custodial
                        or trust account.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Section 2 --}}
    <section class="container mx-auto px-4 md:p-10 mt-10">
        <div class="flex flex-col mb-10 gap-14">
            <div class="md:w-1/2">
                <h2 class="font-visuletProLight text-3xl md:text-6xl">
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
                <div class="flex flex-col items-center justify-center p-8 border-b md:border-b-0 md:border-r">
                    <img src="{{ asset('assets/images/relationship.svg') }}" alt="Relationship-driven" class="mb-4">
                    <h3 class="text-lg font-semibold">Relationship-driven</h3>
                    <p class="text-gray-600 mt-2 text-center">
                        Our team of experts are committed to providing innovative solutions to ensure the best outcomes
                        for our clients.
                    </p>
                </div>

                <div class="flex flex-col items-center justify-center p-8 md:border-l md:border-black">
                    <img src="{{ asset('assets/images/vertically_integrated.svg') }}" alt="Vertically integrated"
                         class="mb-4">
                    <h3 class="text-lg font-semibold">Vertically integrated</h3>
                    <p class="text-gray-600 mt-2 text-center">
                        Our connectivity to traditional and next-generation financial services makes us a one-stop-shop
                        for any project.
                    </p>
                </div>

                <div class="flex flex-col items-center justify-center p-8 md:border-t md:border-black">
                    <img src="{{ asset('assets/images/bridget.svg') }}" alt="Bridge to digital assets" class="mb-4">
                    <h3 class="text-lg font-semibold">Bridge to digital assets</h3>
                    <p class="text-gray-600 mt-2 text-center">
                        We safely store a wide array of digital assets, assisting in navigating the evolving digital
                        asset landscape.
                    </p>
                </div>

                <div class="flex flex-col items-center justify-center p-8 border-l md:border-t md:border-black">
                    <img src="{{ asset('assets/images/love_techies.svg') }}" alt="Loved by techies" class="mb-4">
                    <h3 class="text-lg font-semibold">Loved by techies</h3>
                    <p class="text-gray-600 mt-2 text-center">
                        We prioritize better ways of working together by adopting technology solutions that improve
                        workflow and enhance compliance oversight.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Section 3 Partners --}}
    <section class="bg-black p-4 py-24 md:p-16 xl:p-28 mt-10">
        <div class="container mx-auto flex flex-col md:flex-row md:justify-between">
            <div class="flex-1 mb-10 md:p-10">
                <h2 class="font-visuletProLight text-3xl md:text-5xl leading-[1.15] text-white">
                    <span class="font-bold">Friends with everyone</span> you know.
                </h2>
            </div>
            <div class="flex-1 mb-10 md:p-10">
                <p class="text-white">
                    Our ecosystem of best-in-class players includes technology providers, asset managers, law firms,
                    consultants, and advisors, all supporting our clients to get things done.
                </p>
            </div>
        </div>

        <div class="logos flex flex-row gap-6 overflow-x-auto">
            @foreach(['partner1.svg', 'partner2.svg', 'partner3.svg', 'partner4.svg'] as $partner)
                <div class="brand w-full p-6 shrink-0 md:shrink">
                    <img src="{{ asset("assets/images/$partner") }}" alt="Partner Logo" class="w-full h-auto">
                </div>
            @endforeach
        </div>
    </section>

    {{-- Section 4 --}}
    <section class="container mx-auto px-4 py-24 md:p-10 mt-10">
        <div class="flex flex-col mb-10 gap-14">
            <div class="md:w-2/3">
                <h2 class="font-visuletProLight text-3xl md:text-6xl">
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
            @include('_components.card', [
                'image' => "assets/images/client_portal_card.svg",
                'title' => 'Global Market Access',
                'description' => 'We connect you to global market access, ensuring that your investment opportunities are accessible to all.',
                'actionText' => 'SIGN IN →'
            ])

            @include('_components.card', [
                'image' => "assets/images/client_portal_card.svg",
                'title' => 'Open Trust™ APIs',
                'description' => 'Your ideas, our tech—embed our services directly into your app, fintech platform or back-office operations and deliver exceptional results for your users.',
                'actionText' => 'LEARN MORE →',
                'theme' => 'dark',
                'bg' => 'black'
            ])
        </div>
    </section>

    {{-- Section 5 --}}
    <section class="mx-auto px-4 py-24 md:p-28 mt-10 bg-light">
        <div class="container flex flex-col justify-center items-center gap-14">
            <h1 class="text-4xl md:text-6xl text-center md:text-start">
                <span class="font-semibold">We're ready</span> when you are.
            </h1>
            <p class="text-2xl text-center">
                Talk to our experts today to learn more about our trustee services and how we can help you launch and
                grow your financial services business.
            </p>
            <button class="btn-dark btn-lg">Start a Conversation</button>
        </div>
    </section>

    {{-- Section 6 --}}
    <section class="mx-auto px-4 md:p-10 mt-20">
        <div class="container flex flex-col justify-center">
            <div class="md:w-1/2">
                <h1 class="text-3xl md:text-5xl">
                    Featured <span class="font-semibold">news and insights.</span>
                </h1>
            </div>
        </div>
        <div class="cards flex gap-3 flex-wrap mt-10">
            @foreach(['news1.webp', 'news2.webp', 'news3.jpg'] as $news)
                @include('_components.news_card', [
                    'image' => "assets/images/$news",
                    'title' => 'Guide to International Digital Assets Regulations',
                    'footerText' => '6 February 2023',
                    'theme' => 'light',
                    'bg' => 'transparent'
                ])
            @endforeach
        </div>
    </section>

@endsection
