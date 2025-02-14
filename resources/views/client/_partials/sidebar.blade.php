<nav class="sidebar fixed top-0 left-0 h-full w-64 bg-white border-r border-gray-200 z-20 transition-transform duration-300 ease-in-out">
    <div class="flex flex-col h-full">
        <div class="flex items-center justify-between h-16 px-4 border-b border-gray-200">
            <div class="text-xl font-bold"><img src="{{asset('assets/images/logo.svg')}}" alt=""></div>
            <i class="fa-solid fa-envelope-dot text-green-500 text-lg"></i></div>

        <div class="flex-1 overflow-y-auto px-4 py-4">
            <div class="card p-4 rounded-lg">
                <h2 class="font-semibold tracking-widest leading-loose"><span class="mr-2"><i
                            class="fa-solid fa-user"></i></span>{{auth()->user()->name}}</h2>
                <h2 class="font-visuletProBold font-semibold   text-xl">Premium Custody Platinum</h2>
                <hr>
                <div class="flex justify-between items-center mt-2">
                    <h3 class="font-semibold text-gray-500 ">User & Profile</h3>
                    <i class="fa-solid fa-angle-right text-gray-500"></i>
                </div>
            </div>
            <ul class="space-y-2 mt-10">
                <li>
                    <a href="{{ route('client.dashboard') }}"
                       class="flex items-center px-4 py-2 text-gray-700  font-semibold hover:bg-gray-100 rounded-lg {{ request()->routeIs('client.dashboard') ? 'bg-gray-100' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Overview
                    </a>
                </li>

                <li>
                    <a href="{{ route('client.activities.index') }}"
                       class="flex items-center px-4 py-2 text-gray-700 font-semibold hover:bg-gray-100 rounded-lg {{ request()->routeIs('activity') ? 'bg-gray-100' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Activity
                    </a>
                </li>

                <li>
                    <hr></li>
                <li>INSTRUCTIONS</li>
                <li>
                    <a href="{{ route('client.transfer') }}"
                       class="flex items-center px-4 py-2 text-gray-700 font-semibold hover:bg-gray-100 rounded-lg {{ request()->routeIs('client.transfer') ? 'bg-gray-100' : '' }}">
                        <i class="fa-solid fa-arrow-right-arrow-left mr-3"></i>
                        Asset Transfer
                    </a>
                </li>
                <li>
                    <a href="{{ route('client.otc.index') }}"
                       class="flex items-center px-4 py-2 text-gray-700 font-semibold hover:bg-gray-100 rounded-lg {{ request()->routeIs('client.transfer') ? 'bg-gray-100' : '' }}">
                        <i class="fa-solid fa-repeat mr-3 "></i>
                        OTC
                    </a>
                </li>
            </ul>
        </div>
        <div class=" px-4 py-4">
            <ul>
                <li>
                    <a href="{{ route('client.account.index') }}"
                       class="flex items-center px-4 py-2 text-gray-700  font-semibold hover:bg-gray-100 rounded-lg {{ request()->routeIs(['client.account.index','client.account.add']) ? 'bg-gray-100' : '' }}">
                        <i class="fa-solid fa-id-badge mr-3"></i> Whitelist
                    </a>
                </li>
                <li>
                    <hr>
                </li>
                <li>
                    <a href="{{ route('client.client-logout') }}"
                       class="flex items-center px-4 py-2 text-red-500  font-semibold hover:bg-gray-100 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-gray-100' : '' }}">
                        <i class="fa-solid fa-arrow-right-from-bracket mr-3"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
