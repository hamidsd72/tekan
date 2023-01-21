@extends('layouts.admin')
@section('css')
@endsection
@section('content')
    <section class="container-fluid">
        <div class="card res_table">
            <div class="card-header">
                @if (auth()->user()->id==$id)
                    <a href="{{route('admin.daily-schedule-org-performance.create')}}" class="btn btn-primary my-2">افزودن {{$title1}}</a>
                @endif
            </div>
            <div class="card-body pt-2">
                <table class="table table-bordered table-hover mb-2 @if($items->count()) tbl_1 @endif">
                    <thead> 
                        <tr>
                            <th>#</th>
                            <th>نوع اقدام</th>
                            <th>نام شخص</th>
                            <th>تاریخ</th>
                            @if(count($items)>0)
                                <th>عملیات</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                            @foreach($items as $index=>$item)
                                <tr>
                                    <td>{{$index+1}}</td>
                                    <td>{{$item->label?$item->label->label:'__________'}}</td>
                                    <td>{{ $item->name?$item->name:'__________' }}</td>
                                    <td>
                                        @if ($item->activate && $item->user_id==auth()->user()->id)
                                            <form action="{{route('admin.daily-schedule-org-performance.update',$item->id)}}" method="post" class="d-flex" style="max-width: 200px">
                                                @csrf
                                                @method('PATCH')
                                                <input type="text" name="time" id="time" value="{{$item->date}}" class="form-control date_p1" style="max-width: 75px">
                                                <button type="submit" class="btn btn-sm">تغییر تاریخ</button>
                                            </form>
                                        @else
                                            {{$item->date}}
                                        @endif
                                    </td>
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
                                </tr>
                            @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

@endsection

