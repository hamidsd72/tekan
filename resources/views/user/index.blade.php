@extends('layouts.user')
@section('css_style')
<link rel="stylesheet" type="text/css" href="{{ asset('user/front/slick.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('user/front/slick-theme.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('user/front/style.css') }}"/>
<style>
    .slick-slider { direction: ltr; }
    .slick-dots{
        display: flex;
        align-items: center;
        height: 10px;
        margin: 0 -2.5px;
        pointer-events: auto;
        justify-content: center;
    }
    .services .box .info-box { padding: 0px 10px !important; }
    .services .box p { font-size: 12px; }
    .info-box-section { margin: unset !important; }
</style>
<link rel="stylesheet" type="text/css" href="{{asset('user/front/index.css')}}"/>
@endsection
@section('body')

    <div id="carouselExampleCaptions" class="carousel slide slider_index carousel-fade" data-interval="7000"
         data-ride="carousel">
        <div class="carousel-inner">
            @if(count($sliders))
                @foreach($sliders as $key=> $slider)
                    <div class="carousel-item {{$key==0?'active':''}}">
                        <a href="{{$slider->url}}">
                            <img src="{{$slider->photo && is_file($slider->photo->path)?url($slider->photo->path):asset('user/pic/SL-02.jpg')}}"
                                 class="d-block w-100"
                                 alt="{{set_lang($slider,'title',app()->getLocale())}}">
                        </a>

                        @if(is_file($slider->img_top))
                            <div class="animated_img">
                                <img src="{{url($slider->img_top)}}">
                            </div>
                        @endif
                    </div>
                @endforeach
            @else
                <div class="carousel-item active">
                    <img src="{{asset('user/pic/SL-02.jpg')}}"
                         class="d-block w-100" alt="فناوری فردا">
                </div>
            @endif
        </div>
        <a class="carousel-control-prev" href="#carouselExampleCaptions" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleCaptions" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>

    <section class="services">
        <div class="container">
            <div class="title main-title text-center pt-3 py-lg-5">
                <h3>خدمات ما</h3>
            </div>
            <div class="row info-box-section">
                @foreach($services as $service)
                    <div class="col-lg col-md-6 mt-4 mt-lg-0">
                        <div class="box">
                            <a href="{{route('user.blog.show',$service->slug)}}">
                                <img class="pic" src="{{$service->photo?url($service->photo->path):url('includes/asset/user/pic/nopic.png')}}">
                                <div class="info-box">
                                    <h2>{{$service->title}}</h2>
                                    <p>{{$service->short_text}}</p>
                                </div>
                            </a>
        
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="products-slider bg-ver-light-gray py-4 py-lg-5 mt-5" style="overflow-x: hidden;">
        <div class="container">
            <div class="title main-title text-center py-4">
                <h3>محصولات</h3>
            </div>
            <div class="slick-slider-area">
                <div class="row slick-carousel" data-slick='{"slidesToShow": 3, "responsive":[{"breakpoint": 1024,"settings":{"slidesToShow": 2}}, {"breakpoint": 768,"settings":{"slidesToShow": 1}}]}'>
                    @foreach($products as $product)
                        <div class="slick-slide-item">
                            <div class="product">
                                <a href="{{route('user.product.show',$product->slug)}}">
                                    <img src="{{$product->photo?url($product->photo->path):url('includes/asset/user/pic/nopic.png')}}"  alt="{{$product->name}}"  class="js-picturefill js-lazyloaded sdl-lazyloaded">
                                    <p class="se-rich-text" data-shave="4" data-tpl-xpm="" style="">{{$product->name}}</p>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    
    {{-- <section class="news">
        <div class="container">
            <div class="title main-title text-center pt-lg-5">
                <h3>اخبار</h3>
            </div>
            <section class="cards-wrapper">
                @foreach($news as $news_item)
                    <div class="card-grid-space">
                        <div class="num"></div>
                        <a class="card" href="{{route('user.blog.show',$news_item->slug)}}"
                             style="--bg-img: url({{$news_item->photo?url($news_item->photo->path):url('includes/asset/user/pic/nopic.png')}})">
                            <div>
                                <h1>{{$news_item->title}}</h1>
                                <p>{{$news_item->short_text}}</p>
                                <div class="date">{{date('F d, Y', strtotime($news_item->created_at))}}</div>
                                <div class="tags">
                                    <div class="tag">{{$news_item->writer}}</div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </section>
        </div>
    </section> --}}

    <section id="news" class="why_choose teams bg-white my-0">
        <div class="container ">
            <div class="title pb-5">
                <h3>اخبار</h3>
            </div>
            <div class="row">
                @foreach($news as $news_item)
                    <div class="col-lg-4">
                        <article class="cmp-news-list__news-item" style="height: 380px;">
                            <a class="cmp-news-list__item__link" target="_blank" href="{{route('user.blog.show',$news_item->slug)}}">
                                <div class="cmp-news-list__image-container">
                                    <div class="cmp-image"><img class="cmp-image__image cmp-news-list__image"
                                         src="{{ $news_item->photo?url($news_item->photo->path):url('includes/asset/user/pic/nopic.png') }}"></div>
                                </div>
                                <div class="cmp-news-list__news-item__contentContainer">
                                    <time class="cmp-news-list__description cmp-news-list__description--date float-left" datetime="2022-11-14T08:56:20.160Z">{{date('F d, Y', strtotime($news_item->created_at))}}</time>
                                    <div class="cmp-news-list__news-item__descriptionContainer">
                                        <h5 class="cmp-news-list__description cmp-news-list__description--newsType">{{$news_item->title}}</h5>
                                    </div>
                                    <div class="cmp-news-list__header">
                                        <p class="cmp-news-list__header__title">{{$news_item->short_text}}</p>
                                    </div>
                                    <p class="cmp-news-list__body-text">{{$news_item->writer}}</p>
                                </div>
                            </a>
                        </article>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
        
        <!-- https://images.unsplash.com/photo-1520839090488-4a6c211e2f94?ixlib=rb-0.3.5&ixid=eyJhcHBfaWQiOjEyMDd9&s=38951b8650067840307cba514b554ba5&auto=format&fit=crop&w=1350&q=80 -->
    {{--    </section>--}}
    {{--<div class="container">
        <div class="about_section">
            <div class="line d-flex pt-lg-5 pr-3 pr-lg-2">
                <h2>
                    {{__('text.page_name.about')}}
                </h2>
            </div>
            <p class="" style="text-align: justify;">{!!  str_limit(set_lang($about,'head_text'),580) !!}  </p>
            --}}{{--            <p class="" style="text-align: justify;">{{__('text.site_description')}}</p>--}}{{--
            <a class="btn_more" href="{{route('user.employment.show')}}"> {{__('text.continue')}}...</a>
        </div>

    </div>--}}


    {{--    category--}}
   {{-- <section id="menu_slider_downs" class="why_choose bg-white teams pb-0 mt-2">
        <div class="container ">
            <div class="title">
                <h3 class="text-bold"> {{strtoupper(__('text.power transmission'))}}</h3>
            </div>
            <div class="row">

                @foreach($ProductCategory->children_orderBy->reverse() as $index=>$item)

                    <div class="col-lg-{{$index<3?'4 mt-3':'3'}} ">
                        <div class="card4">
                            <a href="{{route('user.product.category.index',['power-transfer',$item->slug])}}">
                                @if($item->photo)
                                    <img alt="انتقال قدرت" class="card1_img" style="border-radius: 6px;"
                                         src="{{$item->photo->path}}">
                                @endif
                            </a>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </section>--}}

  {{--  <section id="menu_slider_downs" class="why_choose bg-white teams pb-0 mt-2">
        <div class="container ">
            <div class="title">
                <h3>
                    {{strtoupper(__('text.Material handling'))}}
                </h3>
            </div>
            <div class="row">

                @foreach($ProductCategory2->children_orderBy as $index=>$item)

                    <div class="col-lg-{{$index<2?'6':'4 mt-3'}}">
                        <div class="card4">
                            @if($item->slug == 'ریل-آلومینیومی')
                                <a href="{{route('user.product.show','ریل-آلومینیومی')}}">
                                    @else
                                        <a href="{{route('user.product.category.index',['material-handling',$item->slug])}}">

                                            @endif
                                            @if($item->photo)
                                                <img alt="{{$item->name}}" class="card1_img" style="border-radius: 6px;"
                                                     src="{{$item->photo->path}}">
                                            @endif
                                        </a>
                        </div>
                    </div>
                @endforeach


            </div>
        </div>
    </section>--}}

   {{-- <section id="menu_slider_downs" class="why_choose bg-white teams pb-0 mt-2">
        <div class="container ">
            <div class="title">
                <h3>
                    {{strtoupper(__('text.Letter representation'))}}
                </h3>
            </div>
            <section class="swiper-container loading">
                <div class="swiper-wrapper">

                    @foreach($certs as $index=>$item)
                        @if($item->pic != null)
                            <div class="swiper-slide" style="background-image:url('{{$item->pic}}');width: 253.333px;">
                                <img src="{{$item->pic}}" class="entity-img"/>
                                <div class="content">
                                </div>
                            </div>
                        @endif

                    @endforeach

                </div>

                <!-- If we need pagination -->
                <div class="swiper-pagination"></div>
                <!-- If we need navigation buttons -->
                <div class="swiper-button-prev swiper-button-white"></div>
                <div class="swiper-button-next swiper-button-white"></div>
            </section>
        </div>
    </section>--}}

    @if(app()->getLocale()=='fa')
        <section id="blog_section" class="why_choose bg-ver-light-gray teams">
            <div class="container ">

                <div class="title pb-5">

                    <h3>{{__('text.page_name.article')}}</h3>
                </div>
                <div class="row">

                    @foreach($blogs as $blog)
                        <div class="col-lg-4">
                            <article class="cmp-news-list__news-item">
                                <a class="cmp-news-list__item__link" target="_blank" href="{{route('user.blog.show',$blog->slug)}}">
                                    <div class="cmp-news-list__image-container">
                                        @if($blog->photo() != null)
                                            <div class="cmp-image"><img class="cmp-image__image cmp-news-list__image" src="{{$blog->photo()->first()->path}}"></div>
                                        @endif
                                    </div>
                                    <div class="cmp-news-list__news-item__contentContainer">
                                        <time class="cmp-news-list__description cmp-news-list__description--date float-left" datetime="2022-11-14T08:56:20.160Z">{{date('F d, Y', strtotime($blog->created_at))}}</time>
                                        <div class="cmp-news-list__news-item__descriptionContainer">
                                            <h5 class="cmp-news-list__description cmp-news-list__description--newsType">{{$blog->title}}</h5>
                                        </div>
                                        <div class="cmp-news-list__header">
                                            <p class="cmp-news-list__header__title">{{$blog->short_text}}</p>
                                        </div>
                                        <p class="cmp-news-list__body-text"></p>

                                    </div>
                                </a>
                            </article>
                        </div>

                    @endforeach

                    <a class="btn_more" href="{{route('user.blog.index','article')}}">{{__('text.view all')}}</a>
                </div>
            </div>
        </section>
    @endif

    <article class="teaser aem-GridColumn aem-GridColumn--default--12" style="overflow: hidden">
        <div class="cmp-teaser teaser-txt-right bounce-padding" id="cmp-3mM4ZvPYpBFgDJ">
            <div class="cmp-teaser__image">

                <div data-cmp-lazy="" data-cmp-src="https://media-d.global.abb/is/image/abbc/Did_you_know_KV_200819_tescik 2" data-asset="/content/dam/abb/global/group/technology/did-you-know/Did_you_know_KV_200819_tescik 2.jpg" data-asset-id="f0810f32-1aaa-4f44-92fb-bb7b063ba211" class="cmp-image" itemscope="" itemtype="http://schema.org/ImageObject">

                    <div class="cmp-picture">
                        <picture>
                            <img src="https://media-d.global.abb/is/image/abbc/Did_you_know_KV_200819_tescik%202:16x9?wid=1440&hei=810"  alt="" class="cmp-image__image" itemprop="contentUrl" data-cmp-hook-image="image">
                        </picture>
                    </div>

                </div>
                <div itemscope="" class="cmp-image__description">
                </div>
            </div>

            <div class="cmp-teaser__content">

                <h2 class="cmp-teaser__title">
                    درباره ما
                </h2>

                <div class="cmp-teaser__description"><p>لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از طراحان گرافیک است، چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است، و برای شرایط فعلی تکنولوژی مورد نیاز، و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد، کتابهای زیادی در شصت و سه درصد گذشته حال و آینده، شناخت فراوان جامعه و متخصصان را می طلبد، تا با نرم افزارها شناخت بیشتری را برای طراحان رایانه ای علی الخصوص طراحان خلاقی، و فرهنگ پیشرو در زبان فارسی ایجاد کرد، در این صورت می توان امید... </p>
                </div>

                <div class="cmp-teaser__action-container">

                    <div class="cmp-abb-cta">
                        <a class="cmp-abb-cta__link cmp-teaser__action-link cmp-abb-cta__link--primary" style="background: #a58c5a !important;" href="{{route('user.employment.show')}}" target="_self">ادامه</a>
                    </div>

                </div>
            </div>
        </div>

    </article>

@endsection
@section('js')
    <script type="text/javascript" src="//code.jquery.com/jquery-1.11.0.min.js"></script>
    <script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>

    <script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function(){
            $('.slick-carousel').slick({
                lazyLoad: 'onDemand',
                centerMode: true,
                infinite: true,
                slidesToShow: 4,
                slidesToScroll: 4,
                variableWidth: true,
                dots: true,
                //autoplay: true,
                autoplaySpeed: 6000,
                responsive: [
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 1,
                            centerMode: false, /* set centerMode to false to show complete slide instead of 3 */
                            slidesToScroll: 1
                        }
                    }
                ]
            });
        });
    </script>

@endsection