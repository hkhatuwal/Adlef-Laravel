<div class="card w-full md:w-[300px]  shrink-0 bg-white p-5 " data-aos="fade-up" data-aos-delay="100"
     style="background: {{(isset($bg) )?$bg:""}}!important;">
    <div class="card-image">
        @if($image)
            <img src="{{$image}}" alt="{{$title}}" class="w-full h-48 object-cover">
        @else
            <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                <span class="text-gray-500">No Image</span>
            </div>
        @endif
    </div>
    <div class="card-content flex flex-col gap-3 py-8 ">
        <span class="font-semibold uppercase text-xs  font-visuletProLight text-[#999999] "
              style="color: {{(isset($theme) && $theme=="dark")?"white!important;":""}} ">{{$label}}</span>
        <h3 class="text-[1.2rem]   word tracking-wide font-visuletProLight font-[600] text-black "
            style="color: {{(isset($theme) && $theme=="dark")?"white!important;":""}} ">{{$title}}</h3>
        <p style="color: {{(isset($theme) && $theme=="dark")?"white!important;":""}} ">{{$description}}</p>

        @if(isset($source))
            <div class="text-sm text-gray-600 mt-2">
                <span>Source: {{$source}}</span>
                @if(isset($publishedAt))
                    <span class="block">{{ \Carbon\Carbon::parse($publishedAt)->format('M d, Y') }}</span>
                @endif
            </div>
        @endif

        <div class="flex space-x-4 mt-2 h-8 ">
            @if(isset($links) && is_array($links))
                @foreach($links as $link)
                    @if(isset($link['url']))
                        <a href="{{ $link['url'] }}" target="_blank" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">
                            {{ $link['platform'] ?? 'Read More' }}
                        </a>
                    @endif
                @endforeach
            @endif
        </div>
    </div>
</div>
