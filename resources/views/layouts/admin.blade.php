<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>پنل مدیریت | {{$setting->title}}</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="icon" type="image/png" href="{{url($setting->icon_site)}}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset('admin/plugins/font-awesome/css/font-awesome.min.css')}}">
  <!-- Ionicons -->
{{-- <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css"> --}}
<!-- Select2 -->
  <link rel="stylesheet" href="{{asset('admin/plugins/select2/select2.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('admin/css/adminlte.min.css')}}">
  <link rel="stylesheet" href="{{asset('admin/css/adminlte.css?v.1.0.1')}}">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <!-- bootstrap rtl -->
  <link rel="stylesheet" href="{{asset('admin/css/bootstrap-rtl.min.css')}}">
  <!-- persian datepicker -->
{{-- <link rel="stylesheet" href="https://unpkg.com/persian-datepicker@1.2.0/dist/css/persian-datepicker.min.css"/>
<script src="https://unpkg.com/persian-date@1.1.0/dist/persian-date.min.js"></script>
<script src="https://unpkg.com/persian-datepicker@1.2.0/dist/js/persian-datepicker.min.js"></script> --}}
<!-- template rtl version -->
  <link rel="stylesheet" href="{{asset('admin/css/custom-style.css?v2')}}">
  <!-- Persian Data Picker -->
  <link rel="stylesheet" href="{{asset('admin/css/persian-datepicker.min.css?v.1.0.1')}}">
  {{-- <link rel="stylesheet" type="text/css" href="{{ asset('assets/styles/style.css?v.1.0.1') }}"> --}}
  <link rel="stylesheet" type="text/css" href="{{ asset('admin/css/dataTables.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/styles/style.css') }}">
  <style>
      @font-face {
          font-family: 'Vazirmatn';
          src: url({{ asset('fonts/webfonts/Vazirmatn-Light.woff2') }});
          src: url({{ asset('fonts/webfonts/Vazirmatn-Light.woff2') }}) format('embedded-opentype'),
          url({{ asset('fonts/webfonts/Vazirmatn-Light.woff2') }}) format('woff2'),
          url({{ asset('fonts/webfonts/Vazirmatn-Light.woff2') }}) format('woff'),
          url({{ asset('fonts/ttf/Vazirmatn-Light.ttf') }}) format('truetype'),
      }
      .sidebar {
        overflow-y: auto !important;
      }
      .chart-scrollable {
        margin: auto !important;
        overflow-x: auto !important;
      }
      .sidebar .nav-link p {
        font-size: 12px;
      }
      @media (min-width: 998px) {
        .max-w-lg-100 {
          max-width: 100px;
        }
        .max-w-lg-82 {
          max-width: 82px;
        }
        .max-w-lg-52 {
          max-width: 52px;
        }
      }
      .tree ul {
        overflow: auto;
        display: flex;
        direction: initial;
      }
      .tree li a {
        font-size: 12px !important;
        width: 80px;
      }
      #map-page {
          max-width: 100%;
          overflow: auto;
      }
      #map-page a path {
          fill: #c2c7d0;
      }
      #map-page a.activate path {
          fill: teal;
      }
      #map-page a:hover path {
          fill: teal;
      }
      #map-page a>path {
          transition: 200ms;
          transition-timing-function: ease-in-out;
          -webkit-transition: 200ms;
          -webkit-transition-timing-function: ease-in-out;
          stroke: #e9ecef;
          stroke-width: 1px;
          stroke-linejoin: round;
          cursor: pointer
      }
      @media (max-width: 640px) {
        .custom3 {
          max-width: 282px;
        }
        .chart-scrollable .frame {
          min-height: 480px;
          min-width: 920px;
        }
        .notify .dropdown-menu {
          left: -100px;
        }
      }
  </style>
  @yield('css')
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-light border-bottom">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      @if(!auth()->user()->hasRole('user'))
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
        </li>
      @endif
    </ul>

    <ul class="navbar-nav mr-auto ">
      <li class="nav-item dropdown notify">
        <a class="nav-link text-light" href="#" id="navbarDropdown" role="button" data-toggle="dropdown"
           aria-haspopup="true" aria-expanded="false">
          <i class="fa fa-bell " dir="ltr">
            @if($notifications)
              <span class="badge badge-warning">{{$notifications}}</span>
            @endif
          </i>
        </a>
        <ul class="dropdown-menu bg-light-gradient shadow-xl pt-0">
          <li class="head text-light bg-info-gradient py-1">
            <div class="col-lg-12 col-sm-12 col-12">
              @if($notifications)
                <a href="{{route('admin.mark-as-read')}}" class="float-left text-light text-sm ">رد همه</a>
              @endif
              <span>
                اعلانات
                @if($notifications)
                  <span>({{$notifications}})</span>
                @endif
              </span>
            </div>
          </li>
          @if($notifications)
            @foreach($unreaDailydNotifications as $item)
              <li class="notification-box py-0">
                <div class="row">
                  <div class="col-12 border rounded my-1">
                    <strong>
                      برنامه روزانه
                    </strong>
                    <div class="text-sm">
                      <small class="float-left" dir="ltr">{{$item->date}}</small>
                      <a href="{{route('admin.daily-schedule-quad-performance.show',auth()->user()->id)}}">نمایش</a>
                    </div>
                  </div>
                </div>
              </li>
            @endforeach
            @foreach($unreaOrgDailydNotifications as $item)
              <li class="notification-box py-0">
                <div class="row">
                  <div class="col-12 border rounded my-1">
                    <strong>
                      برنامه روزانه سازمانی
                    </strong>
                    <div class="text-sm">
                      <small class="float-left" dir="ltr">{{$item->date}}</small>
                      <a href="{{route('admin.daily-schedule-org-performance.show',auth()->user()->id)}}">نمایش</a>
                    </div>
                  </div>
                </div>
              </li>
            @endforeach
            @foreach($unreaMeetReportNotifications as $item)
              @if ($item->data)
                <li class="notification-box py-0">
                  <div class="row">
                    <div class="col-12 border rounded my-1">
                      <strong>
                        گزارش خوانده نشده
                      </strong>
                      <div class="text-sm">
                        <small class="float-left" dir="ltr">نمایش گزارش جلسه</small>
                        <a href="{{route('admin.workshop.show', (json_decode($item->data))->date ) }}">نمایش</a>
                      </div>
                    </div>
                  </div>
                </li>
              @endif
            @endforeach
            @if ($unreaMeetdNotifications ?? '')
              @foreach($unreaMeetdNotifications as $item)
                @if ($item->data)
                  <li class="notification-box py-0">
                    <div class="row">
                      <div class="col-12 border rounded my-1">
                        <strong>
                          امروز جلسه دارید
                        </strong>
                        <div class="text-sm">
                          <small class="float-left" dir="ltr">رفتن به جلسات</small>
                          <a href="{{route('admin.workshop.index') }}">نمایش</a>
                        </div>
                      </div>
                    </div>
                  </li>
                @endif
              @endforeach
            @endif
          @else
            <li class="notification-box py-4 border-bottom">
              <div class="col-12 text-center text-black-50 ">
                <div>برنامه ای وجود ندارد</div>
              </div>
            </li>
          @endif
        </ul>
      </li>

      <li class="nav-item dropdown has-treeview">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="fa fa-user ml-1"></i>
          {{auth()->user()->first_name.' '.auth()->user()->last_name}}
          <i class="right fa fa-angle-down mr-1"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-sm">
          <a href="{{route('admin.index')}}" class="dropdown-item">
            <i class="nav-icon fa fa-dashboard mx-1"></i>داشبورد
          </a>
          <a href="{{route('admin.profile.show')}}" class="dropdown-item">
            <i class="fa fa-user mx-1"></i>پروفایل
          </a>
          <a href="{{$active?route('admin.user.level-up'):'#'}}" class="dropdown-item {{$active?'':'text-secondary'}}">
            <i class="fa fa-level-up mx-1"></i>{{$next_role}}
          </a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="{{ route('logout') }}"
             onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fa fa-power-off ml-1"></i>خروج
          </a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
          </form>
        </div>
      </li>

    </ul>

  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4 pr-lg-2">
    <!-- Brand Logo -->
    <a href="{{route('admin.index')}}" class="brand-link">
     
      <span class="brand-text font-weight-light" style="font-size:14px">{{auth()->user()->roles()->first()?' پنل '.auth()->user()->roles()->first()->title:' پنل کاربری '}}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar" style="direction: ltr;">
      <div style="direction: rtl">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel d-flex">
          <a href="{{route('admin.index')}}" class="image my-auto">
            <img src="{{Auth::user()->photo? url(Auth::user()->photo->path) :asset('admin/img/user.png')}}"
                 class="img-circle elevation-2" alt="User Image">
          </a>
          <div class="info">

            <a href="{{route('admin.profile.show')}}" title="نمایش پروفایل" class="d-block">
              <span>
                {{auth()->user()->full_name}}
                <small> {{ auth()->user()->roles()->first() ?  "(".auth()->user()->roles->first()->title.")" : '' }}  </small>
              </span><br>
              <span>{{auth()->user()->mobile}}</span><br>
            </a>
          </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            @canany(['permission_cat_list','permission_list','role_list'])
              <li class="nav-item has-treeview  {{in_array(request()->route()->getName(), ['admin.permissionCat.index','admin.permission.index','admin.role.index']) ? 'menu-open' : ''}}">
                <a href="javascript:void(0);" class="nav-link">
                  <i class="nav-icon fa fa-key"></i>
                  <p>مجوزها<i class="right fa fa-angle-left"></i></p>
                </a>
                <ul class="nav nav-treeview border-bottom">
                  @can('permission_cat_list')
                    <li class="nav-item">
                      <a href="{{route('admin.permissionCat.index')}}" class="nav-link">
                        <i class="fa fa-circle-o nav-icon"></i>
                        <p>جداول</p>
                      </a>
                    </li>
                  @endcan
                  @can('permission_list')
                    <li class="nav-item">
                      <a href="{{route('admin.permission.index')}}" class="nav-link">
                        <i class="fa fa-circle-o nav-icon"></i>
                        <p>مجوزها</p>
                      </a>
                    </li>
                  @endcan
                  @can('role_list')
                    <li class="nav-item">
                      <a href="{{route('admin.role.index')}}" class="nav-link">
                        <i class="fa fa-circle-o nav-icon"></i>
                        <p>سطح دسترسی</p>
                      </a>
                    </li>
                  @endcan
                </ul>
              </li>
            @endcan

            <li class="nav-item has-treeview  {{in_array(request()->route()->getName(), ['admin.index','admin.profile.edit','admin.password.edit','admin.user.level-up.request']) ? 'menu-open' : ''}}">
              <a href="javascript:void(0);" class="nav-link">
                <i class="nav-icon fa fa-dashboard"></i>
                <p>داشبورد<i class="right fa fa-angle-left"></i></p>
              </a>
              <ul class="nav nav-treeview border-bottom">
                <li class="nav-item">
                  <a href="{{route('admin.index')}}" class="nav-link">
                    <i class="fa fa-circle-o nav-icon"></i>
                    <p>داشبورد</p>
                  </a>
                </li>
                @if ( in_array( auth()->user()->roles->first()?auth()->user()->roles->first()->title:'null', ['مدیر','برنامه نویس','حامی الماس - کارآفرین پویا','حامی پلاتین - کارآفرین کوشا','حامی طلایی','حامی نقره ای'] ) )
                  <li class="nav-item">
                    <a href="{{route('admin.user.level-up.request')}}" class="nav-link">
                      <i class="fa fa-circle-o nav-icon"></i>
                      <p>درخواست ارتقا کاربران</p>
                    </a>
                  </li>
                @endif
                <li class="nav-item">
                  <a href="{{route('admin.profile.edit')}}" class="nav-link">
                    <i class="fa fa-circle-o nav-icon"></i>
                    <p>ویرایش پروفایل</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{route('admin.password.edit')}}" class="nav-link">
                    <i class="fa fa-circle-o nav-icon"></i>
                    <p>ویرایش رمز عبور</p>
                  </a>
                </li>
              </ul>
            </li>

            @canany(['target_system_list','target_me_list'])
              <li class="nav-item has-treeview {{in_array(request()->route()->getName(), ['admin.target.show']) ? 'menu-open' : ''}}">
                <a href="javascript:void(0);" class="nav-link">
                  <i class="nav-icon fa fa-diamond"></i>
                  <p>اهداف<i class="right fa fa-angle-left"></i></p>
                </a>
                <ul class="nav nav-treeview border-bottom">
                  @can('target_system_list')
                    <li class="nav-item">
                      <a href="{{route('admin.target.show','سیستمی')}}" class="nav-link">
                        <i class="fa fa-circle-o nav-icon"></i>
                        <p>اهداف سیستمی</p>
                      </a>
                    </li>
                  @endcan
                  @can('target_me_list')
                    <li class="nav-item">
                      <a href="{{route('admin.target.show','شخصی')}}" class="nav-link">
                        <i class="fa fa-circle-o nav-icon"></i>
                        <p>اهداف شخصی</p>
                      </a>
                    </li>
                  @endcan
                </ul>
              </li>
            @endcan
            
            @canany(['daily_schedule_4_1_list','daily_schedule_org_list','daily_schedule_4_1_report_list','daily_schedule_org_report_list'])
              <li class="nav-item has-treeview {{in_array(request()->route()->getName(),
                                    ['admin.daily-schedule-quad-performance.show','admin.daily-schedule-report.show','admin.daily-schedule-org-performance.show','admin.daily-schedule-org-report.show']) ? 'menu-open' : ''}}">
                <a href="javascript:void(0);" class="nav-link">
                  <i class="nav-icon fa fa-hourglass-start"></i>
                  <p>برنامه روزانه<i class="right fa fa-angle-left"></i></p>
                </a>
                <ul class="nav nav-treeview border-bottom">
                  @can('daily_schedule_4_1_list')
                    <li class="nav-item">
                      <a href="{{route('admin.daily-schedule-quad-performance.show',auth()->user()->id)}}" class="nav-link">
                        <i class="fa fa-circle-o nav-icon"></i>
                        <p>ثبت عملکرد شخصی ۴×۱</p>
                      </a>
                    </li>
                  @endcan
                  @can('daily_schedule_org_list')
                    <li class="nav-item">
                      <a href="{{route('admin.daily-schedule-org-performance.show',auth()->user()->id)}}" class="nav-link">
                        <i class="fa fa-circle-o nav-icon"></i>
                        <p>ثبت عملکرد شخصی برای سازمان</p>
                      </a>
                    </li>
                  @endcan
                  @can('daily_schedule_4_1_report_list')
                    <li class="nav-item">
                      <a href="{{route('admin.daily-schedule-report.show',auth()->user()->id)}}" class="nav-link">
                        <i class="fa fa-circle-o nav-icon"></i>
                        <p>گزارش عملکرد شخصی ۴×۱</p>
                      </a>
                    </li>
                  @endcan
                  @can('daily_schedule_org_report_list')
                    <li class="nav-item">
                      <a href="{{route('admin.daily-schedule-org-report.show',auth()->user()->id)}}" class="nav-link">
                        <i class="fa fa-circle-o nav-icon"></i>
                        <p>گزارش عملکرد شخصی برای سازمان</p>
                      </a>
                    </li>
                  @endcan
                </ul>
              </li>
            @endcan

            @canany(['connection_list','connection_report_list'])
              <li class="nav-item has-treeview {{in_array(request()->route()->getName(),
                                  ['admin.connection-list.index','admin.connection-report.list']) ? 'menu-open' : ''}}">
                <a href="javascript:void(0);" class="nav-link">
                  <i class="nav-icon fa fa-comment"></i>
                  <p>ارتباطات شخصی<i class="right fa fa-angle-left"></i></p>
                </a>
                <ul class="nav nav-treeview border-bottom">
                  @can('connection_list')
                    <li class="nav-item">
                      <a href="{{route('admin.connection-list.index')}}" class="nav-link">
                        <i class="fa fa-circle-o nav-icon"></i>
                        <p>لیست</p>
                      </a>
                    </li>
                  @endcan
                  @can('connection_report_list')
                    <li class="nav-item">
                      <a href="{{route('admin.connection-report.list')}}" class="nav-link">
                        <i class="fa fa-circle-o nav-icon"></i>
                        <p>گزارش</p>
                      </a>
                    </li>
                  @endcan
                </ul>
              </li>
            @endcan
            
            @canany(['organization_member_list','organization_member_tree_list'])
              <li class="nav-item has-treeview {{in_array(request()->route()->getName(),['admin.organization-member.index','admin.organization-member-tree.index','admin.organization-map']) ? 'menu-open' : ''}}">
                <a href="javascript:void(0);" class="nav-link">
                  <i class="nav-icon fa fa-users"></i>
                  <p>اعضاء سازمان<i class="right fa fa-angle-left"></i></p>
                </a>
                <ul class="nav nav-treeview border-bottom">
                  @can('organization_member_list')
                    <li class="nav-item">
                      <a href="{{route('admin.organization-member.index')}}" class="nav-link"><i
                                class="fa fa-circle-o nav-icon"></i>
                        <p>لیست اعضاء</p>
                      </a>
                    </li>
                  @endcan
                  @can('organization_member_tree_list')
                    <li class="nav-item">
                      <a href="{{route('admin.organization-member-tree.index')}}" class="nav-link"><i
                                class="fa fa-circle-o nav-icon"></i>
                        <p>نمودار درختی</p>
                      </a>
                    </li>
                  @endcan
                  @can('organization_member_tree_list')
                    <li class="nav-item">
                      <a href="{{route('admin.organization-map')}}" class="nav-link"><i
                                class="fa fa-circle-o nav-icon"></i>
                        <p>نقشه پراکندگی سازمان</p>
                      </a>
                    </li>
                  @endcan
                </ul>
              </li>
            @endcan

            {{-- <li class="nav-item has-treeview  {{in_array(request()->route()->getName(),['admin.user.list','admin.user.list.tree','admin.user.list.roles']) ? 'menu-open' : ''}}">
                <a href="javascript:void(0);" class="nav-link">
                    <i class="nav-icon fa fa-user"></i>
                    <p>اعضاء سازمان<i class="right fa fa-angle-left"></i></p>
                </a>
                <ul class="nav nav-treeview border-bottom">
                    <li class="nav-item">
                        <a href="{{route('admin.user.list')}}" class="nav-link">
                            <i class="fa fa-circle-o nav-icon"></i>
                            <p>لیست اعضاء سازمان</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('admin.user.list.tree')}}" class="nav-link">
                            <i class="fa fa-circle-o nav-icon"></i>
                            <p>لیست اعضاء سازمان (درختی )</p>
                        </a>
                    </li>

                    @foreach (\App\Model\Role::roles() as $item)

                        <li class="nav-item">
                            <a href="{{route('admin.user.list.roles',$item->name)}}"
                            class="nav-link">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>لیست {{$item->name}}</p>
                            </a>
                        </li>

                    @endforeach
                </ul>
            </li> --}}

            @canany(['user_customer_list','user_customer_tree_list','user_customer_package_list','user_customer_report_list','user_customer_org_list','user_customer_org_report_list'])
              <li class="nav-item has-treeview {{in_array(request()->route()->getName(),
                                  ['admin.user-customer.index','admin.user-customer-tree.index-page','admin.user-customer-package.index','admin.user-customer-report.index','admin.subset.index','admin.subset.report']) ? 'menu-open' : ''}}">
                <a href="javascript:void(0);" class="nav-link">
                  <i class="nav-icon fa fa-user"></i>
                  <p>بانک مشتریان<i class="right fa fa-angle-left"></i></p>
                </a>
                <ul class="nav nav-treeview border-bottom">
                  @can('user_customer_list')
                    <li class="nav-item">
                      <a href="{{route('admin.user-customer.index')}}" class="nav-link">
                        <i class="fa fa-circle-o nav-icon"></i>
                        <p>مشتریان شخصی </p>
                      </a>
                    </li>
                  @endcan
                  @can('user_customer_tree_list')
                    <li class="nav-item">
                      <a href="{{route('admin.user-customer-tree.index-page')}}" class="nav-link">
                        <i class="fa fa-circle-o nav-icon"></i>
                        <p>نمودار مشتریان</p>
                      </a>
                    </li>
                  @endcan
                  @can('user_customer_package_list')
                    <li class="nav-item">
                      <a href="{{route('admin.user-customer-package.index')}}" class="nav-link">
                        <i class="fa fa-circle-o nav-icon"></i>
                        <p>پک پشتیبان</p>
                      </a>
                    </li>
                  @endcan
                  @can('user_customer_report_list')
                    <li class="nav-item">
                      <a href="{{route('admin.user-customer-report.index')}}" class="nav-link">
                        <i class="fa fa-circle-o nav-icon"></i>
                        <p>گزارش مشتریان شخصی</p>
                      </a>
                    </li>
                  @endcan
                  @can('user_customer_org_list')
                    <li class="nav-item">
                      <a href="{{route('admin.subset.index')}}" class="nav-link"><i class="fa fa-circle-o nav-icon"></i>
                        <p>مشتریان سازمانی من</p>
                      </a>
                    </li>
                  @endcan
                  @can('user_customer_org_report_list')
                    <li class="nav-item">
                      <a href="{{route('admin.subset.report')}}" class="nav-link"><i class="fa fa-circle-o nav-icon"></i>
                        <p>گزارش مشتریان سازمانی من</p>
                      </a>
                    </li>
                  @endcan
                </ul>
              </li>
            @endcan

            @canany(['four_action_list','four_action_report_list','potential_list','potential_org_list','potential_report_list','potential_org_report_list'])
              <li class="nav-item has-treeview {{ in_array( request()->route()->getName(),
                                  ['admin.four_action.create','admin.four_action.index','admin.potential-list.index','admin.potential-list.report.list','admin.potential-list.list'])  ? 'menu-open' : ''}}">
                <a href="javascript:void(0);" class="nav-link">
                  <i class="fa fa-align-center fa-rotate-270 nav-icon"></i>
                  <p>پتانسیل سازمان<i class="right fa fa-angle-left"></i></p>
                </a>
                <ul class="nav nav-treeview border-bottom">
                  @can('four_action_list')
                    <li class="nav-item">
                      <a href="{{route('admin.four_action.create')}}" class="nav-link"><i
                                class="fa fa-circle-o nav-icon"></i>
                        <p>عملکرد روزانه سازمان </p>
                      </a>
                    </li>
                  @endcan
                  @can('four_action_report_list')
                    <li class="nav-item">
                      <a href="{{route('admin.four_action.index')}}" class="nav-link"><i
                                class="fa fa-circle-o nav-icon"></i>
                        <p>گزارش عملکرد روزانه سازمان</p>
                      </a>
                    </li>
                  @endcan
                  @can('potential_list')
                    <li class="nav-item">
                      <a href="{{route('admin.potential-list.index')}}" class="nav-link"><i
                                class="fa fa-circle-o nav-icon"></i>
                        <p>لیست پتانسیل شخصی</p>
                      </a>
                    </li>
                  @endcan
                  @can('potential_org_list')
                    <li class="nav-item">
                      <a href="{{route('admin.potential-list.list')}}" class="nav-link"><i
                                class="fa fa-circle-o nav-icon"></i>
                        <p>لیست پتانسیل سازمان</p>
                      </a>
                    </li>
                  @endcan
                  @can('potential_report_list')
                    <li class="nav-item">
                      <a href="{{route('admin.potential-list.report.list',auth()->user()->id)}}" class="nav-link"><i
                                class="fa fa-circle-o nav-icon"></i>
                        <p>گزارش لیست پتانسیل شخصی</p>
                      </a>
                    </li>
                  @endcan
                  @can('potential_org_report_list')
                    <li class="nav-item">
                      <a href="{{route('admin.potential-list.report.list',[auth()->user()->id,'all'])}}" class="nav-link"><i
                                class="fa fa-circle-o nav-icon"></i>
                        <p>گزارش لیست پتانسیل سازمان</p>
                      </a>
                    </li>
                  @endcan


                  {{-- @role('مدیر')
                  <li class="nav-item">
                      <a href="{{route('admin.potential.index')}}"
                      class="nav-link">
                          <i class="fa fa-circle-o nav-icon"></i>
                          <p>مدیریت ایتم های پتانسیل </p>
                      </a>
                  </li>
                  @endrole

                  @if($potential_items && $potential_items->count())
                      @foreach($potential_items as $item)
                          <li class="nav-item">
                              <a href="{{route('admin.user.potential.list',['id'=>$item->id,'name'=>$item->name])}}"
                              class="nav-link">
                                  <i class="fa fa-circle-o nav-icon"></i>
                                  <p> {{$item->name}} </p>
                              </a>
                          </li>
                      @endforeach
                  @endif --}}

                </ul>
              </li>
            @endcan

            @canany(['month_package_list','month_package_report_list'])
              <li class="nav-item has-treeview {{in_array(request()->route()->getName(),['admin.monthly-package-report.index','admin.monthly-package.index']) ? 'menu-open' : ''}}">
                <a href="javascript:void(0);" class="nav-link">
                  <i class="nav-icon fa fa-calendar"></i>
                  <p>طرح ماهانه<i class="right fa fa-angle-left"></i></p>
                </a>
                <ul class="nav nav-treeview border-bottom">
                  @can('month_package_list')
                    <li class="nav-item">
                      <a href="{{route('admin.monthly-package.index')}}" class="nav-link"><i
                                class="fa fa-circle-o nav-icon"></i>
                        <p>طرح های ماهانه</p>
                      </a>
                    </li>
                  @endcan
                  @can('month_package_report_list')
                    {{-- <li class="nav-item">
                      <a href="{{route('admin.monthly-package-report.index')}}" class="nav-link"><i
                                class="fa fa-circle-o nav-icon"></i>
                        <p>گزارش عملکرد طرح های ماهانه</p>
                      </a>
                    </li> --}}
                  @endcan
                </ul>
              </li>
            @endcan

            @canany(['product_list','product_category_list','product_brand_list'])
              <li class="nav-item has-treeview {{in_array(request()->route()->getName(),['admin.product.index','admin.category.show']) ? 'menu-open' : ''}}">
                <a href="javascript:void(0);" class="nav-link">
                  <i class="nav-icon fa fa-product-hunt"></i>
                  <p>مدیریت محصولات<i class="right fa fa-angle-left"></i></p>
                </a>
                <ul class="nav nav-treeview border-bottom">
                  @can('product_list')
                    <li class="nav-item">
                      <a href="{{route('admin.product.index')}}" class="nav-link">
                        <i class="fa fa-circle-o nav-icon"></i>
                        <p>لیست محصولات </p>
                      </a>
                    </li>
                  @endcan
                  @can('product_category_list')
                    <li class="nav-item">
                      <a href="{{route('admin.category.show','برند')}}" class="nav-link">
                        <i class="fa fa-circle-o nav-icon"></i>
                        <p>لیست برند ها </p>
                      </a>
                    </li>
                  @endcan
                  @can('product_brand_list')
                    <li class="nav-item">
                      <a href="{{route('admin.category.show','دسته بندی')}}" class="nav-link">
                        <i class="fa fa-circle-o nav-icon"></i>
                        <p>لیست دسته بندی ها </p>
                      </a>
                    </li>
                  @endcan
                </ul>
              </li>
            @endcan

            @canany(['workshop_list','workshop_online_list'])
              <li class="nav-item has-treeview {{in_array(request()->route()->getName(),['admin.learn.index','admin.workshop.index',]) ? 'menu-open' : ''}}">
                <a href="javascript:void(0);" class="nav-link">
                  <i class="nav-icon fa fa-product-hunt"></i>
                  <p>جلسات آموزشی<i class="right fa fa-angle-left"></i></p>
                </a>
                <ul class="nav nav-treeview border-bottom">
                  @can('workshop_list')
                    <li class="nav-item">
                      <a href="{{route('admin.workshop.index')}}" class="nav-link">
                        <i class="fa fa-circle-o nav-icon"></i>
                        <p>Coach یا جلسات کارگاهی</p>
                      </a>
                    </li>
                  @endcan
                  @can('workshop_online_list')
                    <li class="nav-item">
                      <a href="{{route('admin.learn.index')}}" class="nav-link">
                        <i class="fa fa-circle-o nav-icon"></i>
                        <p>جلسات آموزشی</p>
                      </a>
                    </li>
                  @endcan
                </ul>
              </li>
            @endcan

            {{--  <li class="nav-item has-treeview  {{request()->route()->getName() == 'admin.report.performance.list' ? 'menu-open' : ''}}">
                <a href="javascript:void(0);" class="nav-link">
                    <i class="fa fa-align-right nav-icon"></i>
                    <p>
                        گزارش روزانه عملکرد سازمان
                        <i class="right fa fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview border-bottom">

                    @foreach(App\Model\Performance::types() as $name=> $text)
                        <li class="nav-item">
                            <a href="{{route('admin.report.performance.list',$name)}}"
                            class="nav-link">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>{{$text}} </p>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>

            <li class="nav-item has-treeview  {{request()->route()->getName() == 'admin.factor.index' ? 'menu-open' : ''}}">
                <a href="javascript:void(0);" class="nav-link">
                    <i class="fa fa-sellsy nav-icon"></i>
                    <p>
                        مدیریت فروش
                        <i class="right fa fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview border-bottom">
                    <li class="nav-item">
                        <a href="{{route('admin.factor.index')}}" class="nav-link">
                            <i class="fa fa-circle-o nav-icon"></i>
                            <p>لیست فروش ها</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('admin.factor.index','myself')}}" class="nav-link">
                            <i class="fa fa-circle-o nav-icon"></i>
                            <p> فروش های من</p>
                        </a>
                    </li>

                </ul>
            </li>

            <li class="nav-item has-treeview  {{request()->route()->getName() == 'admin.call.index' ? 'menu-open' : ''}}">
                <a href="javascript:void(0);" class="nav-link">
                    <i class="fa fa-phone-square fa-rotate-270 nav-icon"></i>
                    <p>
                    جلسات
                        <i class="right fa fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview border-bottom">
                    <li class="nav-item">
                        <a href="{{route('admin.call.index')}}" class="nav-link">
                            <i class="fa fa-circle-o nav-icon"></i>
                            <p>لیست جلسات </p>
                        </a>
                    </li>

                </ul>
            </li>

            <a href="{{route('admin.todo.list')}}" class="nav-link  {{request()->route()->getName() == 'admin.todo.list' ? 'bg-fade-aqua-light' : ''}}">
                <i class="fa fa-circle-o nav-icon px-2"></i>
                <p> برنامه روزانه </p>
                <span class="mx-1 text-white">  ( {{\App\Model\ConsultationCall::where('type','later_call')->where('consultant_id',auth()->user()->id)->count()}} ) </span>
            </a>


            <li class="nav-item has-treeview  {{in_array(request()->route()->getName(),['admin.agent.request.list','admin.agent.request.create']) ? 'menu-open' : ''}}">
                <a href="javascript:void(0);" class="nav-link">
                    <i class="nav-icon fa fa-cog"></i>
                    <p>
                        درخواست های نماینده شدن
                        <i class="fa fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview border-bottom">
                    <li class="nav-item">
                        <a href="{{route('admin.agent.request.list')}}" class="nav-link">
                            <i class="fa fa-circle-o nav-icon"></i>
                            <p>لیست درخواست ها
                                @if(auth()->user()->hasRole(['مدیر','نماینده مستقل']))
                                    <span class="mx-1 text-white">  ( {{\App\Model\Agent::where('seen',0)->count()}} ) </span>
                                @endif

                            </p>
                        </a>
                    </li>

                    @if(auth()->user()->hasRole(['کاربر']))
                        <li class="nav-item">
                            <a href="{{route('admin.agent.request.create')}}" class="nav-link">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>ایجاد درخواست
                                </p>
                            </a>
                        </li>
                    @endif


                </ul>
            </li>


            <a href="{{route('admin.consultant.list')}}" class="nav-link  {{request()->route()->getName() == 'admin.todo.list' ? 'bg-fade-aqua-light' : ''}}">
                <i class="fa fa-circle-o nav-icon px-2"></i>
                <p> لیست مشاوره ها</p>
                <span class="mx-1 text-white">  ( {{\App\Model\Consultation::where('consultant_id',auth()->user()->id)->count()}} ) </span>
            </a>

            <li class="nav-item has-treeview  {{in_array(request()->route()->getName(),['']) ? 'menu-open' : ''}}">
                <a href="javascript:void(0);" class="nav-link">
                    <i class="nav-icon fa fa-pie-chart"></i>
                    <p>
                        گزارشات
                        <i class="right fa fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview border-bottom">

                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fa fa-circle-o nav-icon"></i>
                            <p> رشد سالیانه</p>
                        </a>
                    </li>

                </ul>
            </li> --}}

            @canany(['setting_edit','org-performance-label_list'])
              <li class="nav-item has-treeview  {{in_array(request()->route()->getName(),['admin.setting.edit','admin.org-performance-label.index']) ? 'menu-open' : ''}}">
                  <a href="javascript:void(0);" class="nav-link">
                      <i class="nav-icon fa fa-cog"></i>
                      <p>تنظیمات اصلی<i class="fa fa-angle-left right"></i></p>
                  </a>
                  <ul class="nav nav-treeview border-bottom">
                      {{-- @can('')
                        <li class="nav-item">
                            <a href="{{route('admin.notification.index')}}" class="nav-link">
                                <i class="fa fa-circle-o nav-icon"></i><p>ارسال اعلان و پیام</p>
                            </a>
                        </li>
                      @endcan --}}
                      @can('org-performance-label_list')
                        <li class="nav-item">
                            <a href="{{route('admin.org-performance-label.index')}}" class="nav-link">
                                <i class="fa fa-circle-o nav-icon"></i><p>لیست گزینه های عملکرد سازمانی</p>
                            </a>
                        </li>
                      @endcan
                      @can('setting_edit')
                        <li class="nav-item">
                            <a href="{{route('admin.setting.edit')}}" class="nav-link">
                                <i class="fa fa-circle-o nav-icon"></i><p>تنظیمات</p>
                            </a>
                        </li>
                      @endcan
                  </ul>
              </li>
            @endcan
            @canany(['bazi_plan_list'])
              <li class="nav-item has-treeview  {{in_array(request()->route()->getName(),['admin.setting.edit','admin.org-performance-label.index']) ? 'menu-open' : ''}}">
                  {{-- <a href="javascript:void(0);" class="nav-link">
                      <i class="nav-icon fa fa-gamepad"></i>
                      <p>بازی<i class="fa fa-angle-left right"></i></p>
                  </a>
                  <ul class="nav nav-treeview border-bottom">
                      @can('bazi_plan_list')
                        <li class="nav-item">
                            <a href="{{route('admin.plan.game.index')}}" class="nav-link">
                                <i class="fa fa-circle-o nav-icon"></i><p>بازی با پلن</p>
                            </a>
                        </li>
                      @endcan
                  </ul> --}}
                  @can('bazi_plan_list')
                    <li class="nav-item">
                        <a href="{{route('admin.plan.game.index')}}" class="nav-link">
                            <i class="fa fa-gamepad nav-icon"></i><p>بازی با پلن</p>
                        </a>
                    </li>
                  @endcan
              </li>
            @endcan

          </ul>
        </nav>
      </div>
    </div>
  </aside>

  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="d-flex">
            <h1 class="m-0 mr-3 text-dark">{{$title1 ?? ''}}</h1>
          </div>
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <hr class="mt-0">
    <!-- /.content-header -->

    <!-- Content Header (Page header) -->
    <div class="d-lg-none">
      @include('includes.bottomNavigationBar')
    </div>
    <div class="p-3 px-lg-4">

      <div class="show_all_notification mb-3 col-md-8 col-lg-6 mx-auto">
        @if($notifications)
          @if ($unreaDailydNotifications->count() || $unreaOrgDailydNotifications->count())<h4>برنامه روزانه</h4>@endif
          @if ($unreaMeetdNotifications->count())<h4>Coach یا جلسات گارگاهی</h4>@endif
          @if ($unreaMeetReportNotifications->count())<h4>گزارشات جلسات گارگاهی</h4>@endif
          @foreach($unreaDailydNotifications as $item)
            <div class="text-center p-0 m-0 my-1 alert alert-info" role="alert">
              <a href="{{route('admin.daily-schedule-quad-performance.show',auth()->user()->id)}}">
                <h6 class="m-0 p-0 text-light pt-2">{{$item->label.' با '.$item->name}}</h6>
                رفتن به لیست برنامه روزانه ۴×۱
              </a>
              <button type="button" class="close h6" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i
                          class="fa fa-close"></i></span></button>
            </div>
          @endforeach
          @foreach($unreaOrgDailydNotifications as $item)
            <div class="text-center p-0 m-0 my-1 alert alert-info" role="alert">
              <a href="{{route('admin.daily-schedule-org-performance.show',auth()->user()->id)}}">
                <h6 class="m-0 p-0 text-light pt-2">{{$item->label->label.' با '.$item->name}}</h6>
                رفتن به لیست برنامه روزانه ۴×۱ سازمانی
              </a>
              <button type="button" class="close h6" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i
                          class="fa fa-close"></i></span></button>
            </div>
          @endforeach
          @foreach($unreaMeetReportNotifications as $item)
            @if ($item->data)
              <div class="text-center p-0 py-2 m-0 my-1 alert alert-info" role="alert">
                <a href="{{route('admin.workshop.show', (json_decode($item->data))->date ) }}">
                  گزارش خوانده نشده - نمایش گزارش جلسه
                </a>
                <button type="button" class="close h6" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i
                            class="fa fa-close"></i></span></button>
              </div>
            @endif
          @endforeach
          @if ($unreaMeetdNotifications ?? '')
            @foreach($unreaMeetdNotifications as $item)
              @if ($item->data)
                <div class="text-center p-0 py-2 m-0 my-1 alert alert-info" role="alert">
                  <a href="{{route('admin.workshop.index') }}">
                    امروز جلسه دارید - رفتن به جلسات
                  </a>
                  <button type="button" class="close h6" data-dismiss="alert" aria-label="Close"><span
                            aria-hidden="true"><i class="fa fa-close"></i></span></button>
                </div>
              @endif
            @endforeach
          @endif
        @endif
      </div>

      @yield('content')
    </div>
  </div>

  <footer class="main-footer text-left mb-5 pb-4 mb-lg-0" style="font-size: smaller;"><strong>copyright &copy; 2023 <a
              href="https://adib-it.com/">Adib Group</a></strong></footer>

  {{-- <div class="modal fade" id="create" tabindex="-1" role="dialog" aria-labelledby="createLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title">انتخاب دسته اصلی جهت افزودن خدمت</h5>
              </div>
              <div class="modal-body">
                  @foreach(\App\Model\ServiceCat::where('slug','!=','کد-تخفیف')->where('type','service')->get(['id','title','pic']) as $ServiceCat)
                      <a class="btn btn-danger my-2 m-lg-2" href="{{route('admin.service.create',$ServiceCat->id)}}">
                          <img src="{{url('/').'/'.$ServiceCat->pic}}" alt="{{$ServiceCat->title}}"
                              style="width:50px;height:50px;border-radius:50%;margin: 12px;">
                          <br>
                          {{$ServiceCat->title}}
                      </a>
                  @endforeach
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">انصراف</button>
              </div>
          </div>
      </div>
  </div> --}}

