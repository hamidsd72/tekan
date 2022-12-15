<link rel="stylesheet" type="text/css" href="{{ asset('user/front/header.css').'?'.random_int(99,9999) }}"/>
<div class="top-header d-none d-md-block">
    <div class="container">
        <div class="row" dir="ltr">
            <div class="col-md-2 my-auto item_enail">
                <a href="{{route('user.employment.show')}}">
                    <i style="color: black !important;" class="fas fa-envelope mx-1"></i>
                </a>
                <a href="mailto:%20info@baffco.com">
                    info@tekan.com
                </a>
            </div>
            <div class="col-md-2 my-auto item_phone">
                <a href="{{route('user.employment.show')}}">
                    <i style="color:  black !important;" class="fas fa-phone-alt mx-1"></i>
                </a>
                <a href="tel:02144004100">
                    @if(app()->getLocale() == 'fa')
                        ۸۸۵۷۷۴۳۹
                    @else
                        88577439
                    @endif
                </a>
            </div>
            <div class="col-md-2 my-auto">
                <a href="{{route('user.employment.show')}}">
                    <i style="color:  black !important;" class="fas fa-calendar mx-1"></i>
                </a>
                <a>{{date('Y-m-d')}}</a>
            </div>
        </div>
    </div>
</div>


<header id="header">
    <nav id="nav_id" class="navbar navbar-expand-lg navbar-dark">
        <div class="container-lg d-flex mb-1">
            <a class="navbar-brand" href="{{route('user.index')}}">
                @if(app()->getLocale() == 'en' )
                    <img src="{{url($setting->logo_en)}}" id="logohone" class="logo1" alt="به اندیشی و فناوری فردا">
                @else
                    <img src="{{url($setting->logo ?? '#')}}" id="logohone" class="logo1" alt="به اندیشی و فناوری فردا">
                @endif
            </a>

            <button id="btn_mobile_menu" class="navbar-toggler collapsed position-relative z-index-9 btn-sm-icon-mobile"
                    type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse edit mt-0 pb-0 justify-content-center" id="navbarSupportedContent">
                <div class="d-none d-lg-block">
                    <ul class="nav navbar-nav {{app()->getLocale()=='en'?'mr-auto':'ml-auto'}}">
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('user.index')}}">{{__('text.page_name.home')}} <span
                                        class="sr-only">(current)</span></a>
                        </li>

