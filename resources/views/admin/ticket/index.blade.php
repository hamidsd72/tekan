@extends('layouts.admin')
@section('css')

@endsection
@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-12">
                    <div class="card res_table">
                        <div class="card-header">
                            <h3 class="card-title float-right">{{$title2}}</h3>
                            @if (!Auth::user()->hasRole('مدیر'))
                                <a href="{{route('admin.ticket.create')}}" class="float-left btn btn-primary"><i class="fa fa-circle-o mtp-1 ml-1"></i>افزودن</a>
                            @endif
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body res_table_in">
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th class="border-bottom-0">ردیف</th>
                                    @if(Auth::user()->hasRole('مدیر'))
                                        <th class="border-bottom-0">ثبت کننده</th>
                                    @endif
                                    <th class="border-bottom-0">عنوان</th>
                                    <th class="border-bottom-0">اولویت</th>
                                    <th class="border-bottom-0">زمان ثبت</th>
                                    <th class="border-bottom-0">زمان آخرین ویرایش</th>
                                    <th class="border-bottom-0">عملیات</th>

                                </tr>
                                </thead>
                                <tbody>
                                @foreach($items as $key=>$item)
                                    <tr>
                                        <td class="{{Auth::user()->hasRole('مدیر') && $item->status=='1_active'?'bg-tbl-new':''}}">{{$key+1}}</td>
                                        @if(Auth::user()->hasRole('مدیر'))
                                        <td class="{{Auth::user()->hasRole('مدیر') && $item->status=='1_active'?'bg-tbl-new':''}}">{{$item->user_create?$item->user_create->first_name.' '.$item->user_create->last_name:'ثبت نشده'}}</td>
                                        @endif
                                            <td class="{{Auth::user()->hasRole('مدیر') && $item->status=='1_active'?'bg-tbl-new':''}}">{{$item->title}}
                                            @if($item->status=='3_closed')
                                                <span class="badge badge-danger">بسته شد</span>
                                            @elseif($item->status=='2_done')
                                                <span class="badge badge-info">انجام شد</span>
                                            @elseif($item->status=='1_active')
                                                <span class="badge badge-success">فعال</span>
                                            @endif
                                        </td>
                                        <td class="{{Auth::user()->hasRole('مدیر') && $item->status=='1_active'?'bg-tbl-new':''}}">
                                            @if($item->priority=='low')
                                                <span class="badge badge-success">کم</span>
                                            @elseif($item->priority=='medium')
                                                <span class="badge badge-warning">متوسط</span>
                                            @elseif($item->priority=='much')
                                                <span class="badge badge-danger">زیاد</span>
                                            @endif
                                        </td>
                                        <td class="{{Auth::user()->hasRole('مدیر') && $item->status=='1_active'?'bg-tbl-new':''}}">{{my_jdate($item->created_at,'Y/m/d H:i')}}</td>
                                        <td class="{{Auth::user()->hasRole('مدیر') && $item->status=='1_active'?'bg-tbl-new':''}}">{{my_jdate($item->updated_at,'Y/m/d H:i')}}</td>
                                        <td class="{{Auth::user()->hasRole('مدیر') && $item->status=='1_active'?'bg-tbl-new':''}}">
                                            <div class="d-flex">
                                                <a href="{{route('admin.ticket.show',$item->id)}}"
                                                   class="badge bg-info" data-toggle="tooltip" data-placement="top" title="مشاهده و پاسخ">
                                                    <i class="fa fa-eye"
                                                    ></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <div class="pag_ul">
                        {{ $items->links() }}
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
@section('js')

@endsection
