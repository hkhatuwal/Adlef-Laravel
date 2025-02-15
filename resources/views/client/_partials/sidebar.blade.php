<?php
$menuItems = config('constants.menu_items');

$instructionItems = config('constants.instruction_items');
?>

<nav class="sidebar fixed top-0 left-0 h-full w-72 bg-white border-r border-gray-100 z-20 transition-transform duration-300 ease-in-out shadow-sm">
    <div class="flex flex-col h-full">
        <!-- Header -->
        <div class="flex items-center justify-between h-20 px-6 border-b border-gray-100">
            <div class="text-xl font-bold">
                <img src="{{asset('assets/images/logo.svg')}}" alt="Logo" class="h-8">
            </div>
            <div class="flex items-center space-x-4">
                <!-- Theme Toggle -->
                <button class="theme-toggle w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 transition-all">
                    <i class="fa-regular fa-sun text-gray-600 dark:text-gray-400 text-lg"></i>
                </button>
                <!-- Notification -->
                <div class="relative">
                    <button class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 transition-all">
                        <i class="fa-solid fa-bell text-gray-600 text-lg"></i>
                        <span class="absolute -top-0.5 -right-0.5 bg-red-500 text-white text-xs w-4 h-4 flex items-center justify-center rounded-full">3</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Profile Card -->
        <div class="px-6 py-6">
            <div class="relative overflow-hidden card p-4 rounded-xl bg-gradient-to-br from-gray-50 to-white  hover:border-gray-200 transition-all group">
                <div class="absolute inset-0 bg-gradient-to-r from-green-50 to-blue-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="relative">
                    <div class="flex items-center space-x-3 mb-3">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-r from-green-100 to-blue-100 p-0.5">
                            <div class="w-full h-full rounded-full bg-white flex items-center justify-center">
                                <i class="fa-solid fa-user text-gray-600"></i>
                            </div>
                        </div>
                        <div>
                            <h2 class="font-semibold tracking-wide text-gray-800 text-lg">{{auth()->user()->name}}</h2>
                            <span class="text-sm text-gray-500 flex items-center">
                                <span class="w-2 h-2 rounded-full bg-green-500 mr-2"></span>
                                Premium Member
                            </span>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg p-3 mb-3 border border-gray-100 shadow-sm">
                        <h2 class=" font-semibold text-xl bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent">Premium Custody Platinum</h2>
                    </div>
                    <button class="w-full flex justify-between items-center px-3 py-2 hover:bg-white rounded-lg cursor-pointer transition-all border border-transparent hover:border-gray-100">
                        <div class="flex items-center">
                            <i class="fa-regular fa-user-circle text-gray-400 mr-2"></i>
                            <span class="font-medium text-gray-600">User & Profile</span>
                        </div>
                        <i class="fa-solid fa-angle-right text-gray-400 text-sm group-hover:translate-x-1 transition-transform"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <div class="flex-1 overflow-y-auto px-4 space-y-6">
            <!-- Main Menu -->
            <div>
                <ul class="space-y-1">
                    @foreach($menuItems as $item)
                        <li>
                            <a href="{{ route($item['route']) }}"
                               class="flex items-center px-4 py-3 text-gray-700 font-medium hover:bg-gray-50 rounded-xl transition-all group {{ request()->routeIs($item['route']) ? 'bg-gray-50 text-gray-900 font-semibold' : '' }}">
                                <span class="inline-flex mr-3 {{ request()->routeIs($item['route']) ? 'text-green-500' : 'text-gray-400' }} group-hover:text-green-500 transition-colors">
                                    {!! $item['icon'] !!}
                                </span>
                                {{ $item['title'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Instructions -->
            <div>
                <h3 class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3 flex items-center">
                    <span class="flex-shrink-0">Instructions</span>
                    <span class="ml-3 flex-grow border-t border-gray-200"></span>
                </h3>
                <ul class="space-y-1">
                    @foreach($instructionItems as $item)
                        <li>
                            <a href="{{ route($item['route']) }}"
                               class="flex items-center px-4 py-3 text-gray-700 font-medium hover:bg-gray-50 rounded-xl transition-all group {{ request()->routeIs($item['route']) ? 'bg-gray-50 text-gray-900 font-semibold' : '' }}">
                                <i class="fa-solid {{ $item['icon'] }} mr-3 {{ request()->routeIs($item['route']) ? 'text-green-500' : 'text-gray-400' }} group-hover:text-green-500 transition-colors"></i>
                                {{ $item['title'] }}
                                @if($item['title'] === 'OTC')
                                    <span class="ml-auto px-2 py-1 text-xs font-medium bg-green-100 text-green-600 rounded-full">New</span>
                                @endif
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Footer Navigation -->
        <div class="px-4 py-4 border-t border-gray-100 bg-gray-50">
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('client.account.index') }}"
                       class="flex items-center px-4 py-3 text-gray-700 font-medium hover:bg-white rounded-xl transition-all group {{ request()->routeIs(['client.account.index','client.account.add']) ? 'bg-white text-gray-900 font-semibold' : '' }}">
                        <i class="fa-solid fa-id-badge mr-3 {{ request()->routeIs(['client.account.index','client.account.add']) ? 'text-green-500' : 'text-gray-400' }} group-hover:text-green-500 transition-colors"></i>
                        Whitelist
                    </a>
                </li>

                <li>
                    <a href="{{ route('client.client-logout') }}"
                       class="flex items-center px-4 py-3 text-red-500 font-medium hover:bg-white rounded-xl transition-all group">
                        <i class="fa-solid fa-arrow-right-from-bracket mr-3 group-hover:translate-x-1 transition-transform"></i>
                        Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

@push('scripts')
<script>
    // Theme Toggle Functionality
    document.querySelector('.theme-toggle').addEventListener('click', function() {
        const icon = this.querySelector('i');
        if(icon.classList.contains('fa-sun')) {
            icon.classList.remove('fa-sun');
            icon.classList.add('fa-moon');
            document.documentElement.classList.add('dark');
        } else {
            icon.classList.remove('fa-moon');
            icon.classList.add('fa-sun');
            document.documentElement.classList.remove('dark');
        }
    });
</script>
@endpush
