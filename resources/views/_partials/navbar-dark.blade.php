{{-- Navbar --}}
<nav class="navbar  sticky top-0 bg-black w-full">
    <div class="flex justify-between px-4 md:px-10 container  mx-auto">
        <div class="logo flex justify-center items-center">
            <a href="{{route('frontend.home')}}">
                <img class="h-16 text-white invert-0" src="{{ asset('assets/images/logo-light.svg') }}"  alt="Logo">
            </a>
        </div>
        <div class="sm:hidden flex items-center">
            <button id="menu-toggle" class="text-gray-500 hover:text-white focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 12h16m-7 6h7"></path>
                </svg>
            </button>
        </div>
        <ul id="menu-items"
            class="gap-3 md:gap-5 flex p-5 opacity-0    transition-opacity duration-300   flex-col  absolute sm:relative bg-black  w-full sm:w-auto top-10 sm:top-0 left-0 mt-12 z-20 sm:z-auto  sm:mt-0 sm:flex-row sm:flex sm:items-center sm:bg-transparent sm:opacity-100 sm:p-0 sm:overflow-visible">
            <li><a href="{{route('frontend.solutions')}}" class="text-white">Solutions</a></li>
            <li><a href="{{route('frontend.news-insights')}}" class="text-white">News & Insights</a></li>
            <li>
                <div class="inline-block">
                    <button class="text-white font-medium hover:text-gray-200 dropdown-button ">Company</button>
                    <div class="hidden absolute  rounded-md shadow-sm w-48 mt-2 dropdown-content z-[9999]">
                        <ul class="py-2">
                            <li class="px-4 py-2 text-sm text-white hover:bg-gray-600 " ><a href="{{route('frontend.about')}}">About Us</a></li>
                            <li class="px-4 py-2 text-sm text-white hover:bg-gray-600"><a href="{{route('frontend.careers')}}">Careers</a></li>
                            <li class="px-4 py-2 text-sm text-white hover:bg-gray-600"><a href="">Get in touch</a></li>
                        </ul>
                    </div>
                </div>
            </li>
            <li>
                <button class="px-8 py-3 hidden md:block text-white border border-white">Login</button>
            </li>
            <li>
                <button class="btn-secondary hidden md:block">Become a Client</button>
            </li>
        </ul>
    </div>
</nav>
