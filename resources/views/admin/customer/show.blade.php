@extends('layouts.admin')
@section('css')

@endsection
@section('content')
    <!-- Main content -->
    <section class="content">

        <ul class="nav nav-tabs" id="custom_tabs">
            <li class="ml-3  "><a class="btn bg-secondary-gradient mb-2 py-0 {{request()->has('tab') && request()->tab == 'information' ? 'active'  : ''}} {{ (!request()->has('tab') ? 'active'  :'') }}" data-toggle="tab" href="#information_tab">
                    <i class="fa fa-user"></i>
                    اطلاعات
                </a></li>
            <li class="ml-3 "><a class="btn  mb-2 py-0  {{request()->has('tab') && request()->tab == 'calls' ? 'active'  : ''}}" data-toggle="tab" href="#calls_tab">
                    <i class="fa fa-phone fa-rotate-270"></i>
                    تماس ها
                </a></li>

            <li class="ml-3 "><a class="btn  mb-2 py-0  {{request()->has('tab') && request()->tab == 'customer' ? 'active'  : ''}}" data-toggle="tab" href="#customer_tab">
                    <i class="fa fa-users"></i>
                    مشتری ها
                </a></li>
        </ul>

        <div class="tab-content">
            <div id="information_tab" class="tab-pane  {{request()->has('tab') && request()->tab == 'information' ? 'active'  : ''}} {{ (!request()->has('tab') ? 'active'  :'fade') }}">
                @include('admin.user._user_information',['user'=>$item])

            </div>
            <div id="calls_tab" class="tab-pane {{request()->has('tab') && request()->tab == 'calls' ? 'active'  : 'fade'}}">

                <div class="card">
                    <div class="card-header">
                        <button class="btn btn-sm float-left btn-dark" data-toggle="modal"
                                data-target="#store_call_modal">
                            ثبت جلسه
                        </button>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>#شناسه</th>
                                @role('مدیر')
                                <th>ایجاد کننده</th>
                                @endrole
                                <th>نوع تماس</th>
                                <th>تاریخ پیگیری</th>
                                <th>تاریخ ثبت</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($calls as $key=>$item)
                                <tr class="{{is_odd($key) ? 'bg-gray-light2' : ''}}">
                                    <td>{{$item->id}}</td>
                                    @role('مدیر')
                                    <td>{{$item->creator ? $item->creator->full_name : '-'}}</td>
                                    @endrole
                                    <td>{!! $item->status_badge !!}</td>
                                    <td>{{$item->follow_up_date?? 'تنظیم نشده'}}  {{$item->follow_up_time?? ''}}</td>
                                    <td>
                                        <span dir="ltr"> {{my_jdate($item->created_at,'Y/m/d H:i:s')}}</span>
                                    </td>
                                </tr>
                                @if(!empty($item->description) && !is_null($item->description))
                                    <tr class="{{is_odd($key) ? 'bg-gray-light2' : ''}}">
                                        <td colspan="6" class="text-right">
                                            توضیحات :
                                            {{$item->description}}
                                        </td>
                                    </tr>
                                @endif
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <div id="customer_tab" class="tab-pane {{request()->has('tab') && request()->tab == 'customer' ? 'active'  : 'fade'}}">
                <div class="card">
                    <div class="card-header">
                        <a href="{{route('admin.customer.create').'?redirect_url='.route('admin.customer.show',$id)."?tab=customer&creator_id=$id"}}" class="float-left btn btn-info btn-sm"><i
                                    class="fa fa-circle-o mtp-1 ml-1"></i>افزودن</a>
                    </div>
                    <div class="card-body">
                        @include('admin.user._table',['items'=>$customers,'action'=>false])
                    </div>
                </div>
            </div>

        </div>


    </section>

    <!-- modal create call -->
    @include('admin.call._modal_create',['users'=>null,'id','call_statuses'=>$call_statuses])

@endsection
@section('js')

@endsection