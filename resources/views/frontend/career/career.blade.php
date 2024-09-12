@extends('_partials.app',['title' => 'ADLEF GROUP | Careers','description' => 'Explore the exciting career opportunities at ADLEF Group, Asia\'s innovative trust company. Learn more about the available positions and our unique work culture.'])

@section('content')
    {{-- Section 1 --}}
    <section class="container mx-auto">
        <div class="mx-auto px-4 md:p-10 mt-10 flex flex-col mb-10 gap-14">
            <div class="md:w-1/2">
                <h2 class="font-visuletProLight text-3xl md:text-6xl">
                    We're <span class="font-bold">recruiting</span>
                </h2>
                <p class="text-xl mt-4">
                    As a leader in tech innovation across Asia, the career paths at ADLEF Group are limitless. Explore the diverse job roles we offer and gain insight into the vibrant workplace culture that sets us apart.
                </p>
            </div>

            <div class="slider w-full overflow-hidden">
                <div class="flex gap-3 w-full " data-0="transform: translateX(0%);" data-1000="transform: translateX(-100%);">
                    <div class="shrink-0 w-full md:w-1/3 h-52">
                        <img class="h-full object-cover" src="{{asset('assets/images/career1.avif')}}" alt="">
                    </div>
                    <div class="shrink-0 w-full md:w-1/3 h-52">
                        <img  class="h-full object-cover" src="{{asset('assets/images/career2.webp')}}" alt="">
                    </div>
                    <div class="shrink-0 w-full md:w-1/3 h-52">
                        <img class="h-full object-cover" src="{{asset('assets/images/career3.jpg')}}" alt="">
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Section 2 --}}
    <section class="px-6 py-12 bg-[#e0e9f0]">
        <div class="container mx-auto">
            <h2 class="text-4xl font-bold">A workplace built on cooperation and  <span class="text-black">constructive feedback</span></h2>
            <p class="mt-4 text-lg">
                We conduct our Employee Engagement Survey twice a year to truly hear our team's feedback and ensure we're creating a workplace that inspires long-term commitment
            </p>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mt-8">
                <!-- Working at ADLEF -->
                <div class="bg-white p-6">
                    <h3 class="font-bold text-lg">Life at ADLEF Group
 </h3>
                    <div class="progress-bar h-3 w-full bg-gray-200 my-3">
                        <div class="h-full w-3/4 bg-green-400"></div>
                    </div>
                    <div class="mt-4">
                        <p class="text-2xl font-semibold">88%</p>
                        <p class="text-sm">
                            of our employees take pride in being a part of ADLEF Group.</p>
                    </div>
                    <div class="progress-bar h-3 w-full bg-gray-200 my-3">
                        <div class="h-full w-3/4 bg-green-400"></div>
                    </div>
                    <div class="mt-4">
                        <p class="text-2xl font-semibold">83%</p>
                        <p class="text-sm">would recommend working here to others.</p>
                    </div>
                </div>
                <!-- Innovation -->
                <div class="bg-white p-6">
                    <h3 class="font-bold text-lg">Creativity and Innovation</h3>
                    <div class="progress-bar h-3 w-full bg-gray-200 my-3">
                        <div class="h-full w-3/4 bg-blue-100"></div>
                    </div>
                    <p class="mt-4 text-2xl font-semibold">79%</p>
                    <p class="text-sm">feel they are supported in thinking creatively and innovatively .</p>
                </div>
                <!-- Work-Life Balance -->
                <div class="bg-white p-6">
                    <h3 class="font-bold text-lg">Work-Life Harmony</h3>
                    <div class="progress-bar h-3 w-full bg-gray-200 my-3">
                        <div class="h-full w-3/4 bg-blue-600"></div>
                    </div>
                    <p class="mt-4 text-2xl font-semibold">82%</p>
                    <p class="text-sm">feel they maintain a healthy balance between their work and personal life..</p>
                </div>
                <!-- Belonging -->
                <div class="bg-white p-6">
                    <h3 class="font-bold text-lg">Sense of Community</h3>
                    <div class="progress-bar h-3 w-full bg-gray-200 my-3">
                        <div class="h-full w-3/4 bg-green-500"></div>
                    </div>
                    <p class="mt-4 text-2xl font-semibold">78%</p>
                    <p class="text-sm">experience a profound sense of belonging within ADLEF Group. </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Section 3 --}}
    <section class="bg-[#e0e9f0] px-6 py-12">
        <div class="container mx-auto">
            <div class="w-full md:w-1/2">
                <h2 class="text-5xl">We celebrate <span class="text-black font-bold">commitment and hard work.</span></h2>
                <p class="mt-4 text-lg">
                    Our comprehensive benefits showcase our dedication to supporting every aspect of our employees' lives, with packages tailored to a variety of needs.
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-8">
                <!-- Benefit 1 -->
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h3 class="font-bold text-lg flex items-center"><span class="material-symbols-outlined text-green-500">check_circle</span>&nbsp; Attractive salary</h3>
                    <p class="mt-4 text-sm">We continuously evaluate our pay scales to ensure they are in line with industry standards.</p>
                </div>
                <!-- Benefit 2 -->
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h3 class="font-bold text-lg flex items-center"><span class="material-symbols-outlined text-green-500">check_circle</span>&nbsp; Comprehensive benefits package</h3>
                    <p class="mt-4 text-sm">Our package includes health insurance, retirement savings, and personalized allowances each month.</p>
                </div>
                <!-- Benefit 3 -->
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h3 class="font-bold text-lg flex items-center"><span class="material-symbols-outlined text-green-500">check_circle</span>&nbsp; Flexible leave policies</h3>
                    <p class="mt-4 text-sm">We offer various leave options to help you maintain a healthy work-life balance.</p>
                </div>
                <!-- Benefit 4 -->
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h3 class="font-bold text-lg flex items-center"><span class="material-symbols-outlined text-green-500">check_circle</span>&nbsp; Educational support</h3>
                    <p class="mt-4 text-sm">We cover 100% of expenses for job-related courses to enhance your professional development.</p>
                </div>
                <!-- Benefit 5 -->
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h3 class="font-bold text-lg flex items-center"><span class="material-symbols-outlined text-green-500">check_circle</span>&nbsp; Matching donations</h3>
                    <p class="mt-4 text-sm">We match your charitable contributions and acknowledge hours spent volunteering.</p>
                </div>
                <!-- Benefit 6 -->
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h3 class="font-bold text-lg flex items-center"><span class="material-symbols-outlined text-green-500">check_circle</span>&nbsp; Social events</h3>
                    <p class="mt-4 text-sm">Join us for engaging company events where you can connect and unwind with your teammates.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Section 4 --}}
    <section class="bg-white px-6 py-12">
        <div class="container mx-auto">
            <h2 class="text-5xl">Explore Our <span class="text-black font-bold">Career Opportunities</span></h2>

            <div class="flex w-full gap-3 flex-col mt-8">
                <div class="bg-gray-100 p-6 w-full">
                    <div class="flex items-center">
                        <h3 class="font-bold text-lg">Attractive Compensation Packages</h3>
                    </div>
                    <p class="mt-4 text-sm">Our salary packages are designed to be competitive and reflect current market trends.</p>
                </div>

                <div class="bg-gray-100 p-6 w-full mt-4">
                    <div class="flex items-center">
                        <h3 class="font-bold text-lg">Extensive Health Coverage</h3>
                    </div>
                    <p class="mt-4 text-sm">Select from a range of health plans tailored to meet your and your family’s needs.</p>
                </div>

                <div class="bg-gray-100 p-6 w-full mt-4">
                    <div class="flex items-center">
                        <h3 class="font-bold text-lg">Adaptive Work Hours</h3>
                    </div>
                    <p class="mt-4 text-sm">We support various flexible work arrangements to accommodate your personal needs.</p>
                </div>

            </div>
        </div>
    </section>

    {{-- Section 4: FAQ --}}
    <section class="bg-black px-6 py-12">
        <div class="container mx-auto p-10">
            <div class="flex">
                <div class="md:flex-1">
                    <h3 class="text-white font-semibold text-2xl inline-block"> <span class="material-symbols-outlined text-white">trending_down</span>How can I apply for a role at ADLEF Group?</h3>
                    <p class="text-white opacity-80">You can explore open positions on our career page or through external job platforms like LinkedIn. Each job listing will provide details on the application process.</p>
                </div>
                <div class="md:flex-1 p-10 flex justify-center items-center">
                    <img class="w-3/4 h-3/4" src="{{asset('assets/images/faq.svg')}}" alt="">
                </div>
            </div>
        </div>
    </section>

@endsection

@section('pre-script')
    <script src="{{ asset('assets/js/skrollr.js') }}"></script>

    <script>
        skrollr.init();
    </script>
@endsection
