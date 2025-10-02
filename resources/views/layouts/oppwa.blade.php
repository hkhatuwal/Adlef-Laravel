<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Payment Portal') }} - @yield('title', 'OPPWA Payment')</title>

    @vite('resources/css/app.css')
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <link rel="stylesheet" href="{{asset('assets/css/aos.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/swiper.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/google-fonts.css')}}">
    <link href="{{asset('common/css/select2.min.css')}}" rel="stylesheet"/>
    <link href="{{asset('common/css/fontawesome.min.css')}}" rel="stylesheet"/>
    <link rel="stylesheet" href="{{asset('common/css/toastr.min.css')}}">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{isset($description) ? $description : 'Secure payment processing with OPPWA'}}">
    <meta name="keywords" content="{{isset($keywords) ? $keywords : 'payment, OPPWA, secure, online payment'}}">
    <meta name="author" content="{{isset($author) ? $author : "ADLEF GROUP"}}">
    <meta name="robots" content="noindex, nofollow">

    <!-- Open Graph Meta Tags (for social media sharing) -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{url()->current()}}">
    <meta property="og:site_name" content="{{isset($title) ? $title : config('app.name')}}">
    <meta property="og:image" content="{{isset($image)?$image:asset('assets/images/logo.png')}}">
    <meta property="og:locale" content="en_US">

    <!-- Twitter Card Meta Tags (for Twitter sharing) -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{isset($title) ? $title : config('app.name')}}">
    <meta name="twitter:description" content="{{isset($description) ? $description : 'Secure payment processing'}}">
    <meta name="twitter:image" content="{{isset($image)?$image:asset('assets/images/logo.png')}}">

    <!-- Canonical URL -->
    <link rel="canonical" href="{{url()->current()}}">

    <!-- Additional SEO Metadata -->
    <meta name="theme-color" content="#000000">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
</head>

<body class="antialiased bg-gray-100 poppins-medium">
<div class="min-h-screen">
    <!-- Header with Logo -->
    <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('frontend.home') }}" class="flex items-center">
                        <img src="{{ asset('assets/images/logo.png') }}" alt="{{ config('app.name') }}" class="h-8 w-auto">
                    </a>
                </div>

                <!-- Payment Status Indicator -->
                <div class="flex items-center">
                    <div class="flex items-center space-x-2 text-sm text-gray-600">
                        <i class="fas fa-shield-alt text-green-500"></i>
                        <span>Secure Payment</span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="flex items-center mb-4 md:mb-0">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="{{ config('app.name') }}" class="h-6 w-auto">
                    <span class="ml-2 text-sm text-gray-600">{{ config('app.name') }} Payment Portal</span>
                </div>
                <div class="text-sm text-gray-500">
                    <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>
</div>

<!-- Scripts -->
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

</body>
</html>
