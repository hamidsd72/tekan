@extends('layouts.admin')
@section('css')
@endsection
@section('content')
    <section class="container-fluid">
        <div class="card res_table">
            <div class="card-header">
                <a href="{{route('admin.potential-list.create')}}" class="btn btn-primary my-2">افزودن پتانسیل</a>
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
                            @if(count($items)>0)
                                <th>عملیات</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                            @foreach($items as $index=>$item)
                                <tr>
                                    <td>{{$index+1}}</td>
                                    <td>{{$item->name}}</td>
                                    <td>{{$item->store_type}}</td>
                                    <td>{{$item->action_type}}</td>
                                    <td>{{$item->candidate?$item->candidate:'___'}}</td>
                                    <td>{{$item->status?$item->status:'___'}}</td>
                                    <td>{{$item->time?$item->time:'___'}}</td>
                                    <td class="text-center">
                                        <a href="{{route('admin.potential-list.edit',$item->id)}}" class="badge bg-primary" title="ویرایش"><i class="fa fa-edit"></i> </a>
                                    </td>
                                </tr>
                            @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection

