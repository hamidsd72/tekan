@extends('layouts.admin')
<style>
    .card-primary.card-outline, .res_table {
        min-height: 500px;
    }
    .form-control:disabled, .form-control[readonly] , textarea {
        cursor: auto !important;
    }
</style>
@section('content')
    <section class="container-fluid">
        <div class="row">
            
            {{-- @can('target_system_list') --}}
                <div class="col">
                    <div class="card card-primary card-outline">
                        <div class="card-header"><i class="fa fa-line-chart mx-1"></i>هدف سیستمی {{my_jdate(Carbon\Carbon::today(),'F')}} ماه</div>
                        <div class="card-body box-profile">
                            <div class="form-group">
                                {{ Form::label('level', 'هدف لول ماه') }}
                                {{ Form::text('level',$nItem?$nItem->level:null, array('class' => 'form-control','onkeyup'=>'number_price(this.value)', 'readonly')) }}
                            </div>
                            <div class="form-group">
                                {{ Form::label('personal', 'هدف درآمد از فروش شخصی') }}
                                {{ Form::text('personal',$nItem?number_format($nItem->personal):null, array('class' => 'form-control','onkeyup'=>'number_price(this.value)', 'readonly')) }}
                            </div>
                            <div class="form-group">
                                {{ Form::label('network', 'هدف درآمد از شبکه سازی') }}
                                {{ Form::text('network',$nItem?number_format($nItem->network):null, array('class' => 'form-control','onkeyup'=>'number_price2(this.value)', 'readonly')) }}
                            </div>
            
                            <div class="float-left"><button class="btn btn-info mt-5" onclick="filter('time')">جستجو</button></div>
                            {{ Form::label('perDailyReport', 'برنامه شخصی عملکرد روزانه') }}
                            <div class="row my-1">
                                <div class="col-auto" style="padding-top: 10px;">
                                    {{-- @if ($step > 1) --}}
                                        <a href="{{ route('admin.target.customoze.date.index',[$user_id, $step-1, $step2]) }}" class="text-primary h6"><< بعد</a>
                                    {{-- @endif --}}
                                </div>
                                <div class="col-auto p-0">
                                    <input type="text" id="time" name="time" value="{{$time}}" class="form-control text-center date_p1" style="max-width: 128px;" autocomplete="off" readonly required>
                                </div>
                                <div class="col-auto" style="padding-top: 10px;">
                                    <a href="{{ route('admin.target.customoze.date.index',[$user_id, $step+1, $step2]) }}" class="text-primary h6">قبل >></a>
                                </div>
                            </div>
                            <ul id="perDailyReport" style="width: 100%;overflow: auto;">
                                @if ($perDailyReport->count())
                                    <table class="table table-bordered table-hover mb-2 tbl_1">
                                        <thead> 
                                            <tr>
                                                <th>#</th>
                                                <th>نوع اقدام</th>
                                                <th>نام شخص</th>
                                                <th>عملیات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($perDailyReport as $index=>$daily)
                                                <tr @if ($daily->status=='pending' && $daily->time===null) class="bad" @endif>
                                                    <td>{{$index+1}}</td>
                                                    <td>{{$daily->label}}</td>
                                                    <td>{{ $daily->name?$daily->name:'__________' }}</td>
                                                    
                                                    <td class="text-center">
                                                        <div class="d-flex">
                                                            @can('daily_schedule_4_1_status')
                                                                <form action="{{route('admin.daily-schedule-quad-performance.update',$daily->id)}}" method="post">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <input type="hidden" name="status" id="status" value="active">
                                                                    <button type="submit" class="btn btn-sm btn-success">تایید</button>
                                                                </form>
                    
                                                                <form action="{{route('admin.daily-schedule-quad-performance.update',$daily->id)}}" method="post" class="mx-2">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <input type="hidden" name="status" id="status" value="deactive">
                                                                    <button type="submit" class="btn btn-sm btn-danger">رد</button>
                                                                </form>
                                                            @endcan
                                                            @can('daily_schedule_4_1_date')
                                                                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#daily{{$daily->id}}">تغییر تاریخ</button>
                                                            @endcan
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </ul>
                        
                        </div>
                    </div>

                </div>
            {{-- @endcan --}}

            <div class="col-lg-1"></div>

            {{-- @can('target_me_list') --}}
                <div class="col">
                    <div class="card card-primary card-outline">
                        <div class="card-header"><i class="fa fa-fire mx-1"></i>هدف سوزان {{my_jdate(Carbon\Carbon::today(),'F')}} ماه</div>
                        <div class="card-body box-profile">
                            <div class="form-group">
                                {{ Form::label('burning', 'هدف سوزان') }}
                                {{ Form::text('burning',$nItem?$nItem->burning:null, array('class' => 'form-control' ,'readonly')) }}
                            </div>
                            <div class="form-group">
                                {{ Form::label('other', 'سایر اهداف') }}
                                {{ Form::textarea('other',$nItem?$nItem->other:null, array('class' => 'form-control' ,'readonly')) }}
                            </div>
            
                            <div class="float-left"><button class="btn btn-info mt-5" onclick="filter('time2')">جستجو</button></div>
                            {{ Form::label('orgDailyReport', 'برنامه شخصی عملکرد برای سازمان') }}
                            <div class="row my-1">
                                <div class="col-auto" style="padding-top: 10px;">
                                    {{-- @if ($step2 > 1) --}}
                                        <a href="{{ route('admin.target.customoze.date.index',[$user_id, $step, $step2-1]) }}" class="text-primary h6"><< بعد</a>
                                    {{-- @endif --}}
                                </div>
                                <div class="col-auto p-0">
                                    <input type="text" id="time2" name="time2" value="{{$time2}}" class="form-control text-center date_p1" style="max-width: 128px;" autocomplete="off" readonly required>
                                </div>
                                <div class="col-auto" style="padding-top: 10px;">
                                    <a href="{{ route('admin.target.customoze.date.index',[$user_id, $step, $step2+1]) }}" class="text-primary h6">قبل >></a>
                                </div>
                            </div>
                            <ul id="orgDailyReport" style="width: 100%;overflow: auto;">
                                @if ($orgDailyReport->count())
                                    <table class="table table-bordered table-hover my-2 tbl_1">
                                        <thead> 
                                            <tr>
                                                <th>#</th>
                                                <th>نوع اقدام</th>
                                                <th>نام شخص</th>
                                                <th>عملیات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($orgDailyReport as $index=>$orgDaily)
                                                <tr @if ($orgDaily->status=='pending' && $orgDaily->time===null) class="bad" @endif>
                                                    <td>{{$index+1}}</td>
                                                    <td>{{$orgDaily->label?$orgDaily->label->label:'__________'}}</td>
                                                    <td>{{ $orgDaily->name?$orgDaily->name:'__________' }}</td>
                                                    <td class="text-center">
                                                        <div class="d-flex">
                                                            @can('daily_schedule_org_status')
                                                                <form action="{{route('admin.daily-schedule-quad-performance.update',$orgDaily->id)}}" method="post">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <input type="hidden" name="status" id="status" value="active">
                                                                    <button type="submit" class="btn btn-sm btn-success">تایید</button>
                                                                </form>
                                                                <form action="{{route('admin.daily-schedule-quad-performance.update',$orgDaily->id)}}" method="post" class="mx-2">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <input type="hidden" name="status" id="status" value="deactive">
                                                                    <button type="submit" class="btn btn-sm btn-danger">رد</button>
                                                                </form>
                                                            @endcan
                                                            @can('daily_schedule_org_date')
                                                                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#orgDaily{{$orgDaily->id}}">تغییر تاریخ</button>
                                                            @endcan
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </ul>
                        
                        </div>
                    </div>

                </div>
            {{-- @endcan --}}

        </div>
    </section>

    @foreach($perDailyReport as $index=>$daily)
        <div class="modal" id="daily{{$daily->id}}">
            <div class="modal-dialog mt-5">
                <div class="modal-content">
            
                    <!-- Modal Header -->
                    <div class="modal-header" style="direction: ltr;">
                        <h4 class="modal-title">{{ $daily->name?$daily->name:'__________' }}</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
            
                    <div class="modal-body">
                        @if ($daily->activate)
                            @can('daily_schedule_4_1_date')
                                <form action="{{route('admin.daily-schedule-org-performance.update',$daily->id)}}" method="post" class="d-flex mb-4" style="max-width: 200px">
                                    @csrf
                                    @method('PATCH')
                                    <input type="text" name="date" id="date" value="{{$daily->date}}" class="form-control date_p1" style="max-width: 75px">
                                    <button type="submit" class="btn btn-sm">تغییر تاریخ</button>
                                </form>
                            @endcan
                        @else
                            {{$daily->date}}
                        @endif

                        @if ($daily->time)
                            {{' زمان اجرا '.$daily->time}}
                        @else
                            @if ($daily->status=='pending')
                                @can('daily_schedule_4_1_date')
                                    <form action="{{route('admin.daily-schedule-quad-performance.update',$daily->id)}}" method="post" class="d-flex mr-2" style="max-width: 200px">
                                        @csrf
                                        @method('PATCH')
                                        <input type="time" name="time" id="time" value="{{$daily->time}}" class="form-control" style="max-width: 120px">
                                        <button type="submit" class="btn btn-sm">تنظیم ساعت</button>
                                    </form>
                                @endcan
                            @endif
                        @endif

                    </div>
                </div>
            </div>
        </div>
    @endforeach

    @foreach($orgDailyReport as $index=>$orgDaily)
        <div class="modal" id="orgDaily{{$orgDaily->id}}">
            <div class="modal-dialog mt-5">
                <div class="modal-content">
            
                    <!-- Modal Header -->
                    <div class="modal-header" style="direction: ltr;">
                        <h4 class="modal-title">{{ $orgDaily->name?$orgDaily->name:'__________' }}</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
            
                    <div class="modal-body">
                        @if ($orgDaily->activate)
                            @can('daily_schedule_org_date')
                                <form action="{{route('admin.daily-schedule-org-performance.update',$orgDaily->id)}}" method="post" class="d-flex mb-4" style="max-width: 200px">
                                    @csrf
                                    @method('PATCH')
                                    <input type="text" name="date" id="date" value="{{$orgDaily->date}}" class="form-control date_p1" style="max-width: 75px">
                                    <button type="submit" class="btn btn-sm">تغییر تاریخ</button>
                                </form>
                            @endcan
                        @else
                            {{$orgDaily->date}}
                        @endif

                        @if ($orgDaily->time)
                            {{' زمان اجرا '.$orgDaily->time}}
                        @else
                            @if ($orgDaily->status=='pending')
                                @can('daily_schedule_org_date')
                                    <form action="{{route('admin.daily-schedule-quad-performance.update',$orgDaily->id)}}" method="post" class="d-flex mr-2" style="max-width: 200px">
                                        @csrf
                                        @method('PATCH')
                                        <input type="time" name="time" id="time" value="{{$orgDaily->time}}" class="form-control" style="max-width: 120px">
                                        <button type="submit" class="btn btn-sm">تنظیم ساعت</button>
                                    </form>
                                @endcan
                            @endif
                        @endif

                    </div>
                </div>
            </div>
        </div>
    @endforeach

@endsection
@section('js')
<script>
    function filter(table) {
        
        if (table=='time') {
            var time = document.getElementById('time').value;
        } else {
            var time = document.getElementById('time2').value;
        }
        
        if (time) {
            var url = `{{url("/")}}/admin/api/v1/target/{{$user_id}}/filter/?${table}=${time}`;
            $.ajax({
                type: "GET",
                url:  url,
                success: function(dataVal) {
                    let items = dataVal.time;

                    if (table=='time') {
                        var ul = document.getElementById('perDailyReport');
                    } else {
                        var ul = document.getElementById('orgDailyReport');
                    }
                    ul.innerHTML = '';

                    for (let index = 0; index < items.length; index++) {
                        var item = items[index];
                        if (table=='time') {
                            ul.insertAdjacentHTML("beforeend",
                                `<li>${index+1} ${item.label} ${item.name}</li>`    
                            );
                        } else {
                            ul.insertAdjacentHTML("beforeend",
                                `<li>${index+1} ${item.label.label} ${item.name}</li>`    
                            );
                        }
                    }
                    
                },
                error: function() {
                    console.log(this.error);
                }
            });
        } else {
            alert('ابتدا تاریخ را انتخاب کنید');
        }
    }
</script>
@endsection
