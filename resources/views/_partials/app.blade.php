<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <link rel="stylesheet" href="{{asset('assets/css/aos.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/swiper.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/google-fonts.css')}}">

    <title>Document</title>
</head>
<body class="flex items-center justify-center">
<div class="w-screen" data-aos-easing="ease" data-aos-duration="400" data-aos-delay="0">
    @include('_partials.navbar')

    @yield('content')
    @include('_partials.footer')


</div>


</body>

<script src="{{asset("assets/js/aos.js")}}"></script>
<script src="{{asset("assets/js/swiper.min.js")}}"></script>
<script src="{{asset("assets/js/script.js")}}"></script>

</html>
