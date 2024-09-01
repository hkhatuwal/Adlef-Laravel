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
            <button class="btn-dark btn-lg"><a href="{{route('frontend.contact-us.business-enquiry')}}">Contact Sales</a></button>
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
                    Ready to <mark class="font-semibold">take</mark> your <mark class="font-semibold">businesses</mark> to the next level ?
                </h2>

                <p>
                    We take great satisfaction in providing specialized solutions to a wide spectrum of clients in various industries. Our platform is made to change with the specific requirements of companies of all kinds, from start-ups to global giants
                </p>
            </div>
            <div class="flex flex-col md:flex-row my-12 gap-5">
                <div class="flex-1" data-aos="fade-up" data-aos-delay="100">
                    <h5 class="font-[500] text-xl">By Department </h5>
                    <p class="inter_regular_normal">
                        We tailor our financial solutions to meet the needs of various departments within your organization. Whether you're looking to enhance treasury operations or streamline IT financial management, our solutions can be seamlessly integrated into your existing operations. By adopting this approach, you can achieve a more cohesive and coordinated financial strategy that meets the specific needs of each department.


                    </p>
                </div>
                <div class="flex-1" data-aos="fade-up" data-aos-delay="200">
                    <h5 class="font-[500] text-xl">By Industry</h5>
                    <p class="inter_regular_normal">
                        Tailored to meet the distinct needs of various industries, providing specialized support across multiple sectors.
                        We cover a wide range of fields, including Retail, Financial Services, Healthcare, Financial Education, and Manufacturing. Each industry has its unique challenges and requirements, and our solutions are designed to address these with precision.


                    </p>
                </div>
                <div class="flex-1" data-aos="fade-up" data-aos-delay="300">
                    <h5 class="font-[500] text-xl">By Business</h5>
                    <p class="inter_regular_normal">
                        Designed to cater to the diverse needs of various types of businesses, ensuring that we provide relevant and effective financial management regardless of size or sector.
                        Whether you’re a growing startup looking to establish robust financial processes, a large organization needing sophisticated financial solutions, a bank requiring advanced compliance measures, or a public sector entity managing complex budgets, our platform is equipped to address your specific requirements with precision and efficiency.

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
                    What makes <span class="font-semibold">us</span> different?

                </h2>
                <p class="text-xl mt-4">
                    We don’t just offer financial solutions — we transform the way businesses manage their treasury and operations. Our unique blend of cutting-edge technology, industry expertise, and client-first approach sets us apart in the marke
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 md:w-2/3 mx-auto">
                <div class="flex flex-col items-center justify-center p-8 border-b md:border-b-0 md:border-r">
                    <img src="{{ asset('assets/images/relationship.svg') }}" alt="Relationship-driven" class="mb-4 text-primary">
                    <h3 class="text-lg font-semibold text-center">Tailored Solutions for Every Need</h3>
                    <p class="text-gray-600 mt-2 text-center">
                        We understand that every business is different. That's why we offer personalized solutions that cater to your unique requirements, whether you’re optimizing treasury operations, enhancing risk management, or improving financial visibility.
                    </p>
                </div>

                <div class="flex flex-col items-center justify-center p-8 md:border-l md:border-black">
                    <img src="{{ asset('assets/images/vertically_integrated.svg') }}" alt="Vertically integrated"
                         class="mb-4">
                    <h3 class="text-lg font-semibold">Innovative Technology</h3>
                    <p class="text-gray-600 mt-2 text-center">
                        Our platform leverages the latest advancements in digital finance to simplify and streamline your operations. From real-time data analytics to AI-driven insights, we equip you with the tools you need to stay ahead in a rapidly changing landscape.
                    </p>
                </div>

                <div class="flex flex-col items-center justify-center p-8 md:border-t md:border-black">
                    <img src="{{ asset('assets/images/bridget.svg') }}" alt="Bridge to digital assets" class="mb-4">
                    <h3 class="text-lg font-semibold">Trusted Global Network</h3>
                    <p class="text-gray-600 mt-2 text-center">
                        With a presence across key global markets, we ensure your business has access to a broad network of financial partners, intermediaries, and experts. Our extensive reach empowers you to act quickly and decisively, no matter where you operate.
                    </p>
                </div>

                <div class="flex flex-col items-center justify-center p-8 border-l md:border-t md:border-black">
                    <img src="{{ asset('assets/images/love_techies.svg') }}" alt="Loved by techies" class="mb-4">
                    <h3 class="text-lg font-semibold">Empowering Collaboration Through  Technology</h3>
                    <p class="text-gray-600 mt-2 text-center">
                        Beloved by tech enthusiasts, we focus on adopting technology solutions that streamline workflows and strengthen compliance oversight, ensuring more efficient and effective collaboration
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

        <div class="logos flex flex-row gap-6 overflow-x-auto items-center">
            @foreach(['uplef.png', 'ubs.png', 'bitgo.webp', 'token.com.svg', 'biget.svg','creed.webp'] as $partner)
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
                'description' => 'We provide seamless access to global markets, ensuring that your business can capitalize on every opportunity, no matter where it arises. From investment strategies to market entry solutions, we ensure that your global financial operations are efficient and effective.',
                'actionText' => 'SIGN IN →'
            ])

            @include('_components.card', [
                'image' => "assets/images/client_portal_card_2.svg",
                'title' => 'Open Trust™ APIs',
                'description' => 'Your business, your rules. With our custom APIs, integrate our services directly into your platform, whether it\'s an app, a fintech solution, or a back-office system. We deliver unparalleled functionality that aligns perfectly with your business needs.
',
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
            <button class="btn-dark btn-lg"><a href="{{route('frontend.contact-us.business-enquiry')}}">Start a Conversation</a></button>
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
            <div class="cards flex gap-3  mt-10 overflow-scroll">
                @foreach($topNews as $post)
                    @include('_components.news_card', [
                        'image' => 'storage/' . $post->image,
                        'title' => $post->title,
                        'footerText' => $post->label,
                        'theme' => 'light',
                        'bg' => 'transparent'
                    ])
                @endforeach
            </div>
        </div>
    </section>

@endsection
