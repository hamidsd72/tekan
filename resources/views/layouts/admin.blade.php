<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <title>@yield('title')</title>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <link rel="Shortcut Icon" type="image/x-icon" href="{{ url($setting->icon) }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.min.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/admin.layout.min.css') }}"/>
    <!-- UIkit CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.0-rc.25/css/uikit.min.css"/>
   {{-- <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css"
    integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">--}}
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous"/>
    <!-- UIkit JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.0-rc.25/js/uikit.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.0-rc.25/js/uikit-icons.min.js"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('selectize/css.css')}}">
    <link rel="stylesheet" href="{{ asset('css/some-font.css') }}">

    <style>
         nav[aria-label="Pagination Navigation"]>div:first-child,
         nav[aria-label="Pagination Navigation"]>div>div:first-child
        {
            display: none!important;
        }
        .tab_box .tab-content
        {
            background: rgba(0,0,0,0.01);
            border: 1px solid rgba(0,0,0,0.3);
        }
        .d-ltr
        {
            direction: ltr!important;
            text-align: left!important;
        }
        .d-rtl
        {
            direction: rtl!important;
            text-align: right!important;
        }
        ::-webkit-input-placeholder { /* Chrome/Opera/Safari */
            text-align: right!important;
        }
        ::-moz-placeholder { /* Firefox 19+ */
            text-align: right!important;
        }
        :-ms-input-placeholder { /* IE 10+ */
            text-align: right!important;
        }
        :-moz-placeholder { /* Firefox 18- */
            text-align: right!important;
        }
        svg:not(:root){
            transform: rotate(180deg);
        }
        .hidden {
            display: block;
        }
        svg{
            width: 30px;
        }
    </style>
    @yield('stylesheets')


    <script>
        !function (t, e, n) {
            t.yektanetAnalyticsObject = n, t[n] = t[n] || function () {
                t[n].q.push(arguments)
            }, t[n].q = t[n].q || [];
            var a = new Date,
                r = a.getFullYear().toString() + "0" + a.getMonth() + "0" + a.getDate() + "0" + a.getHours(),
                c = e.getElementsByTagName("script")[0], s = e.createElement("script");
            s.id = "ua-script-yn-2165-adv";
            s.dataset.analyticsobject = n;
            s.async = 1;
            s.type = "text/javascript";
            s.src = "https://cdn.yektanet.com/rg_woebegone/scripts_v2/yn-2165-adv/rg.complete.js?v=" + r, c.parentNode.insertBefore(s, c)
        }(window, document, "yektanet");
    </script>
</head>
<body>
<nav class="top-nav navbar ">
    <div class="container">
        <div class="navbar-left">
 
            <div class="notify pull-left">
                <li class="dropdown-toggle" id="notify" data-toggle="dropdown"><i class="icon-brain text-grey-400"></i>
                </li>
                <ul class="notify-menu dropdown-menu" role="notify" aria-labelledby="notify">
                    <div class="notify-content-heading pull-right w-100">آخرین فعالیت ها
                        <i class="icon-stats-bars2 pull-left text-grey-300"></i>
                    </div>
                    @foreach($activities as $item)
                        <li class="notify-media">
                            @if($item->type == 'store')
                                <div class="notify-media-right">
                                    <span class="notify-icon-add pull-right w-100"><i class="fa fa-plus"></i></span>
                                </div>
                            @elseif($item->type == 'update')
                                <div class="notify-media-right">
                                    <span class="notify-icon-edit pull-right w-100"><i class="fa fa-edit"></i></span>
                                </div>
                            @elseif($item->type == 'delete')
                                <div class="notify-media-right">
                                    <span class="notify-icon-remove pull-right w-100"><i
                                            class="fa fa-remove"></i></span>
                                </div>
                            @endif
                            <div class="notify-media-body">
                                <a href="javascript:void(0)" class="media-heading">
                                    <span class="text-semibold text-grey-600">{{ $item->user->name }}</span>
                                    <span
                                        class="media-annotation pull-left">{{ my_jdate($item->created_at, 'Y/m/d H:i') }}</span>
                                </a>
                                <span class="text-muted">{{ $item->text }}...</span>
                            </div>
                        </li>
                    @endforeach
                    @if(count($activities)<=0)
                        <li class="notify-media">
                            <div class="notify-media-right">
                                <span class="notify-icon-none pull-right w-100"><i
                                        class="fa fa-remove text-grey-300"></i></span>
                            </div>
                            <div class="notify-media-body">
                                <a href="javascript:void(0)" class="media-heading">
                                    <span class="text-semibold text-grey-600">فعالیتی وجود ندارد</span>
                                    <span class="media-annotation pull-left">{{ date('H:i') }}</span>
                                </a>
                                <span class="text-muted">فعالیتی برای نمایش وجود ندارد...</span>
                            </div>
                        </li>
                    @endif
                    <div class="notify-content-footer pull-right w-100">
                        <a href="{{ route('admin-activities') }}" data-popup="tooltip"
                           title="تمام پیام ها"><i
                                class="icon-menu display-block"></i></a>
                    </div>
                </ul>
            </div>
        </div>
        <div class="navbar-right" data-direction="rtl">
            <div class="dropdown">
                <a href="javascript:void(0)" class="dropdown-toggle" id="user" data-toggle="dropdown"
                   aria-haspopup="true" aria-expanded="false"><i
                        class="now-ui-icons users_circle-08 ml-2"></i>{{ \Illuminate\Support\Facades\Auth::user()->name }}
                </a>
                <div class="dropdown-menu" aria-labelledby="user">
                    <a href="{{route('user.index')}}" class="dropdown-item">
                        صفحه اصلی سایت
                    </a>
                    <a href="javascript:void(0)" class="dropdown-item" onclick="$('.logout').submit()">
                        <form action="{{ url('logout') }}" method="POST" class="logout hidden">{{ csrf_field() }}</form>
                       خروج</a>
                </div>
            </div>
        </div>
    </div>
</nav>



<div class="content pull-right w-100">
    <div class="container" data-direction="rtl">
        <div class="row">
            {{--menues--}}
            @role('ادمین ارشد')
                    <div class="col-lg-3">
                        <div class="sidebar list-group mb-2">
                            <a href="{{route('admin-home')}}" class="sidebar-title list-group-item active"><i
                                    class="fa fa-bars text-size-large ml-2"></i>شرکت تکان انرژی</a>

{{--                            <a href="{{ route('admin-user-list') }}" class="list-group-item list-group-item-action"><i--}}
{{--                                    class="fas fa-circle-notch text-size-small ml-2"></i>لیست کاربران</a>--}}

{{--                            <a href="{{ route('email-list') }}" class="list-group-item list-group-item-action"><i--}}
{{--                                    class="fas fa-circle-notch text-size-small ml-2"></i>لیست خبرنامه</a>--}}

{{--                            <a href="{{ route('live-list') }}" class="list-group-item list-group-item-action"><i--}}
{{--                                    class="fas fa-circle-notch text-size-small ml-2"></i>لیست ویدئو ها</a>--}}

                            <div class="sidebar-a list-group-item list-group-item-action" id="b1">
                                <i class="fas fa-circle-notch text-size-small ml-2"></i>محصولات
                                <div class="sidebar-ul" id="s1">
                                    <a href="{{ route('admin-product-category-list') }}"
                                       class="list-group-item list-group-item-action"><i
                                            class="fas fa-circle-notch text-size-small ml-2"></i>دسته بندی</a>
                                    <a href="{{ route('admin-product-brand-list') }}"
                                       class="list-group-item list-group-item-action"><i
                                                class="fas fa-circle-notch text-size-small ml-2"></i>برند</a>
                                    <a href="{{ route('admin-product-list') }}"
                                       class="list-group-item list-group-item-action"><i
                                                class="fas fa-circle-notch text-size-small ml-2"></i>محصولات</a>
                                    <a href="{{ route('admin-product-comment-list') }}"
                                       class="list-group-item list-group-item-action"><i
                                                class="fas fa-circle-notch text-size-small ml-2"></i>نظرات
                                        محصولات@if(count($comment_product))<span
                                                style="color: red;"> ({{count($comment_product)}}
                                             جدید )</span>@endif</a>

                                </div>
                            </div>
                            <div class="sidebar-a list-group-item list-group-item-action" id="b2">
                                <i class="fas fa-circle-notch text-size-small ml-2"></i>بلاگ
                                <div class="sidebar-ul" id="s2">
                                    <a href="{{ route('admin-blog-list','news') }}"
                                       class="list-group-item list-group-item-action"><i
                                                class="fas fa-circle-notch text-size-small ml-2"></i>اخبار</a>
                                    <a href="{{ route('admin-blog-list','article') }}"
                                       class="list-group-item list-group-item-action"><i
                                                class="fas fa-circle-notch text-size-small ml-2"></i>مقالات</a>
                                    <a href="{{ route('admin-blog-list','service') }}"
                                       class="list-group-item list-group-item-action"><i
                                                class="fas fa-circle-notch text-size-small ml-2"></i>خدمات</a>


                                    <a href="{{ route('admin-article-comment-list') }}"
                                       class="list-group-item list-group-item-action"><i
                                                class="fas fa-circle-notch text-size-small ml-2"></i>نظرات
                                        بلاگ ها@if(count($comment_blog))<span
                                                style="color: red;"> ({{count($comment_blog)}}
                                             جدید )</span>@endif</a>

                                    {{--<a href="{{ route('admin-project-list') }}"
                                       class="list-group-item list-group-item-action"><i
                                                class="fas fa-circle-notch text-size-small ml-2"></i>پروژه ها</a>--}}

                                </div>
                            </div>
                            <div class="sidebar-a list-group-item list-group-item-action" id="b12">
                                <i
                                        class="fas fa-circle-notch text-size-small ml-2"></i>ویدئو ها
                                <div class="sidebar-ul" id="s12">
                                    <a href="{{ route('admin-learn-category.index') }}" class="list-group-item list-group-item-action">
                                        <i class="fas fa-circle-notch text-size-small ml-2"></i>مشاهده دسته بندی ها
                                    </a>
                                    
                                    <a href="{{route('admin-video.index')}}" class="list-group-item list-group-item-action">
                                        <i class="fas fa-circle-notch text-size-small ml-2"></i>مشاهده ویدئو ها
                                    </a>
                                    
                                </div>
                            </div>
                            <div class="sidebar-a list-group-item list-group-item-action" id="b13">
                                <i
                                        class="fas fa-circle-notch text-size-small ml-2"></i>گالری
                                <div class="sidebar-ul" id="s13">
                                    <a href="{{ route('admin-gallery-category.index') }}" class="list-group-item list-group-item-action">
                                        <i class="fas fa-circle-notch text-size-small ml-2"></i>مشاهده دسته بندی ها
                                    </a>
                                </div>
                            </div>
                            <div class="sidebar-a list-group-item list-group-item-action" id="b3">
                                <i
                                        class="fas fa-circle-notch text-size-small ml-2"></i>درباره ما
                                <div class="sidebar-ul" id="s3">
                                    <a href="{{ route('admin-about-edit',1) }}" class="list-group-item list-group-item-action"><i
                                                class="fas fa-circle-notch text-size-small ml-2"></i>درباره ما</a>
                                   {{-- <a href="{{route('admin-about-faq-list')}}" class="list-group-item list-group-item-action"><i
                                                class="fas fa-circle-notch text-size-small ml-2"></i>پرسش و پاسخ درباره ما</a>
                                    <a href="{{route('admin-about-team-list')}}" class="list-group-item list-group-item-action"><i
                                                class="fas fa-circle-notch text-size-small ml-2"></i>سرمایه انسانی</a>
                                    <a href="{{route('admin-about-bank-list')}}" class="list-group-item list-group-item-action"><i
                                                class="fas fa-circle-notch text-size-small ml-2"></i>بانک ها</a>
                                    <a href="{{route('admin-about-branch-list')}}" class="list-group-item list-group-item-action"><i
                                                class="fas fa-circle-notch text-size-small ml-2"></i>لیست شعب</a>
                                    <a href="{{route('admin-about-feature-list')}}" class="list-group-item list-group-item-action"><i
                                                class="fas fa-circle-notch text-size-small ml-2"></i>ویژگی های ما</a>--}}
                                </div>
                            </div>
                            {{--<div class="sidebar-a list-group-item list-group-item-action" id="b4">
                                <i
                                        class="fas fa-circle-notch text-size-small ml-2"></i>پرسش و پاسخ
                                <div class="sidebar-ul" id="s4">
                                    <a href="{{ route('admin-faq-cat-list') }}"
                                       class="list-group-item list-group-item-action"><i
                                                class="fas fa-circle-notch text-size-small ml-2"></i>دسته پرسش و پاسخ</a>
                                    <a href="{{ route('admin-faq-list') }}"
                                       class="list-group-item list-group-item-action"><i
                                                class="fas fa-circle-notch text-size-small ml-2"></i>پرسش و پاسخ</a>
                                </div>
                            </div>--}}
                            <div class="sidebar-a list-group-item list-group-item-action" id="b5">
                                <i
                                    class="fas fa-circle-notch text-size-small ml-2"></i>محتوا
                                <div class="sidebar-ul" id="s5">

                                    <a href="{{ route('admin-menu-list') }}" class="list-group-item list-group-item-action"><i
                                                class="fas fa-circle-notch text-size-small ml-2"></i>صفحات داینامیک</a>
                                   {{-- <a href="{{ route('admin-menu-information.index') }}" class="list-group-item list-group-item-action"><i
                                                class="fas fa-circle-notch text-size-small ml-2"></i>محتوا صفحات</a>--}}
                                    <a href="{{ route('admin_show_certificate') }}" class="list-group-item list-group-item-action"><i
                                                class="fas fa-circle-notch text-size-small ml-2"></i>گواهینامه ها</a>
                                    {{-- <a href="{{ route('admin_show_product_doctor') }}" class="list-group-item list-group-item-action"><i
                                                class="fas fa-circle-notch text-size-small ml-2"></i>توصیه های پزشکی</a> --}}
                                    <a href="{{ route('admin-contact-info-edit',1) }}" class="list-group-item list-group-item-action"><i
                                                class="fas fa-circle-notch text-size-small ml-2"></i>صفحه تماس با ما</a>
                                   {{-- <a href="{{ route('admin-partner-list') }}"
                                       class="list-group-item list-group-item-action"><i
                                                class="fas fa-circle-notch text-size-small ml-2"></i>همکاران ما</a>--}}
                                    <a href="{{ route('upload') }}" class="list-group-item list-group-item-action"><i
                                            class="fas fa-circle-notch text-size-small ml-2"></i>آپلود فایل</a>

                                </div>
                            </div>
                            {{--<div class="sidebar-a list-group-item list-group-item-action" id="b6">
                                <i class="fas fa-circle-notch text-size-small ml-2"></i>استخدام
                                <div class="sidebar-ul" id="s6">
                                    <a href="{{ route('admin.employment.page.edit') }}"
                                       class="list-group-item list-group-item-action"><i
                                                class="fas fa-circle-notch text-size-small ml-2"></i>ویرایش صفحه فرم</a>
                                    <a href="{{ route('admin.employment.page.list') }}"
                                       class="list-group-item list-group-item-action"><i
                                                class="fas fa-circle-notch text-size-small ml-2"></i>لیست صفحات داخلی</a>
                                    <a href="{{ route('admin.employment.list') }}"
                                       class="list-group-item list-group-item-action"><i
                                                class="fas fa-circle-notch text-size-small ml-2"></i>لیست فرم @if(count($employment))<span
                                                style="color: red;"> ({{count($employment)}}
                                              جدید )</span>@endif</a></a>

                                </div>
                            </div>--}}
                            <div class="sidebar-a list-group-item list-group-item-action" id="b7">
                                <i
                                    class="fas fa-circle-notch text-size-small ml-2"></i>فرم ها
                                <div class="sidebar-ul" id="s7">
                                    <a href="{{ route('admin-contact-list') }}"
                                       class="list-group-item list-group-item-action"><i
                                            class="fas fa-circle-notch text-size-small ml-2"></i>تماس با ما@if(count($contact))<span
                                            style="color: red;"> ({{count($contact)}}
                                              جدید )</span>@endif</a>
                                </div>
                            </div>
                            <div class="sidebar-a list-group-item list-group-item-action" id="b8">
                                <i
                                    class="fas fa-circle-notch text-size-small ml-2"></i>تنظیمات
                                <div class="sidebar-ul" id="s8">
                                    <a href="{{ route('admin-slider-list') }}"
                                       class="list-group-item list-group-item-action"><i
                                            class="fas fa-circle-notch text-size-small ml-2"></i>اسلایدر</a>
                                    <a href="{{ route('meta-list') }}"
                                       class="list-group-item list-group-item-action"><i
                                            class="fas fa-circle-notch text-size-small ml-2"></i>meta</a>
                                    <a href="{{ route('change-pass')}}"
                                       class="list-group-item list-group-item-action"><i
                                            class="fas fa-circle-notch text-size-small ml-2"></i>تغییر رمز عبور</a>
                                    <a href="{{ route('admin-setting-edit',1)}}"
                                       class="list-group-item list-group-item-action"><i
                                            class="fas fa-circle-notch text-size-small ml-2"></i>تنظیمات سایت</a>
                                </div>
                            </div>
                            <a href="{{ route('admin-activities') }}"
                               class="list-group-item list-group-item-action"><i
                                        class="fas fa-circle-notch text-size-small ml-2"></i>گزارشات</a>
                        </div>
                    </div>
            <div class="col-lg-9">
                @yield('body')
            </div>
            @endrole
           
            {{--end menues--}}
        </div>
    </div>
</div>
<footer class="footer pull-right w-100" data-direction="rtl">
    <div class="container">
        @php require_once 'jdf.php'; @endphp
        <p class="pull-right text-grey-300"></p>
        <p class="pull-left text-grey-300">کپی رایت �    {{ jdate('Y') }} |
            طراحی و اجرا توسط  <a
                href="http://adib-it.com/"
                target="_blank"
                rel="follow">تیم توسعه
                ادیب گستر عصر نوین</a>

            ، تمامی حقوق محفوظ
            است.</p>
    </div>
</footer>
@if (session('status'))
    <div class="bg-alert animated fadeIn">
        <div class="alert-popup animated fadeInDown bg-{{ session('status') }}">
            <span class="pull-right text-size-base">{{ session('message') }}</span>
            <button class="alert-close pull-left">×</button>
        </div>
    </div>
@endif
<script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}"></script>

<script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js"></script>
<script type="text/javascript" src="{{ asset('selectize/js.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/admin.layout.min.js') }}"></script>




<script>
    $(".key_word").selectize({
        delimiter: ",",
        plugins: {
            remove_button: {
                label: "×"
            }
        },
        persist: false,
        createOnBlur: true,
        create: true,
        copyClassesToDropdown:false
    });

    $('#s1').toggle();
    $('#s2').toggle();
    $('#s3').toggle();
    $('#s4').toggle();
    $('#s5').toggle();
    $('#s6').toggle();
    $('#s7').toggle();
    $('#s8').toggle();
    $('#s9').toggle();
    $('#s12').toggle();

    $('#b1').click(function () {
        $('#s1').toggle();
    })
    $('#b2').click(function () {
        $('#s2').toggle();
    })
    $('#b3').click(function () {
        $('#s3').toggle();
    })
    $('#b33').click(function () {
        $('#s33').toggle();
    })
    $('#b4').click(function () {
        $('#s4').toggle();
    })
    $('#b5').click(function () {
        $('#s5').toggle();
    })
    $('#b6').click(function () {
        $('#s6').toggle();
    })
    $('#b7').click(function () {
        $('#s7').toggle();
    })
    $('#b8').click(function () {
        $('#s8').toggle();
    });
    $('#b9').click(function () {
        $('#s9').toggle();
    });
    $('#b10').click(function () {
        $('#s10').toggle();
    });
    $('#b11').click(function () {
        $('#s11').toggle();
    });
    $('#b12').click(function () {
        $('#s12').toggle();
    });

</script>
<script>
        {{-- edit --}}
    let i = $('.fa-pencil');
    i.removeClass();
    i.addClass('far fa-edit');

    // delete
    let j = $('.fa-trash-o');
    j.removeClass();
    j.addClass('fa fa-trash');

    // reply
    let k = $('.fa-mail-reply');
    k.removeClass();
    k.addClass('fas fa-reply');
</script>
<script>
    $('.sidebar-title').click(function () {
        $(this).parent().find('.list-group-item-action').toggleClass('d-block');
    })
</script>
<script>
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>
@stack('scripts')
</body>
</html>
