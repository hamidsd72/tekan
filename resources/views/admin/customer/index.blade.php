@extends('layouts.admin')
@section('css')
<style>
    .table-hover tbody tr.bad {background-color:tomato;}
    table.dataTable tbody tr.bad td {color: white}
    .table-hover tbody tr.bad:hover {background-color:#00000013;}
    .table-hover tbody tr.bad:hover td {color: unset;}
    </style>
@endsection
@section('content')
    <section class="container">
        <div class="card res_table">
            <div class="card-header">
                <a href="{{route('admin.user-customer.create')}}" class="btn btn-primary my-2">افزودن</a>
            </div>
            <div class="card-body pt-2">
                <table class="table table-bordered table-hover mb-2 @if($items->count()) tbl_1 @endif">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>نام و نام خانوادگی</th>
                            <th>تعداد خرید</th>
                            <th>نوع مشتری</th>
                            <th>ارجاعی گرفته</th>
                            <th>پیگیری بعدی</th>
                            <th>پروفایل</th>
                            <th>توضیحات</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($items->count())
                            @foreach($items as $key => $item)
                                <tr class="{{$item->referrer_count()>2?'':'bad'}}">
                                    <td>{{$key+1}}</td>
                                    <td>{{$item->name}}</td>
                                    <td>از رابطه...</td>
                                    <td>{{$item->grade($item->referrer_count())}}</td>
                                    <td>{{$item->referrer_count()}}</td>
                                    <td>{{$item->time}}</td>
                                    <td>{{$item->profile?$item->profile:'هنوز وارد نشده'}}</td>
                                    <td>
                                        <a href="#" class="popover-dismiss" data-toggle="popover" title="توضیحات"
                                        data-content="{{ $item->description?$item->description:'________' }}">نمایش توضیحات آیتم</a>
                                    </td>
                                    <td class="text-center">
                                        <a href="#" class="badge bg-success p-1">ثبت فاکتور</a>
                                        <a href="{{route('admin.user-customer.edit',$item->id)}}" class="badge bg-primary mx-1" title="ویرایش">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="javascript:void(0);" onclick="del_row('{{$item->id}}')" class="badge bg-danger" title="حذف">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="12" class="text-center">موردی یافت نشد</td>
                            </tr>
                        @endif
                </table>
                {{-- @include('admin.customer._table',['items'=>$items,'action'=>true]) --}}
            </div>
        </div>
    </section>
@endsection
@section('js')
    <script>
        function active_row(id,type) {
            if(type=='blocked') {
                var text_user='پنل کاربر مسدود می شود';
            }
            if(type=='active') {
                var text_user='پنل کاربر فعال می شود';
            }
            Swal.fire({
                title: text_user ,
                text: 'برای تغییر وضعیت کاربر مطمئن هستید؟',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    location.href = '{{url('/')}}/admin/user-active/'+id+'/'+type;
                }
            })
        }

        function del_row(id) {
            Swal.fire({
                text: 'برای حذف مطمئن هستید؟',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    location.href = '{{url('/')}}/admin/user-customer/force/delete/'+id;
                }
            })
        }
    </script>
@endsection