{{--
                        @if(session('locale') == 'fa' or is_null(session('locale')))

                            <li class="nav-item">
                                <a href="#" class="nav-link dropdown-toggle">گروه فناوری فردا</a>
                                <ul class="nav navbar-nav d-block   {{app()->getLocale()=='en'?'mr-auto':'ml-auto'}}">

                                    <li><a class="nav-link dropdown-toggle" href="#">به اندیشی و فناوری فردا</a>
                                        <ul>
                                            <li><a class="nav-link" href="#"> ریخته گری و صادرات </a></li>

                                            <li class="nav-item">
                                                <a href="#" class="nav-link dropdown-toggle"> انتقال قدرت </a>
                                                <ul class="nav navbar-nav d-block   {{app()->getLocale()=='en'?'mr-auto':'ml-auto'}}">

                                                    @foreach($ProductCategory->children_orderBy->take(10) as $item)

                                                        <li><a class="nav-link"
                                                               href="{{route('user.product.category.index',['material-handling',$item->slug])}}"> {{$item->name}}</a>
                                                        </li>
                                                        --}}{{--                                                            @foreach($item->children_orderBy->take(5) as $item2)--}}{{--
                                                        --}}{{--                                                                <li><a class="nav-link" href="{{route('user.product.category.index',['material-handling',$item->slug,$item2->slug])}}">{{$item2->name}}</a></li>--}}{{--
                                                        --}}{{--                                                            @endforeach--}}{{--
                                                    @endforeach

                                                    --}}{{--                                                    <li><a class="nav-link" href="#">بیرینگ</a></li>--}}{{--
                                                    --}}{{--                                                    <li><a class="nav-link" href="#">کوپلینگ</a></li>--}}{{--
                                                    --}}{{--                                                    <li><a class="nav-link" href="#">تسمه و پولی</a></li>--}}{{--
                                                </ul>
                                            </li>

                                        </ul>
                                    </li>
                                    <li><a class="nav-link" href="#">فرزان فن اندیش فردا</a></li>

                                    <li class="nav-item">
                                        <a href="{{route('user.product.category.index',['material-handling'])}}"
                                           class="nav-link dropdown-toggle">ناب آفرینان فردا</a>
                                        <ul class="nav navbar-nav d-block   {{app()->getLocale()=='en'?'mr-auto':'ml-auto'}}">
                                            --}}{{--                                            <li><a class="nav-link" href="#">MANIPULATOR</a></li>--}}{{--
                                            --}}{{--                                            <li><a class="nav-link" href="#">AGV</a></li>--}}{{--
                                            --}}{{--                                            <li><a class="nav-link" href="#">LEAN SYSTEM</a></li>--}}{{--
                                            --}}{{--                                            <li><a class="nav-link" href="#">BALANCER</a></li>--}}{{--
                                            --}}{{--                                            <li><a class="nav-link" href="#">RAIL</a></li>--}}{{--
                                            @foreach($ProductCategory2->children_order_menu as $item)

                                                --}}{{--                                                @if($item->children_orderBy && $item->children_orderBy->count() > 0)--}}{{--
                                                --}}{{--                                                  @php $hasSub = true; @endphp--}}{{--
                                                --}}{{--                                                @else--}}{{--
                                                --}}{{--                                                    @php $hasSub = false; @endphp--}}{{--
                                                --}}{{--                                                 @endif--}}{{--


                                                @if($item->slug == 'ریل-آلومینیومی')
                                                    <li><a class="nav-link  "
                                                           href="{{route('user.product.show','ریل-آلومینیومی')}}">{{$item->name}}</a>

                                                @else
                                                    <li><a class="nav-link "
                                                           href="{{route('user.product.category.index',['material-handling',$item->slug])}}">{{$item->name}}</a>

                                                        @endif

                                                        --}}{{--                                                    @if($hasSub)--}}{{--
                                                        --}}{{--                                                        <ul class="nav navbar-nav d-block   {{app()->getLocale()=='en'?'mr-auto':'ml-auto'}}">--}}{{--

                                                        --}}{{--                                                            @foreach($item->children_orderBy as $item2)--}}{{--
                                                        --}}{{--                                                                <li><a class="nav-link"--}}{{--
                                                        --}}{{--                                                                       href="{{route('user.product.category.index',['material-handling',$item->slug,$item2->slug])}}">{{$item2->name}}</a>--}}{{--
                                                        --}}{{--                                                                </li>--}}{{--
                                                        --}}{{--                                                            @endforeach--}}{{--
                                                        --}}{{--                                                        </ul>--}}{{--
                                                        --}}{{--                                                    @endif--}}{{--
                                                    </li>
                                                    @endforeach

                                        </ul>
                                    </li>

                                    <li><a class="nav-link" href="#">GIPCO</a></li>
                                </ul>
                            </li>

                            <li class="nav-item">
                                <a href="#" class="nav-link dropdown-toggle">دانش فنی</a>
                                <ul class="nav navbar-nav d-block   {{app()->getLocale()=='en'?'mr-auto':'ml-auto'}}">
                                    <li><a class="nav-link" href="{{route('user.catalogs.show')}}">کاتالوگ ها</a></li>
                                    <li><a class="nav-link" href="{{route('user.blog.index','article')}}">مقالات</a>
                                    </li>
                                </ul>
                            </li>

                            <li class="nav-item">
                                <a href="#" class="nav-link dropdown-toggle">اخبار و رویداد ها</a>
                                <ul class="nav navbar-nav d-block   {{app()->getLocale()=='en'?'mr-auto':'ml-auto'}}">
                                    <li>
                                        <a href="{{route('user.catalogs.show')}}" class="nav-link ">پروژه
                                            ها</a>
                                        --}}{{--                                        <ul class="nav navbar-nav d-block   {{app()->getLocale()=='en'?'mr-auto':'ml-auto'}}">--}}{{--
                                        --}}{{--                                            <li><a class="nav-link" href="{{route('user.employment.show')}}">فرصت های--}}{{--
                                        --}}{{--                                                    شغلی</a></li>--}}{{--
                                        --}}{{--                                        </ul>--}}{{--
                                    </li>
                                    <li><a class="nav-link" href="{{route('user.news')}}">فرصت های
                                            شغلی</a></li>
                                </ul>
                            </li>

                        @else
                            <li class="nav-item">
                                <a class="nav-link"
                                   href="{{route('user.employment.show')}}">{{__('text.page_name.about')}}</a>
                            </li>
                        @endif--}}

                        <li class="nav-item">
                            <a class="nav-link"
                               href="#">محصولات </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"
                               href="{{route('user.blog.index','service')}}">خدمات</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link"
                               href="{{route('user.blog.index','news')}}">اخبار</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"
                               href="{{route('user.blog.index','article')}}">مقالات </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"
                               href="#">درباره ما  </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"
                               href="{{route('user.contact.show')}}">{{__('text.page_name.contact')}}</a>
                        </li>
                    </ul>
                </div>

                <ul class="d-lg-none nav-sm navbar-nav {{app()->getLocale()=='en'?'mr-auto':'ml-auto'}}">
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('user.index')}}">{{__('text.header_down.home')}} <span
                                    class="sr-only">(current)</span></a>
                    </li>
                   {{-- <div id="accordion">

                        <div class="">
                            <div id="headingOne">
                                <button class="btn text-white p-0 mb-3" data-toggle="collapse"
                                        data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    <img src="{{ asset('img/arrow.png') }}" width="20px"
                                         style="border-radius: 50px;padding: 2px;" alt="arrow">
                                    انتقال قدرت
                                </button>
                            </div>
                            <div id="collapseOne" class="collapse pb-3 pr-3" aria-labelledby="headingOne"
                                 data-parent="#accordion">
                                @if($ProductCategory !=  null)
                                    @foreach($ProductCategory->children_orderBy as $item)

                                        <div>
                                            <button class="btn text-white p-0 mb-3" data-toggle="collapse"
                                                    href="#multiCollapseExample1" aria-expanded="true"
                                                    aria-controls="multiCollapseExample1">
                                                <img src="{{ asset('img/arrow.png') }}" width="20px"
                                                     style="border-radius: 50px;padding: 2px;" alt="arrow">
                                                {{$item->name}}
                                            </button>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <div class="collapse multi-collapse" id="multiCollapseExample1">
                                                    @foreach($item->children_orderBy as $item2)
                                                        <a class="dropdown-item"
                                                           href="{{route('user.product.category.index',['material-handling',$item->slug,$item2->slug])}}">{{$item2->name}}</a>
                                                    @endforeach

                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif

                            </div>
                        </div>

                        <div class="">
                            <div id="headingOne">
                                <button class="btn text-white p-0 mb-3" data-toggle="collapse"
                                        data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                                    <img src="{{ asset('img/arrow.png') }}" width="20px"
                                         style="border-radius: 50px;padding: 2px;" alt="arrow">
                                    جابجایی مواد
                                </button>
                            </div>
                            <div id="collapseTwo" class="collapse pb-3 pr-3" aria-labelledby="headingOne"
                                 data-parent="#accordion">
                                @if($ProductCategory2 !=  null)
                                    @foreach($ProductCategory2->children_orderBy as $item)

                                        <div>
                                            <button class="btn text-white p-0 mb-3" data-toggle="collapse"
                                                    href="#multiCollapseExample1" aria-expanded="true"
                                                    aria-controls="multiCollapseExample1">
                                                <img src="{{ asset('img/arrow.png') }}" width="20px"
                                                     style="border-radius: 50px;padding: 2px;" alt="arrow">
                                                {{$item->name}}
                                            </button>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <div class="collapse multi-collapse" id="multiCollapseExample1">
                                                    @foreach($item->children_orderBy as $item2)
                                                        <a class="dropdown-item"
                                                           href="{{route('user.product.category.index',['material-handling',$item->slug,$item2->slug])}}">{{$item2->name}}</a>
                                                    @endforeach

                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif

                            </div>
                        </div>

                        <div class="">
                            <div id="headingTwo">
                                <button class="btn text-white p-0 mb-3 collapsed" data-toggle="collapse"
                                        data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                    <img src="{{ asset('img/arrow.png') }}" width="20px"
                                         style="border-radius: 50px;padding: 2px;" alt="arrow">
                                    دانش فنی
                                </button>
                            </div>
                            <div id="collapseFour" class="collapse pb-3" aria-labelledby="headingTwo"
                                 data-parent="#accordion">
                                <a class="dropdown-item" href="{{route('user.catalogs.show')}}">کاتالوگ ها</a>
                                <a class="dropdown-item" href="{{route('user.blog.index','article')}}">مقالات</a>
                                <a class="dropdown-item" href="{{route('user.knowledge.video')}}">فیلم های آموزشی</a>
                                <a class="dropdown-item" href="{{route('user.software.show')}}">نرم افزار های
                                    محاسباتی</a>
                            </div>
                        </div>


                    </div>--}}

                    {{-- <li class="nav-item" >
                        <p class="m-0 p-0 pb-1">
                            خدمات حافظ
                        </p>
                        @foreach ($menu_header1_links as $link)
                            <a href="{{$link->link}}" target="_blank" class="nav-link mr-2 mb-2 {{app()->getLocale()=='fa'?'text-right':'text-left'}}">{{$link->name}}</a>
                        @endforeach
                    </li> --}}
                    
                    <li class="nav-item">
                        <a class="nav-link"
                           href="{{route('user.blog.index','service')}}"> خدمات</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link"
                           href="{{route('user.blog.index','article')}}"> اخبار</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link"
                           href="{{route('user.blog.index','article')}}"> مقالات</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link"
                           href="{{route('user.employment.show')}}">درباره ما</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                           href="{{route('user.contact.show')}}">تماس با ما</a>
                    </li>
                    {{-- <li class="nav-item" >
                        <a class="nav-link"
                           href="{{route('bank.index')}}">شماره حساب ها</a>
                    </li>
                    <li class="nav-item" >
                        <a class="nav-link"
                           href="{{route('sub-station.index')}}">شعب</a>
                    </li>
                    <li class="nav-item" >
                        <a class="nav-link"
                           href="https://club.hafezbroker.ir/" target="_blank">باشگاه مشتریان</a>
                    </li> --}}
                </ul>

                <div class="dropdown language">
                    <button class="btn btn-sm dropdown-toggle" style="background: transparent !important;border: none !important;"
                     type="button" data-toggle="dropdown" aria-expanded="false">
                        @if(session('locale') == 'en')
                            <img src="https://adib-it.com/assets/newFront/img/en.png" alt="en">
                        @else
                            <img src="https://adib-it.com/assets/newFront/img/fa.png" alt="fa">
                        @endif
                    </button>
                    <div class="dropdown-menu">
                        <a class="d-block" href="{{route('lang_set','fa')}}"> <img
                                    src="https://adib-it.com/assets/newFront/img/fa.png" alt="fa"></a>
                        <a class="d-block" href="{{route('lang_set','en')}}"> <img
                                    src="https://adib-it.com/assets/newFront/img/en.png" alt="en"> </a>
                    </div>
                </div>

                {{--                <div class="dropdown">--}}
                {{--                    <button class="btn text-white dropdown-toggle " type="button" id="current-language-dropdown" data-bs-toggle="dropdown" aria-expanded="true">--}}
                {{--                        <img src="https://adib-it.com/./assets/newFront/img/fa.png" alt="fa" height="40px">--}}
                {{--                    </button>--}}
                {{--                    <ul class="dropdown-menu " aria-labelledby="current-language-dropdown" >--}}
                {{--                        <li>--}}
                {{--                            <a class="dropdown-item" href="{{route('lang_set','fa')}}"> <img src="https://adib-it.com/./assets/newFront/img/fa.png" alt="fa"> <span>فارسی</span> </a>--}}
                {{--                        </li>--}}
                {{--                        <li> <a class="dropdown-item" href="{{route('lang_set','en')}}"> <img src="https://adib-it.com/./assets/newFront/img/en.png" alt="en"> <span>انگلیسی</span> </a> </li>--}}
                {{--                    </ul>--}}
                {{--                </div>--}}
            </div>
        </div>
    </nav>
</header>
{{--
<script>
    $(function() {
        $(window).scroll(function () {
            if ($(this).scrollTop() > 50) {
                $('header').addClass('activate')
            } else {
                $('header').removeClass('activate')
            }
        });
    });
</script> --}}
<script>
    // window.onscroll = function () {
    //     scrollFunction()
    // };
    //
    // function scrollFunction() {
    //     if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
    //         // document.getElementById("header").style.backgroundColor = "transparent";
    //         document.getElementById("logohone").style.display = "none";
    //         document.getElementById("logotwo").style.display = "block";
    //     } else {
    //         // document.getElementById("header").style.backgroundColor = "white";
    //         document.getElementById("logohone").style.display = "block";
    //         document.getElementById("logotwo").style.display = "none";
    //     }
    // }
</script>