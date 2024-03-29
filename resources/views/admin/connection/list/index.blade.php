@extends('layouts.admin')
@section('css')
@endsection
@section('content')
    <section class="container-fluid">
        <div class="card res_table">
            <div class="card-header">
                @can('connection_create')
                    <a href="{{route('admin.connection-list.create')}}" class="btn btn-primary my-2">افزودن ارتباط شخصی جدید</a>
                @endcan
            </div>
            <div class="card-body pt-2">
                <table class="table table-bordered table-hover mb-2 @if($items->count()) tbl_1 @endif">
                    <thead> 
                        <tr>
                            <th>#</th>
                            <th>نام و نام خانواگی</th>
                            <th>نوع بازار</th>
                            <th>نوع اقدام</th>
                            <th>کاندید</th>
                            <th>وضعیت</th>
                            <th>تاریخ اقدام</th>
                            <th>توضیحات</th>
                            @canany(['connection_edit','connection_delete'])
                                @if(count($items)>0)
                                    <th>عملیات</th>
                                @endif
                            @endcan
                        </tr>
                    </thead>
                    <tbody>
                            @foreach($items as $index=>$item)
                                <tr>
                                    <td>{{$index+1}}</td>
                                    <td>{{$item->name}}</td>
                                    <td>{{$item->store_type}}</td>
                                    <td>{{$item->action_type?$item->action_type:'___'}}</td>
                                    <td>{{$item->candidate?$item->candidate:'___'}}</td>
                                    <td>{{$item->status?$item->status:'___'}}</td>
                                    <td>{{$item->time?$item->time:'___'}}</td>
                                    <td>
                                        <a href="javascript:void(0)" class="popover-dismiss" data-toggle="popover" title="توضیحات"
                                         data-content="{{ $item->description?$item->description:'___' }}">نمایش توضیحات</a>
                                    </td>
                                    @canany(['connection_edit','connection_delete'])
                                        <td class="text-center">
                                            @can('connection_edit')
                                                <a href="{{route('admin.connection-list.edit',$item->id)}}" class="badge bg-primary" title="ویرایش"><i class="fa fa-edit"></i> </a>
                                            @endcan
                                            @can('connection_delete')
                                                <a href="{{route('admin.connection-list.force.delete',$item->id)}}" onclick="confirm('برای حذف مطمئن هستید؟')" class="badge bg-danger mx-1"><i class="fa fa-trash"></i> </a>
                                            @endcan
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

