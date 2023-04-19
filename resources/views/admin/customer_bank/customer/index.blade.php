@extends('layouts.admin')
@section('css')

@endsection
@section('content')
    <section class="container-fluid">
        <div class="card res_table">
            <div class="card-header">
                @unless (\Request::route()->getName()=='admin.user-customer.custom.index')
                    @can('user_customer_create')
                        <a href="{{route('admin.user-customer.create')}}" class="btn btn-primary my-2">افزودن مشتری جدید</a>
                    @endcan
                @endunless
            </div>
            <div class="card-body pt-2">
                <table class="table table-bordered table-hover mb-2 @if(count($items)) tbl_1 @endif">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>کد مشتری</th>
                            <th>نام و نام خانوادگی</th>
                            <th>تعداد تکرار خرید انجام شده</th>
                            <th>نوع مشتری</th>
                            <th>تعداد ارجاعی گرفته شده</th>
                            <th>تاریخ ثبت</th>
                            <th>توضیحات</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                            @foreach($items as $key => $item)
                                <tr class="{{count($item->referrer_users)>2?'':'bad'}}">
                                    <td>{{$key+1}}</td>
                                    <td>{{'gtedX'.$item->id}}</td>
                                    <td>{{$item->name}}</td>
                                    <td>{{$item->customer_factors->count() + $item->factor_count}}</td>
                                    <td>{{$item->grade(count($item->customer_factors) + $item->factor_count)}}</td>
                                    <td>{{count($item->referrer_users)}}</td>
                                    <td>{{$item->time}}</td>
                                    <td>
                                        <a href="javascript:void(0)" class="popover-dismiss" data-toggle="popover" title="توضیحات"
                                        data-content="{{ $item->description??'___' }}">نمایش توضیحات</a>
                                    </td>
                                    <td class="text-center">
                                        @can('user_customer_factor_list')
                                            <a href="{{route('admin.user-customer-factor.show',$item->id)}}" class="badge bg-info p-1 ml-1">لیست فاکتورها</a>
                                        @endcan
                                        @can('user_customer_factor_create')
                                            <a href="{{route('admin.user-customer-factor.create.factor',$item->id)}}" class="badge bg-success p-1 ml-1">ثبت فاکتور جدید</a>
                                        @endcan
                                        <a href="javascript:void(0)" onclick="ProfileModal(
                                                '{{$item->id}}','{{$item->name}}','{{$item->mobile}}'
                                                ,'{{$item->state?$item->state->name:'__'}}'
                                                ,'{{$item->city?$item->city->name:'__'}}'
                                                ,'{{$item->referrer?$item->referrer->name:'ندارد'}}'
                                                )" class="badge bg-success ml-1" title="پروفایل" data-toggle="modal" data-target="#profile">
                                            <i class="fa fa-user"></i>
                                        </a>
                                        @can('user_customer_edit')
                                            <a href="{{route('admin.user-customer.edit',$item->id)}}" class="badge bg-primary ml-1" title="ویرایش">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        @endcan
                                        @can('user_customer_delete')
                                            <a href="javascript:void(0);" onclick="del_row('{{$item->id}}')" class="badge bg-danger" title="حذف">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </section>
    <div class="modal" id="profile">
        <div class="modal-dialog pt-4">
            <div class="modal-content">
                <div class="modal-header"><h4 id="modalTitle" class="modal-title"></h4></div>
                <div class="modal-body">
                    <p>نام مشتری: <span id="customer_name"></span> </p>
                    <p>موبایل مشتری: <span id="customer_mobile"></span> </p>
                    <p>استان و شهر: <span id="customer_state"></span> </p>
                    <p>معرف: <span id="customer_reffer"></span> </p>
                    <button type="button" class="btn btn-secondary m-0 mx-2" data-dismiss="modal">انصراف</button>
                </div>

            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        function ProfileModal(id ,name ,mobile ,state ,city ,reffer  ) {
            document.getElementById('modalTitle').innerHTML = `<span> پروفایل ${name}</span>`;
            document.getElementById('customer_name').innerHTML = name;
            document.getElementById('customer_mobile').innerHTML = mobile;
            document.getElementById('customer_state').innerHTML = state+' '+city;
            document.getElementById('customer_reffer').innerHTML = reffer;
        }
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
