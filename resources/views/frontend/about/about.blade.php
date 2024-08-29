@extends('_partials.app',['title' => 'ADLEF GROUP | ABOUT','description' => '  First Digital brings traditional fiduciary services into the digital-first world through technology and
                developing financial services infrastructure that lets us and our clients create world-class financial
                products and services.','image' => asset('assets/images/building.avif')])

@section('content')

    <!-- Mission Section -->
    <section >
        <div class="py-16  container px-4 md:px-10 mx-auto">
            <h2 class="text-5xl  mb-6 font-visuletProLight ">Our <span class="text-black font-bold">mission</span></h2>
            <p class="text-3xl leading-relaxed font-visuletProLight ">
                At Adlef Group, our mission is to Empower  Global Businesses with cutting- edge Digital Technology.
            </p>
        </div>
    </section>

    <!-- Story Section -->
    <section>
        <div class="py-16  container px-4 md:px-10 mx-auto font-visuletProLight ">
            <h2 class="text-4xl font-bold mb-6">Our <span class="text-black">story</span></h2>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div>
                    <h4 class="font-semibold text-xl">It All Begins with an Idea
                    </h4>
                    <p class="text-lg leading-relaxed mb-4">
                        With extensive experience in international trade, our senior leaders identified the need for advanced technology in global markets. Recognizing the challenges businesses faced in a digital economy, we combined traditional finance with digital assets to create open finance solutions. This led to the founding of ADLEF GROUP in 2019, now a multinational company headquartered in Dubai.

                    </p>
                    <img src="{{asset('assets/images/office.webp')}}" alt="Bridge Image"
                         class="w-full h-auto object-cover">
                </div>
                <div class="space-y-6 ">
                    <img src="{{asset('assets/images/building.avif')}}" alt="Building Image"
                         class="w-full h-full object-cover  ">

                </div>
            </div>

        </div>
    </section>


    <section class="bg-black p-4 py-24 md:p-16 xl:p-28 mt-10">
        <div class="container logos flex flex-row  gap-6 overflow-x-auto mx-auto">
            <div class="logos flex flex-row gap-6 overflow-x-auto items-center">
                @foreach(['uplef.png', 'ubs.png', 'bitgo.webp', 'token.com.svg', 'biget.svg','creed.webp'] as $partner)
                    <div class="brand w-full p-6 shrink-0 md:shrink">
                        <img src="{{ asset("assets/images/$partner") }}" alt="Partner Logo" class="w-full h-auto">
                    </div>
                @endforeach
            </div>
        </div>
    </section>


    <section >

        <div class="container mx-auto overflow-hidden">
            <div class="py-16   px-4 md:px-10 mx-auto font-visuletProLight ">
                <h2 class="text-5xl  mb-6 font-visuletProLight ">Senior <span class="text-black font-bold">leadership</span>
                </h2>
                <p class="text-3xl leading-relaxed font-visuletProLight ">With a unique mix of technology and finance
                    leadership (and some personal experience of our own), we bring a new perspective to a centuries-old
                    industry.
                </p>
            </div>
            <!-- Slider main container -->
            <div class="leadership-wrapper w-[90%] mx-auto">
                <div class="swiper">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide ">
                            <div class=" bg-white  ">
                                <img class="w-full object-cover" src="https://placehold.co/350x350"
                                     alt="Profile Image">
                                <div class="p-6">
                                    <h2 class="text-lg font-semibold text-gray-800">Gunnar Jaerv</h2>
                                    <p class="text-sm text-gray-600">Chief Operating Officer</p>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide ">
                            <div class=" bg-white  ">
                                <img class="w-full object-cover" src="https://placehold.co/350x350"
                                     alt="Profile Image">
                                <div class="p-6">
                                    <h2 class="text-lg font-semibold text-gray-800">Gunnar Jaerv</h2>
                                    <p class="text-sm text-gray-600">Chief Operating Officer</p>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide ">
                            <div class=" bg-white  ">
                                <img class="w-full object-cover" src="https://placehold.co/350x350"
                                     alt="Profile Image">
                                <div class="p-6">
                                    <h2 class="text-lg font-semibold text-gray-800">Gunnar Jaerv</h2>
                                    <p class="text-sm text-gray-600">Chief Operating Officer</p>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide ">
                            <div class=" bg-white  ">
                                <img class="w-full object-cover" src="https://placehold.co/350x350"
                                     alt="Profile Image">
                                <div class="p-6">
                                    <h2 class="text-lg font-semibold text-gray-800">Gunnar Jaerv</h2>
                                    <p class="text-sm text-gray-600">Chief Operating Officer</p>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide ">
                            <div class=" bg-white  ">
                                <img class="w-full object-cover" src="https://placehold.co/350x350"
                                     alt="Profile Image">
                                <div class="p-6">
                                    <h2 class="text-lg font-semibold text-gray-800">Gunnar Jaerv</h2>
                                    <p class="text-sm text-gray-600">Chief Operating Officer</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="flex justify-end">
                    <div class="bg-black w-auto p-5 ml-auto  inline-block ">
                        <span class="swiper-button-prev-custom relative text-white material-symbols-outlined px-5">arrow_forward_ios </span>
                        <div class="swiper-button-next-custom relative text-white material-symbols-outlined px-5">
                            arrow_back_ios
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <section >
       <div class="container mx-auto p-4 py-24 md:p-16 xl:p-28 mt-10">
           <h2 class="text-3xl font-bold  ">
               Core <span class="text-black">values</span>
           </h2>
           <p class="text-lg text-gray-600 mb-8">
               We specialize in next-generation treasury solutions while focusing on customer success, employee support, and community benefits. Our ESG program ensures a positive impact on employees, customers, communities, and the planet.
           </p>

           <!-- Values Container -->
           <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
               <!-- Value 1 -->
               <div class="bg-black text-white p-6 flex items-center justify-center">
                   <div class="text-center">
                       <div class="mb-2">
                           <!-- Replace with your icon -->
                           <span class="text-3xl">🏁</span>
                       </div>
                       <h3 class="text-xl font-semibold">Get things done</h3>
                   </div>
               </div>

               <!-- Value 2 -->
               <div class="bg-black text-white p-6 flex items-center justify-center">
                   <div class="text-center">
                       <div class="mb-2">
                           <!-- Replace with your icon -->
                           <span class="text-3xl">🔧</span>
                       </div>
                       <h3 class="text-xl font-semibold">Get things right</h3>
                   </div>
               </div>

               <!-- Value 3 -->
               <div class="bg-black text-white p-6 flex items-center justify-center md:col-span-2">
                   <div class="text-center">
                       <div class="mb-2">
                           <!-- Replace with your icon -->
                           <span class="text-3xl">⚡</span>
                       </div>
                       <h3 class="text-xl font-semibold">Change things</h3>
                   </div>
               </div>
           </div>
       </div>
    </section>

    <section >
        <div class="flex flex-col md:flex-row items-center justify-center h-screen">
            <!-- Left Side: Text Content -->
            <div class="w-full md:w-1/2 bg-black h-full text-white p-8 flex flex-col justify-center">
                <h1 class="text-5xl  mb-4">
                    Join us. <br> <span class="text-white font-bold">We're hiring.</span>
                </h1>
                <p class="text-lg mb-8">
                    If you’re looking for the challenge of an innovative, fast-growing company, in an industry that’s transforming around you and disrupting traditional finance, in a role that could make your career, First Digital is looking for you.
                </p>
                <button class="btn-secondary text-black inline-block">
                    Browse Careers
                </button>
            </div>

            <!-- Right Side: Image -->
            <div class="w-full md:w-1/2 h-full">
                <img class="w-full h-full " src="{{asset('assets/images/working.svg')}}" alt="Hiring Image">
            </div>
        </div>
    </section>


@endsection
