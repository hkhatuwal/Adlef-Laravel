<div class="card w-full md:w-[280px] bg-light p-5 " style="background: {{(isset($bg) )?$bg:""}}!important;" >
    <div class="card-image">
        <img src="{{asset($image)}}" alt="">
    </div>
    <div class="card-content flex flex-col gap-5  ">
        <h3 class="text-xl mt-4 leading-loose word tracking-wide" style="color: {{(isset($theme) && $theme=="dark")?"white!important;":""}} ">{{$title}}</h3>
        <p style="color: {{(isset($theme) && $theme=="dark")?"white!important;":""}} ">{{$description}}</p>
        <span class="font-[500] text-xs " style="color: {{(isset($theme) && $theme=="dark")?"white!important;":""}} ">{{$actionText}}</span>
    </div>
</div>
