@extends('_partials.app',['title' => 'ADLEF GROUP | SOLUTIONS','description' => 'We integrate our service offerings to provide clients full-turnkey solutions.'])
@section('content')
    {{-- Section 1--}}
    <section class="bg-light">
        <div class="container mx-auto px-4 md:p-10 mt-10 flex flex-col mb-10 gap-14 ">
            <h2 class="font-visuletProLight  text-3xl md:text-5xl ">
                Featured Content
            </h2>
            <h2 class="font-visuletProLight  text-3xl md:text-3xl ">
                First off the Blocks <span class="font-bold"> Podcast</span>
            </h2>
            <div class="leadership-wrapper w-full mx-auto">
                <div class="swiper">
                    <div class="swiper-wrapper">
                        @foreach($podcasts as $podcast)
                        <div class="swiper-slide ">
                                  @include('_components.podcast_card', [
                                                                           'image' => 'storage/'.$podcast->image,
                                                                           'description' =>$podcast->content,
                                                                           'title' => $podcast->title,
                                                                           'label' => $podcast->label,
                                                                           'theme' => 'light',
                                                                           'bg' => '#fff',
                                                                           "links"=>$podcast->links
                                                                       ])
                        </div>
                        @endforeach


                    </div>
                </div>

                <div class="flex justify-end">
                    <div class="bg-black w-auto p-5 ml-auto  inline-block ">
                        <span class="swiper-button-prev-custom relative text-white material-symbols-outlined px-5">arrow_forward_ios </span>
                        <div class="swiper-button-next-custom relative text-white material-symbols-outlined px-5">
                            arrow_back_ios
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>



    <section>
        <div class="container mx-auto px-4 md:p-10 mt-10 flex flex-col mb-10 gap-14 ">
            <div class="md:w-1/2">
                <h2 class="font-visuletProLight  text-3xl md:text-4xl ">
                    Articles
                </h2>
            </div>
            @foreach($categoriesWithPosts as $categoryWithPosts)
                <h2 class="font-visuletProLight  text-3xl md:text-3xl ">
                    {{$categoryWithPosts->name}}
                </h2>
                <div class="cards flex gap-3  mt-10 overflow-scroll">
                    @foreach($categoryWithPosts->posts->take(3) as $post)
                        @include('_components.news_card', [
                            'image' => 'storage/' . $post->image,
                            'title' => 'Guide to International Digital Assets Regulations',
                            'footerText' => '6 February 2023',
                            'theme' => 'light',
                            'bg' => 'transparent'
                        ])
                    @endforeach
                </div>

                <hr>
            @endforeach
        </div>
    </section>

@endsection
