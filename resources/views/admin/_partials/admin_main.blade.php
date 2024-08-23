<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite(['resources/css/app.css','resources/js/app.js'])
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{asset('assets/css/aos.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/swiper.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/google-fonts.css')}}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{isset($description) ? $description : env('APP_NAME')}}">
    <meta name="keywords" content="{{isset($keywords) ? $keywords : 'default, keywords, here'}}">
    <meta name="author" content="{{isset($author) ? $author : "ADLEF GROUP"}}">
    <meta name="robots" content="index, follow">

    <!-- Open Graph Meta Tags (for social media sharing) -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{url()->current()}}">
    <meta property="og:site_name" content="{{env('APP_NAME')}}">
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
<body class="flex items-center justify-center">
<div class="w-screen" data-aos-easing="ease" data-aos-duration="400" data-aos-delay="0">
    @include('admin._partials.sidebar')
    <div class="p-4 sm:ml-64">
        <div class="w-full p-8 bg-white shadow-xl rounded-lg">

        @if ($errors->any())
            <div class="bg-red-500 text-white p-4 rounded-lg mb-6">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (session('success'))
            <div class="bg-green-500 text-white p-4 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif
        @yield('content')
    </div>
    </div>
</div>


</body>
@yield('pre-script')
<script src="{{asset('common/js/jquery.min.js')}}"></script>
<script src="{{asset("admin/js/script.js")}}"></script>
@yield('post-script')

</html>
