@php
   $hideSidebar = (!empty($hideSidebar) && $hideSidebar);
   $showGlobalNotification = \App\Models\Setting::get('global_notification') === 'on';
   $globalNotificationText = \App\Models\Setting::get('global_notification_text');
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Client Portal') }}</title>


    @vite('resources/css/app.css')
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <title>{{isset($title) ? $title : env('APP_NAME')}}</title>
    <link rel="stylesheet" href="{{asset('assets/css/aos.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/swiper.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/google-fonts.css')}}">
    <link href="{{asset('common/css/select2.min.css')}}" rel="stylesheet"/>
    <link href="{{asset('common/css/fontawesome.min.css')}}" rel="stylesheet"/>
    <link rel="stylesheet" href="{{asset('common/css/toastr.min.css')}}">


    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{isset($description) ? $description : env('APP_NAME')}}">
    <meta name="keywords" content="{{isset($keywords) ? $keywords : 'default, keywords, here'}}">
    <meta name="author" content="{{isset($author) ? $author : "ADLEF GROUP"}}">
    <meta name="robots" content="index, follow">

    <!-- Open Graph Meta Tags (for social media sharing) -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{url()->current()}}">
    <meta property="og:site_name" content="{{isset($title) ? $title : env('APP_NAME')}}">
    <meta property="og:image" content="{{isset($image)?$image:asset('assets/images/logo.png')}}">
    <meta property="og:locale" content="en_US">

    <!-- Twitter Card Meta Tags (for Twitter sharing) -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{isset($title) ? $title : env('APP_NAME')}}">
    <meta name="twitter:description" content="{{isset($description) ? $description : env('APP_NAME')}}">
    <meta name="twitter:image" content="{{isset($image)?$image:asset('assets/images/logo.png')}}">
    <meta name="twitter:site" content="@YourTwitterHandle">

    <!-- Canonical URL -->
    <link rel="canonical" href="{{url()->current()}}">


    <!-- Additional SEO Metadata -->
    <meta name="theme-color" content="#000000">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
</head>


<body class=" antialiased bg-gray-100 poppins-medium">
<div class="min-h-screen">
    <!-- Sidebar -->
    @if(!$hideSidebar)
        @include('client._partials.sidebar')
    @endif
    <!-- Main Content -->
    <div class="{{!$hideSidebar?"ml-72":""}} main">
        <!-- Top Navigation -->
        <div class="top-nav bg-white h-16 fixed right-0 {{!$hideSidebar?"left-72":"left-0"}}  top-0 border-b border-gray-200 z-10">
            <div class="flex items-center justify-between h-full px-6">
                <div class="flex items-center">
                    <button id="sidebar-toggle" class="p-2 rounded-md hover:bg-gray-100 mr-2 relative z-40">
                        <i class="fas fa-bars"></i>
                    </button>
                    <span class="text-xl font-semibold">@yield('page-title')</span>
                </div>
                <div class="flex items-center">
                    <!-- User Menu -->
                    <div class="relative flex">
                        <button class="flex items-center space-x-2 mr-5">
                            <img src="{{ auth()->user()->avatar ?? asset('assets/images/avatar.png') }}" alt="Avatar"
                                 class="w-8 h-8 rounded-full">
                            <span>{{ auth()->user()->name }}</span>
                            <a href="{{route('client.client-logout')}}">
                                <i class="fa-regular fa-arrow-right-from-bracket text-red-500"></i>
                            </a>
                        </button>

                    </div>
                </div>
            </div>
        </div>

        <!-- Global Notification Alert -->
        @if($showGlobalNotification && $globalNotificationText)
        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 fixed w-full z-10" style="top: 64px;">
{{--            <button type="button" class="close-notification text-blue-500 hover:text-blue-700 absolute top-0 left-5">--}}
{{--                <i class="fas fa-times"></i>--}}
{{--            </button>--}}
            <div class="flex items-center justify-between max-w-7xl mx-auto">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-bell text-blue-500"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">
                            {{ $globalNotificationText }}
                        </p>
                    </div>
                </div>

            </div>
        </div>
        @endif

        <!-- Page Content -->
        <div class="px-6 py-6 bg-gradient-to-b from-gray-50 to-white {{$showGlobalNotification && $globalNotificationText ? 'pt-32':'pt-16'}}">
            @yield('content')
        </div>
    </div>
</div>

@include('client.components.notification-modal')

</body>
@routes()
<script src="{{asset('common/js/jquery.min.js')}}" crossorigin="anonymous"></script>
@yield('pre-script')
<script src="{{asset("assets/js/aos.js")}}"></script>
<script src="{{asset("assets/js/swiper.min.js")}}"></script>
<script src="{{asset('common/js/select2.min.js')}}"></script>
<script src="{{asset('common/js/fontawesome.js')}}"></script>
<script src="{{asset('common/js/toastr.min.js')}}"></script>
<script src="{{asset("common/js/alpine.min.js")}}"></script>

<script src="{{asset("assets/js/script.js")}}"></script>
<script src="{{asset("common/js/script.js")}}"></script>


@yield('post-script')


</html>
