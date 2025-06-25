<?php
$menuItems = config('constants.menu_items');

$instructionItems = config('constants.instruction_items');

$notifications = auth()->user()->unreadNotifications()->take(5)->get();
?>

<nav class="sidebar fixed top-0 left-0 h-full w-72 bg-white border-r border-gray-200 z-20 transition-transform duration-300 ease-in-out shadow-sm">
    <div class="flex flex-col h-full">
        <!-- Header -->
        <div class="flex items-center justify-between h-16 px-6 border-b border-gray-200">
            <div class="text-lg font-bold">
                <a href="{{route('client.dashboard')}}">
                    <img src="{{asset('assets/images/logo.svg')}}" alt="Logo" class="h-10">
                </a>
            </div>
            <div class="flex items-center space-x-4">
                <!-- Notification -->
                <div class="relative group">
                    <button class="w-7 h-7 flex items-center justify-center rounded-full hover:bg-gray-100 transition-all">
                        <i class="fa-solid fa-bell text-gray-600 text-sm group-hover:text-black"></i>
                        <span class="absolute -top-0.5 -right-0.5 bg-black text-white text-xs w-4 h-4 flex items-center justify-center rounded-full text-[8px]">{{auth()->user()->unreadNotifications()->count()}}</span>
                    </button>

                    <!-- Notification Popup -->
                    <div class="absolute left-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                        <div class="relative">
                            <!-- Arrow -->
                            <div class="absolute top-0 right-[45%] -translate-y-2">
                                <div class="border-8 border-transparent border-b-white filter drop-shadow-sm"></div>
                            </div>

                            <div class="p-3 border-b border-gray-200">
                                <div class="flex justify-between items-center">
                                    <h3 class="font-semibold text-black text-sm">Notifications</h3>
                                    <span class="px-2 py-1 bg-gray-100 text-xs font-medium rounded-full text-gray-600">{{auth()->user()->unreadNotifications()->count()}} new</span>
                                </div>
                            </div>

                            <div class="max-h-[400px] overflow-y-auto">
                                @forelse($notifications as $notification)
                                    <div class="p-3 hover:bg-gray-50 border-b border-gray-100 last:border-b-0 transition-colors cursor-pointer"
                                         onclick="markNotificationAsRead('{{ $notification->id }}')">
                                        <div class="flex items-start space-x-3">
                                            <div class="flex-shrink-0">
                                                @if($notification->type === 'success')
                                                    <span class="w-6 h-6 bg-gray-100 text-black rounded-full flex items-center justify-center">
                                                        <i class="fas fa-check-circle text-xs"></i>
                                                    </span>
                                                @elseif($notification->type === 'warning')
                                                    <span class="w-6 h-6 bg-gray-100 text-black rounded-full flex items-center justify-center">
                                                        <i class="fas fa-exclamation-circle text-xs"></i>
                                                    </span>
                                                @elseif($notification->type === 'error')
                                                    <span class="w-6 h-6 bg-gray-200 text-black rounded-full flex items-center justify-center">
                                                        <i class="fas fa-times-circle text-xs"></i>
                                                    </span>
                                                @else
                                                    <span class="w-6 h-6 bg-gray-100 text-black rounded-full flex items-center justify-center">
                                                        <i class="fas fa-info-circle text-xs"></i>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-xs font-medium text-black">
                                                    {{$notification->title}}
                                                </p>
                                                <p class="text-xs text-gray-600 line-clamp-2">
                                                    {{$notification->message}}
                                                </p>
                                                <p class="text-xs text-gray-400 mt-1">
                                                    {{$notification->created_at->diffForHumans()}}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="p-4 text-center text-gray-500 text-xs">
                                        No new notifications
                                    </div>
                                @endforelse
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Card -->
        <div class="px-6 py-4">
            <div class="relative overflow-hidden card p-3 rounded-lg bg-white border border-gray-200 hover:border-gray-300 transition-all group">
                <div class="relative">
                    <div class="flex items-center space-x-3 mb-2">
                        <div class="w-10 h-10 rounded-full bg-gray-100 p-0.5">
                            <div class="w-full h-full rounded-full bg-white border border-gray-200 flex items-center justify-center">
                                <i class="fa-solid fa-user text-gray-600 text-sm"></i>
                            </div>
                        </div>
                        <div>
                            <h2 class="font-semibold tracking-wide text-black text-sm">{{auth()->user()->name}}</h2>
                            <span class="text-xs text-gray-500 flex items-center">
                                <span class="w-1.5 h-1.5 rounded-full bg-black mr-2"></span>
                                Premium Member
                            </span>
                        </div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-2 mb-2 border border-gray-100">
                        <h2 class="font-semibold text-sm text-black">Premium Custody Platinum</h2>
                    </div>
                    <a href="{{ route('client.profile.edit') }}" class="w-full flex justify-between items-center px-2 py-2 hover:bg-gray-50 rounded-lg cursor-pointer transition-all border border-transparent hover:border-gray-100">
                        <div class="flex items-center">
                            <i class="fa-regular fa-user-circle text-gray-400 mr-2 text-sm"></i>
                            <span class="font-medium text-gray-600 text-xs">User & Profile</span>
                        </div>
                        <i class="fa-solid fa-angle-right text-gray-400 text-xs group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <div class="flex-1 overflow-y-auto px-4 space-y-4">
            <!-- Main Menu -->
            <div>
                <ul class="space-y-1">
                    @foreach($menuItems as $item)
                        <li>
                            <a href="{{ route($item['route']) }}"
                               class="flex items-center px-3 py-2 text-gray-700 font-medium hover:bg-gray-50 rounded-lg transition-all group text-sm {{ request()->routeIs($item['route']) || ($item['route'] === 'client.payment-gateway.index' && request()->routeIs('client.payment-gateway.*')) ? 'bg-gray-100 text-black font-semibold' : '' }}">
                                <span class="inline-flex mr-3 {{ request()->routeIs($item['route']) || ($item['route'] === 'client.payment-gateway.index' && request()->routeIs('client.payment-gateway.*')) ? 'text-black' : 'text-gray-400' }} group-hover:text-black transition-colors text-sm">
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
                <h3 class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 flex items-center">
                    <span class="flex-shrink-0">Instructions</span>
                    <span class="ml-3 flex-grow border-t border-gray-200"></span>
                </h3>
                <ul class="space-y-1">
                    @foreach($instructionItems as $item)
                        <li>
                            <a href="{{ route($item['route']) }}"
                               class="flex items-center px-3 py-2 text-gray-700 font-medium hover:bg-gray-50 rounded-lg transition-all group text-sm {{ request()->routeIs($item['route']) ? 'bg-gray-100 text-black font-semibold' : '' }}">
                                <i class="fa-solid {{ $item['icon'] }} mr-3 {{ request()->routeIs($item['route']) ? 'text-black' : 'text-gray-400' }} group-hover:text-black transition-colors text-sm"></i>
                                {{ $item['title'] }}
                                @if($item['title'] === 'OTC')
                                    <span class="ml-auto px-2 py-1 text-xs font-medium bg-black text-white rounded-full">New</span>
                                @endif
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Footer Navigation -->
        <div class="px-4 py-3 border-t border-gray-200 bg-gray-50">
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('client.account.index') }}"
                       class="flex items-center px-3 py-2 text-gray-700 font-medium hover:bg-white rounded-lg transition-all group text-sm {{ request()->routeIs(['client.account.index','client.account.add']) ? 'bg-white text-black font-semibold' : '' }}">
                        <i class="fa-solid fa-id-badge mr-3 {{ request()->routeIs(['client.account.index','client.account.add']) ? 'text-black' : 'text-gray-400' }} group-hover:text-black transition-colors text-sm"></i>
                        Whitelist
                    </a>
                </li>

                <li class="relative group">
                    <button class="w-full flex items-center px-3 py-2 text-gray-700 font-medium hover:bg-white rounded-lg transition-all text-sm">
                        <i class="fa-solid fa-user mr-3 text-gray-400 group-hover:text-black transition-colors text-sm"></i>
                        <span class="flex-1 text-left">{{ auth()->user()->name }}</span>
                        <i class="fa-solid fa-chevron-down ml-2 text-gray-400 group-hover:text-black transition-colors text-xs"></i>
                    </button>

                    <!-- User Menu Dropdown -->
                    <div class="absolute bottom-full left-0 mb-2 w-full bg-white rounded-lg shadow-lg border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300">
                        <div class="py-1">
                            <a href="{{ route('client.profile.edit') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                <i class="fa-regular fa-user mr-3 text-gray-400"></i>
                                Profile
                            </a>
                            <a href="{{route('frontend.help-center')}}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                <i class="fa-regular fa-circle-question mr-3 text-gray-400"></i>
                                Help Center
                            </a>
                            <a href="{{ route('client.client-logout') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                <i class="fa-solid fa-arrow-right-from-bracket mr-3 text-gray-400"></i>
                                Logout
                            </a>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>


