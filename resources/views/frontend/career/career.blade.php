@extends('_partials.app',['title' => 'ADELF GROUP | Careers','description' => 'Shape your career around what matters to you, in a company where every voice makes a difference. Together, let\'s drive innovation for a more sustainable future.'])

@section('content')
    {{-- Section 1 --}}
    <section class="container mx-auto">
        <div class="mx-auto px-4 md:p-10 mt-10 flex flex-col mb-10 gap-14">
            <div class="md:w-1/2">
                <h2 class="font-visuletProLight text-3xl md:text-6xl">
                    We're <span class="font-bold">hiring</span>
                </h2>
                <p class="text-xl mt-4">
                    Shape your career around what matters to you, in a company where every voice makes a difference. Together, let’s drive innovation for a more sustainable future.
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

    {{-- Section 2: Life at Adelf Group --}}
    <section class="px-6 py-12 bg-[#e0e9f0]">
        <div class="container mx-auto">
            <h2 class="text-4xl font-bold">Life at <span class="text-black">Adelf Group</span></h2>
            <p class="mt-4 text-lg">We’re passionate about our work, but we also believe in enjoying the journey. Whether it's through Hackathons or Team Events, we make time to have fun and connect with amazing people like you.</p>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mt-8">
                <!-- Working at Adelf Group -->
                <div class="bg-white p-6">
                    <h3 class="font-bold text-lg">Flexible Benefits</h3>
                    <p class="mt-4 text-sm">You can use your flexible personal budget to support your individual goals, whether for sports, skills development, or well-being.</p>
                </div>
                <!-- Personal Development -->
                <div class="bg-white p-6">
                    <h3 class="font-bold text-lg">Personal Development</h3>
                    <p class="mt-4 text-sm">Knowledge thrives when it flows freely. We attend conferences and organize events to benefit career starters and professionals alike.</p>
                </div>
                <!-- Work-Life Balance -->
                <div class="bg-white p-6">
                    <h3 class="font-bold text-lg">Work-Life Balance</h3>
                    <p class="mt-4 text-sm">A flexible work approach adapts to your needs and aligns with your projects, giving you control of your schedule.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Section 3: Benefits --}}
    <section class="bg-[#e0e9f0] px-6 py-12">
        <div class="container mx-auto">
            <div class="w-full md:w-1/2">
                <h2 class="text-5xl">We recognize <span class="text-black font-bold">hard work and dedication.</span></h2>
                <p class="mt-4 text-lg">We adopt a holistic approach to benefits, offering packages that reflect our commitment to your overall well-being.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-8">
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h3 class="font-bold text-lg"><span class="material-symbols-outlined text-green-500">check_circle</span>&nbsp; Competitive Base Salary</h3>
                    <p class="mt-4 text-sm">We ensure our salaries are benchmarked and competitive within the industry and region.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h3 class="font-bold text-lg"><span class="material-symbols-outlined text-green-500">check_circle</span>&nbsp; Personalized Benefits</h3>
                    <p class="mt-4 text-sm">Medical plans, provident fund contributions, and a personalized monthly cash allowance.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h3 class="font-bold text-lg"><span class="material-symbols-outlined text-green-500">check_circle</span>&nbsp; Learning Stipend</h3>
                    <p class="mt-4 text-sm">We reimburse expenses for job-related courses, helping you expand your skills and develop your potential.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Section 4: Teams --}}
    <section class="bg-white px-6 py-12">
        <div class="container mx-auto">
            <h2 class="text-5xl">Choose Your <span class="text-black font-bold">Team</span></h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-8">
                <!-- Business Development -->
                <div class="bg-gray-100 p-6">
                    <h3 class="font-bold text-lg">Business Development</h3>
                    <p class="mt-4 text-sm">Our Business Development team drives growth by building partnerships and identifying new opportunities.</p>
                </div>
                <!-- Customer Relations -->
                <div class="bg-gray-100 p-6">
                    <h3 class="font-bold text-lg">Customer Relations</h3>
                    <p class="mt-4 text-sm">Our CRM team maintains strong relationships with clients, ensuring personalized experiences.</p>
                </div>
                <!-- Product -->
                <div class="bg-gray-100 p-6">
                    <h3 class="font-bold text-lg">Product</h3>
                    <p class="mt-4 text-sm">The Product team transforms insights into cutting-edge solutions, guiding our development from concept to execution.</p>
                </div>
                <!-- Tech -->
                <div class="bg-gray-100 p-6">
                    <h3 class="font-bold text-lg">Tech</h3>
                    <p class="mt-4 text-sm">Our tech team powers innovation by building secure and scalable systems that enhance the client experience.</p>
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
