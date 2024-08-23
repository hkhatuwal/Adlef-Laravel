@extends('_partials.app',['title' => 'ADLEF GROUP | Careers','description' => 'As Asia\'s tech-forward trust company, the career possibilities at First Digital are endless. Discover the variety of job opportunities available at First Digital. Plus, take an inside look at the company culture that makes our company so unique.'])
@section('content')
    {{-- Section 1--}}
    <section class="container mx-auto">
        <div class=" mx-auto px-4 md:p-10 mt-10 flex flex-col mb-10 gap-14 ">
            <div class="md:w-1/2">
                <h2 class="font-visuletProLight  text-3xl md:text-6xl ">
                    We're <span class="font-bold">hiring</span>
                </h2>
                <p class="text-xl mt-4">
                    As Asia's tech-forward trust company, the career possibilities at First Digital are endless.
                    Discover the variety of job opportunities available at First Digital. Plus, take an inside look at
                    the company culture that makes our company so unique.
                </p>
            </div>

            <div class="slider w-full overflow-hidden">
                <div class="flex gap-3 w-full" data-0="transform: translateX(0%);"
                     data-1000="transform: translateX(-100%);">
                    <div class="shrink-0 w-full md:w-1/3">
                        <img src="{{asset('assets/images/career0.jpg')}}" alt="">
                    </div>
                    <div class="shrink-0 w-full md:w-1/3">
                        <img src="{{asset('assets/images/career1.jpg')}}" alt="">
                    </div>
                    <div class="shrink-0 w-full md:w-1/3">
                        <img src="{{asset('assets/images/career2.jpg')}}" alt="">
                    </div>
                    <div class="shrink-0 w-full md:w-1/3">
                        <img src="{{asset('assets/images/career3.jpg')}}" alt="">
                    </div>
                    <div class="shrink-0 w-full md:w-1/3">
                        <img src="{{asset('assets/images/career4.jpg')}}" alt="">
                    </div>
                    <div class="w-full md:w-1/3">
                        <img src="{{asset('assets/images/career5.jpg')}}" alt="">
                    </div>
                </div>
            </div>

        </div>
    </section>


    {{-- Section 2--}}
    <section class="px-6 py-12 bg-[#e0e9f0]">
        <div class="container mx-auto">
            <h2 class="text-4xl font-bold">Culture of <span class="text-black">feedback and collaboration.</span></h2>
            <p class="mt-4 text-lg">We run our Employee Engagement Survey bi-annually so that we can really listen to
                what our people have to tell us and make sure that we’re building a work environment that makes them
                want to stay.</p>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mt-8">
                <!-- Working at First Digital -->
                <div class="bg-white p-6 ">
                    <h3 class="font-bold text-lg">Working at First Digital</h3>
                    <div class="progress-bar h-3 w-full bg-gray-200 my-3">
                        <div class="h-full w-3/4 bg-green-400"></div>
                    </div>
                    <div class="mt-4">
                        <p class="text-2xl font-semibold">88%</p>
                        <p class="text-sm">of our employees are proud to work for First Digital.</p>
                    </div>
                    <div class="progress-bar h-3 w-full bg-gray-200 my-3">
                        <div class="h-full w-3/4 bg-green-400"></div>
                    </div>
                    <div class="mt-4">
                        <p class="text-2xl font-semibold">83%</p>
                        <p class="text-sm">would recommend First Digital as a great place to work.</p>
                    </div>
                </div>
                <!-- Innovation -->
                <div class="bg-white p-6 ">
                    <h3 class="font-bold text-lg">Innovation</h3>
                    <div class="progress-bar h-3 w-full bg-gray-200 my-3">
                        <div class="h-full w-3/4 bg-blue-100"></div>
                    </div>
                    <p class="mt-4 text-2xl font-semibold">79%</p>

                    <p class="text-sm">feel they are encouraged to be innovative.</p>
                </div>
                <!-- Work-Life Balance -->
                <div class="bg-white p-6 ">
                    <h3 class="font-bold text-lg">Work-Life Balance</h3>
                    <div class="progress-bar h-3 w-full bg-gray-200 my-3">
                        <div class="h-full w-3/4 bg-blue-600"></div>
                    </div>
                    <p class="mt-4 text-2xl font-semibold">82%</p>

                    <p class="text-sm">feel like they have a good work-life blend.</p>
                </div>
                <!-- Belonging -->
                <div class="bg-white p-6 ">
                    <h3 class="font-bold text-lg">Belonging</h3>
                    <div class="progress-bar h-3 w-full bg-gray-200 my-3">
                        <div class="h-full w-3/4 bg-green-500"></div>
                    </div>
                    <p class="mt-4 text-2xl font-semibold">78%</p>
                    <p class="text-sm">feel a sense of belonging at First Digital.</p>
                </div>
            </div>
        </div>
    </section>
    {{--Secition 3--}}
    <section class="bg-[#e0e9f0] px-6 py-12">
        <div class="container mx-auto">
            <div class="w-full md:w-1/2">
                <h2 class="text-5xl  ">We recognize <span class="text-black font-bold">hard work and dedication.</span></h2>
                <p class="mt-4 text-lg">We take a whole-person approach to benefits and perks and this is reflected in our
                    employee-centric package.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-8">
                <!-- Benefit 1 -->
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h3 class="font-bold text-lg flex items-center"><span
                            class="material-symbols-outlined text-green-500">check_circle</span>&nbsp; Competitive base
                        salary</h3>
                    <p class="mt-4 text-sm">We benchmark our salaries to ensure they remain competitive within the
                        region and industry.</p>
                </div>
                <!-- Benefit 2 -->
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h3 class="font-bold text-lg flex items-center"><span
                            class="material-symbols-outlined text-green-500">check_circle</span>&nbsp; Benefits package
                    </h3>
                    <p class="mt-4 text-sm">Medical plan, provident fund contributions and personalized monthly cash
                        allowance.</p>
                </div>
                <!-- Benefit 3 -->
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h3 class="font-bold text-lg flex items-center"><span
                            class="material-symbols-outlined text-green-500">check_circle</span>&nbsp; Generous leave
                        policies</h3>
                    <p class="mt-4 text-sm">Maintain a healthy work-life balance through paid time off options including
                        sick, vacation, parental leave.</p>
                </div>
                <!-- Benefit 4 -->
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h3 class="font-bold text-lg flex items-center"><span
                            class="material-symbols-outlined text-green-500">check_circle</span>&nbsp; Learning stipend
                    </h3>
                    <p class="mt-4 text-sm">We reimburse 100% of all expenses for job-related courses to expand your
                        skills and develop your potential.</p>
                </div>
                <!-- Benefit 5 -->
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h3 class="font-bold text-lg flex items-center"><span
                            class="material-symbols-outlined text-green-500">check_circle</span>&nbsp; Donation matching
                    </h3>
                    <p class="mt-4 text-sm">We match charitable donations and add donations for hours you work as
                        volunteers.</p>
                </div>
                <!-- Benefit 6 -->
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h3 class="font-bold text-lg flex items-center"><span
                            class="material-symbols-outlined text-green-500">check_circle</span>&nbsp; Company sponsored
                        events</h3>
                    <p class="mt-4 text-sm">Eat, drink and be merry while getting to know the people you are defining
                        the future with.</p>
                </div>
            </div>
        </div>
    </section>

    {{--Secition 4--}}
    <section class="bg-white px-6 py-12">
        <div class="container mx-auto">
            <h2 class="text-5xl ">Current <span class="text-black font-bold">opportunities</span></h2>

            <div class="flex w-full gap-3 flex-col">
                <div class="bg-gray-100 p-6 w-full">
                    <div class="flex items-center">
                        <h3 class="font-bold text-lg ">Competitive base salary</h3>
                    </div>
                    <p class="mt-4 text-sm">We benchmark our salaries to ensure they remain competitive within the region and industry.</p>
                </div>

                <div class="bg-gray-100 p-6 w-full mt-4">
                    <div class="flex items-center">

                        <h3 class="font-bold text-lg ">Comprehensive Health Plans</h3>
                    </div>
                    <p class="mt-4 text-sm">We offer a variety of health plans to meet the needs of you and your family.</p>
                </div>

                <div class="bg-gray-100 p-6 w-full mt-4">
                    <div class="flex items-center">

                        <h3 class="font-bold text-lg ">Flexible Work Schedule</h3>
                    </div>
                    <p class="mt-4 text-sm">We support flexible work arrangements that fit your personal needs.</p>
                </div>

            </div>
        </div>
    </section>

    {{--Secition 4 : FAQ--}}

    <section class="bg-black px-6 py-12">
        <div class="container mx-auto p-10">
         <div class="flex">
             <div class="md:flex-1">

                 <h3 class="text-white font-semibold text-2xl inline-block">  <span class="material-symbols-outlined text-white">trending_down</span>How do I apply to join First Digital?</h3>
                 <p class="text-white opacity-80">You can browse for vacancies on our official careers page or external job boards (i.e. LinkedIn). You will discover how to apply in each detailed job description.</p>
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
