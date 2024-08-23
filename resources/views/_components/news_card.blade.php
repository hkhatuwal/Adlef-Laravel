<div class="card w-full md:w-[300px] shrink-0 bg-light " data-aos="fade-up" data-aos-delay="100" style="background: {{(isset($bg) )?$bg:""}}!important;" >
    <div class="card-image">
        <img src="{{asset($image)}}" alt="">
    </div>
    <div class="card-content flex flex-col gap-5 ">
        <h3 class="text-[1.5rem] mt-4  word tracking-wide font-visuletProLight font-[600] text-black " style="color: {{(isset($theme) && $theme=="dark")?"white!important;":""}} ">{{$title}}</h3>
        <span class="font-[500] text-xs text-gray-500 " style="color: {{(isset($theme) && $theme=="dark")?"white!important;":""}} ">{{$footerText}}</span>
    </div>
</div>