</div>

<!-- jQuery -->
<script src="{{asset('admin/plugins/jquery/jquery.min.js')}}"></script>
<!-- jQuery UI 1.11.4 -->
{{--<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>--}}
{{--<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->--}}
{{--<script>--}}
{{--    $.widget.bridge('uibutton', $.ui.button)--}}
{{--</script>--}}
<!-- Bootstrap 4 -->
<script src="{{asset('admin/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
{{--<!-- Morris.js charts -->--}}
{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>--}}
{{--<script src="{{asset('admin/plugins/morris/morris.min.js')}}"></script>--}}
{{--<!-- Sparkline -->--}}
{{--<script src="{{asset('admin/plugins/sparkline/jquery.sparkline.min.js')}}"></script>--}}
{{--<!-- jvectormap -->--}}
{{--<script src="{{asset('admin/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js')}}"></script>--}}
{{--<script src="{{asset('admin/plugins/jvectormap/jquery-jvectormap-world-mill-en.js')}}"></script>--}}
{{--<!-- jQuery Knob Chart -->--}}
{{--<script src="{{asset('admin/plugins/knob/jquery.knob.js')}}"></script>--}}
{{--<!-- daterangepicker -->--}}
{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>--}}
{{--<script src="{{asset('admin/plugins/daterangepicker/daterangepicker.js')}}"></script>--}}
{{--<!-- datepicker -->--}}
{{--<script src="{{asset('admin/plugins/datepicker/bootstrap-datepicker.js')}}"></script>--}}

