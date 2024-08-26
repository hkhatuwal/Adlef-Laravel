<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <title>{{isset($title) ? $title : env('APP_NAME')}}</title>
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
    @include('_partials.navbar')
    @yield('content')
    @include('_partials.footer')


</div>


</body>
@yield('pre-script')
<script src="{{asset("assets/js/aos.js")}}"></script>
<script src="{{asset("assets/js/swiper.min.js")}}"></script>
<script src="{{asset("assets/js/script.js")}}"></script>
@yield('post-script')

</html>
