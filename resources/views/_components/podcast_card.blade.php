<div class="card w-full md:w-[300px] shrink-0 bg-white p-5 " data-aos="fade-up" data-aos-delay="100"
     style="background: {{(isset($bg) )?$bg:""}}!important;">
    <div class="card-image">
        <img src="{{asset($image)}}" alt="">
    </div>
    <div class="card-content flex flex-col gap-3 py-16 ">
           <span class="font-semibold uppercase text-xs  font-visuletProLight text-[#999999] "
                 style="color: {{(isset($theme) && $theme=="dark")?"white!important;":""}} ">{{$label}}</span>
        <h3 class="text-[1.5rem]   word tracking-wide font-visuletProLight font-[600] text-black "
            style="color: {{(isset($theme) && $theme=="dark")?"white!important;":""}} ">{{$title}}</h3>
        <p style="color: {{(isset($theme) && $theme=="dark")?"white!important;":""}} ">{{$description}}</p>
        <div class="flex space-x-4 mt-6 h-8 ">
            @isset($links['apple'])
                <a href="{{ $links['apple'] }}" target="_blank" class="text-gray-500 hover:text-gray-900">
                    <img class="h-full" src="{{asset('assets/images/apple.svg')}}" alt="">
                </a>
            @endisset

            @isset($links['spotify'])
                <a href="{{ $links['spotify'] }}" target="_blank" class="text-gray-500 hover:text-gray-900">
                    <img class="h-full"  src="{{asset('assets/images/spotify.svg')}}" alt="">

                </a>
            @endisset

            @isset($links['google'])
                <a href="{{ $links['google'] }}" target="_blank" class="text-gray-500 hover:text-gray-900">
                    <img class="h-full" src="{{asset('assets/images/google.svg')}}" alt="">

                </a>
            @endisset

            @isset($links['amazon'])
                <a href="{{ $links['amazon'] }}" target="_blank" class="text-gray-500 hover:text-gray-900">
                    <img class="h-full" src="{{asset('assets/images/amazon.svg')}}" alt="">

                </a>
            @endisset
        </div>


    </div>
</div>