{{--<!-- Slimscroll -->--}}
{{--<script src="{{asset('admin/plugins/slimScroll/jquery.slimscroll.min.js')}}"></script>--}}
{{--<!-- FastClick -->--}}
{{--<script src="{{asset('admin/plugins/fastclick/fastclick.js')}}"></script>--}}
<!-- AdminLTE App -->
<script src="{{asset('admin/js/adminlte.js')}}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{asset('admin/js/demo.js')}}"></script>
<!-- Persian Data Picker -->
<script src="{{asset('admin/js/persian-date.min.js')}}"></script>
<script src="{{asset('admin/js/persian-datepicker.min.js')}}"></script>
<!-- Select2 -->
<script src="{{asset('admin/plugins/select2/select2.full.min.js')}}"></script>
<script src="{{asset('admin/js/sweetalert2.js')}}"></script>
<script src="{{asset('admin/js/clipboard.js')}}"></script>
<script src="{{asset('admin/js/popper.js1.16.1.js')}}"></script>
<script src="{{asset('editor/laravel-ckeditor/ckeditor.js')}}"></script>
<script src="{{asset('editor/laravel-ckeditor/adapters/jquery.js')}}"></script>
<script src="{{asset('admin/js/valdation.js')}}"></script>
<script src="{{asset('admin/js/dataTables.js')}}"></script>
<script src="{{asset('admin/js/selectize.js')}}"></script>
<script src="{{asset('admin/js/temp.js')}}"></script>
<script>
    var textareaOptions = {
        filebrowserImageBrowseUrl: '{{ url('filemanager?type=Images') }}',
        filebrowserImageUploadUrl: '{{ url('filemanager/upload?type=Images&_token=') }}',
        filebrowserBrowseUrl: '{{ url('filemanager?type=Files') }}',
        filebrowserUploadUrl: '{{ url('filemanager/upload?type=Files&_token=') }}',
        language: 'fa'
    };
    $('.textarea').ckeditor(textareaOptions);
    @if(session()->has('err_message'))
    $(document).ready(function () {
        Swal.fire({
            title: "ناموفق",
            text: "{{ session('err_message') }}",
            icon: "warning",
            timer: 6000,
            timerProgressBar: true,
        })
    });
    @endif
    @if(session()->has('err_message'))
    $(document).ready(function () {
        Swal.fire({
            title: "ناموفق",
            text: "{{ session('err_message') }}",
            icon: "warning",
            timer: 6000,
            timerProgressBar: true,
        })
    });
    @endif
    @if(session()->has('flash_message'))
    $(document).ready(function () {
        Swal.fire({
            title: "موفق",
            text: "{{ session('flash_message') }}",
            icon: "success",
            timer: 6000,
            timerProgressBar: true,
        })
    })
    ;@endif
    @if(session()->has('call_message'))
    $(document).ready(function () {
        Swal.fire({
            title: "",
            text: "{{ session('call_message') }}",
            icon: "warning",
            timer: 6000,
            timerProgressBar: true,
        })
    });
    @endif
    @if (count($errors) > 0)
    $(document).ready(function () {
        Swal.fire({
            title: "ناموفق",
            icon: "warning",
            html:
                    @foreach ($errors->all() as $key => $error)
                        '<p class="text-right mt-2 ml-5" dir="rtl"> {{$key+1}} : ' +
                '{{ $error }}' +
                '</p>' +
                    @endforeach
                        '<p class="text-right mt-2 ml-5" dir="rtl">' +
                '</p>',
            timer: @if(count($errors)>3)parseInt('{{count($errors)}}') * 1500 @else 6000 @endif,
            timerProgressBar: true,
        })
    });
    @endif
    @if(isset($select_province) && $select_province)
    $(document).ready(function () {
        $('select[name=state_id]').on('change', function () {
            $.get("{{url('/')}}/city-ajax/" + $(this).val(), function (data, status) {
                $('select[name=city_id]').empty();
                $.each(data, function (key, value) {
                    $('select[name=city_id]').append('<option value="' + value.id + '">' + value.name + '</option>');
                });
                $('select[name=city_id]').trigger('change');
            });
        });

      {{--$.get("{{url('/')}}/city-ajax/" + $('#state_id').val(), function (data, status) {--}}
      {{--    $('select[name=city_id]').empty();--}}

      {{--    $.each(data, function (key, value) {--}}
      {{--        $('select[name=city_id]').append('<option value="' + value.id + '">' + value.name + '</option>');--}}
      {{--    });--}}
      {{--    $('select[name=city_id]').trigger('change');--}}
      {{--});--}}
    })
  @endif
</script>
@yield('js')
</body>
</html>
