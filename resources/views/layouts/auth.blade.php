<!doctype html>
<html lang="fa">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="icon" type="image/png" href="{{url($setting->icon_site)}}">
        <link rel="stylesheet" href="{{url('assets/auth/fonts/icomoon/style.css')}}">
        <link rel="stylesheet" href="{{url('assets/auth/css/owl.carousel.min.css')}}">
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="{{url('assets/auth/css/bootstrap.min.css')}}">
        <!-- Style -->
        <link rel="stylesheet" href="{{url('assets/auth/css/style.css')}}">
        <link rel="stylesheet" href="{{asset('admin/plugins/select2/select2.min.css')}}">
        <style>
            .select2-container--default .select2-selection--single { border: none !important; }
            .select2-container--default .select2-selection--single .select2-selection__rendered { color: gray; text-align: initial; background: #edf2f5; }
            body {font-family: "Vazirmatn" !important;}
            h1, h2, h3, h4, h5, h6 {font-family: "Vazirmatn" !important;}
            @font-face {
                font-family: 'Vazirmatn';
                src: url({{ asset('fonts/webfonts/Vazirmatn-Light.woff2') }});
                src: url({{ asset('fonts/webfonts/Vazirmatn-Light.woff2') }}) format('embedded-opentype'),
                url({{ asset('fonts/webfonts/Vazirmatn-Light.woff2') }}) format('woff2'),
                url({{ asset('fonts/webfonts/Vazirmatn-Light.woff2') }}) format('woff'),
                url({{ asset('fonts/ttf/Vazirmatn-Light.ttf') }}) format('truetype'),
            }
        </style>
        <title>{{$setting->title}} | {{$title ?? ''}}</title>
        {{-- <title> {{env('APP_NAME')}} | {{$title ?? ''}}</title> --}}
        @yield('styles')
    </head>
    <body>
        @yield('content')
        <!--Footer-->
        <footer class="footer fixed-bottom">
            <div class="my-2 text-center">
                Copyright © 2023 <a href="https://adib-it.com/fa">Adib Group</a>
            </div>
        </footer>
        <!-- End Footer-->
        <script src="{{url('assets/auth/js/jquery-3.3.1.min.js')}}"></script>
        <script src="{{url('assets/auth/js/popper.min.js')}}"></script>
        <script src="{{url('assets/auth/js/bootstrap.min.js')}}"></script>
        <script src="{{url('assets/auth/js/main.js')}}"></script>
        <script src="{{asset('admin/plugins/select2/select2.full.min.js')}}"></script>
        <script>
            $(function () { $('.select2').select2() });
        </script>
        @yield('scripts')
    </body>
</html>
