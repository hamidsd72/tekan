@extends('layouts.admin')
@section('css')
@endsection
@section('content')
    <section class="container-fluid">
        <div class="card res_table">
            <div class="card-header"> 
                <div class="float-left">
                    <a class="btn btn-info my-2" href="{{route('admin.daily-schedule-org-performance.show',$id)}}">در انتظار</a>
                    <a class="btn btn-info my-2" href="{{route('admin.org-performance.custom.show.status',[$id,'deactive'])}}">رد شده</a>
                    <a class="btn btn-info my-2" href="{{route('admin.org-performance.custom.show.status',[$id,'active'])}}">تایید شده</a>
                </div>
                @can('daily_schedule_org_create')
                    @if (auth()->user()->id==$id)
                        <a href="{{route('admin.daily-schedule-org-performance.create')}}" class="btn btn-primary my-2">افزودن {{$title1}}</a>
                    @endif
                @endcan
            </div>
            <div class="card-body pt-2">
                <table class="table table-bordered table-hover mb-2 @if($items->count()) tbl_1 @endif">
                    <thead> 
                        <tr>
                            <th>#</th>
                            <th>نوع اقدام</th>
                            <th>نام شخص</th>
                            <th>تاریخ</th>
                            @can('daily_schedule_org_status')
                                @if(count($items)>0)
                                    <th>عملیات</th>
                                @endif
                            @endcan
                        </tr>
                    </thead>
                    <tbody>
                            @foreach($items as $index=>$item)
                                <tr @if ($item->status=='pending' && $item->time===null) class="bad" @endif>
                                    <td>{{$index+1}}</td>
                                    <td>{{$item->label?$item->label->label:'__________'}}</td>
                                    <td>{{ $item->name?$item->name:'__________' }}</td>
                                    <td>
                                        <div class="d-flex">
                                            @if ($item->activate && $item->user_id==auth()->user()->id)
                                                @can('daily_schedule_org_date')
                                                    <form action="{{route('admin.daily-schedule-org-performance.update',$item->id)}}" method="post" class="d-flex" style="max-width: 200px">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="text" name="date" id="date" value="{{$item->date}}" class="form-control date_p1" style="max-width: 75px">
                                                        <button type="submit" class="btn btn-sm">تغییر تاریخ</button>
                                                    </form>
                                                @endcan
                                            @else
                                                {{$item->date}}
                                            @endif

                                            @if ($item->time)
                                                {{' زمان اجرا '.$item->time}}
                                            @else
                                                @can('daily_schedule_org_date')
                                                    @if ($item->status=='pending')
                                                        <form action="{{route('admin.daily-schedule-org-performance.update',$item->id)}}" method="post" class="d-flex mr-2" style="max-width: 200px">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="time" name="time" id="time" value="{{$item->time}}" class="form-control" style="max-width: 120px">
                                                            <button type="submit" class="btn btn-sm">تنظیم ساعت</button>
                                                        </form>
                                                    @endif
                                                @endcan
                                            @endif
                                        </div>
                                    </td>
                                    @can('daily_schedule_org_status')
                                        <td class="text-center">
                                            @if ($item->activate && $item->user_id==auth()->user()->id)
                                                <div class="d-flex">
                                                    <form action="{{route('admin.daily-schedule-org-performance.update',$item->id)}}" method="post" class="mx-1">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" id="status" value="active">
                                                        <button type="submit" class="btn btn-sm btn-success">تایید</button>
                                                    </form>
        
                                                    <form action="{{route('admin.daily-schedule-org-performance.update',$item->id)}}" method="post" class="mx-1">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" id="status" value="deactive">
                                                        <button type="submit" class="btn btn-sm btn-danger">رد</button>
                                                    </form>
                                                </div>
                                            @endif
                                        </td>
                                    @endcan
                                </tr>
                            @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

@endsection

