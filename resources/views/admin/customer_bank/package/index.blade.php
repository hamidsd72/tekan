@extends('layouts.admin')
@section('css')
@endsection
@section('content')
    <section class="container-fluid">
        <div class="card res_table">
            <div class="card-header">
                <a href="{{route('admin.user-customer-package.create')}}" class="btn btn-primary pt-2">افزودن</a>
                <a href="#" id="openModalBuy" data-toggle="modal" data-target="#buy" class="btn btn-success float-left d-none">فروش یا قرض دادن</a>
            </div>
            <div class="card-body res_table_in">
                <table class="table table-bordered table-hover mb-2 @if($items->count()) tbl_1 @endif">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>عنوان </th>
                            <th>محصول </th>
                            <th>مقدار</th>
                            <th>توضیحات</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($items->count())
                            @foreach($items as $key => $item)
                                <tr>
                                    <td class="pt-4">{{$key+1}}</td>
                                    <td class="pt-4">{{$item->name}}</td>
                                    <td>
                                        @if ($item->product()->photo)
                                            <h6 class="float-right pt-4">{{$item->product()->name}}</h6>
                                            <img src="{{url($item->product()->photo->path)}}" class="float-left" style="height: 68px !important" alt="{{$item->product()->name}}">
                                        @else
                                            <h6>{{$item->product()->name}}</h6>
                                        @endif
                                    </td>
                                    <td class="pt-4">{{($item->count-$item->package_reports()->sum('count')).' مانده از '.$item->count}}</td>
                                    <td class="pt-4">
                                        <a href="#" class="popover-dismiss" data-toggle="popover" title="توضیحات"
                                        data-content="{{ $item->description?$item->description:'________' }}">نمایش توضیحات آیتم</a>
                                    </td">
                                    <td class="text-center pt-4">
                                        <a href="javascript:void(0);" class="badge bg-success p-1"
                                         onclick="packageCreateFactor('{{$item->id}}','{{$item->count-$item->package_reports()->sum('count')}}','{{$item->name}}')" >قرض دادن</a>
                                        <a href="{{route('admin.user-customer-package.show',$item->id)}}" class="badge bg-info p-1 mx-1">جزيیات</a>
                                        <a href="{{route('admin.user-customer-package.edit',$item->id)}}" class="badge bg-primary ml-1" title="ویرایش"><i class="fa fa-edit"></i></a>
                                        <a href="javascript:void(0);" onclick="del_row('{{$item->id}}')" class="badge bg-danger" title="حذف"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="12" class="text-center">موردی یافت نشد</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </section>
  
    <div class="modal" id="buy">
        <div class="modal-dialog pt-4">
            <div class="modal-content">
                <div class="modal-header"><h4 id="modalTitle" class="modal-title"></h4></div>
        
                <div class="modal-body">
                    {{ Form::open(array('route' => 'admin.user-customer-package-report.store', 'method' => 'POST', 'files' => true, 'id' => 'form_req')) }}
                        <div class="row mb-0">
                            {{ Form::hidden('package_id',null, array('class' => 'form-control',  'required', 'id' => 'package_id')) }}
                            <div class="col-md-12 col-lg-12">
                                <div class="form-group">
                                    {{ Form::label('time', 'تاریخ اقدام *') }}
                                    {{ Form::text('time',null, array('class' => 'form-control text-left date_p')) }}
                                </div>
                            </div>
                            <div id="products_list" class="col-md-12 col-lg-12">
                                <div class="form-group">
                                    {{ Form::label('status', '* نوع انقال') }}
                                    <select name="status" id="status" class="form-control select2">
                                        <option value="فروخته">فروخته</option>
                                        <option value="قرض داده">قرض داده</option>
                                    </select>
                                </div>
                            </div>
                            <div id="product_count" class="col-md-12 col-lg-12">
                                <div class="form-group">
                                    {{ Form::label('count', '* تعداد') }}
                                    {{ Form::number('count',null, array('class' => 'form-control', 'max' => 1,  'required')) }}
                                    <p id="maxCount" class="text-info my-1"></p>
                                </div>
                            </div>
                        </div>
                        {{ Form::button('ثبت', array('type' => 'submit', 'class' => 'btn btn-success')) }}
                        <button type="button" class="btn btn-secondary m-0 mx-2" data-dismiss="modal">انصراف</button>
                    {{ Form::close() }}
                </div>
        
            </div>
        </div>
    </div>
    
@endsection
@section('js')
    <script>
        function packageCreateFactor(id , max , name) {
            console.log(id,max,name);
            document.getElementById('openModalBuy').click();
            document.getElementById('package_id').value = id;
            document.getElementById('count').max = max;
            document.getElementById('maxCount').innerHTML = ` حداکثر ${max} عدد `;
            document.getElementById('modalTitle').innerHTML = `<span> پکیج ${name}</span>`;
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
                    location.href = '{{url('/')}}/admin/user-customer-package/force/delete/'+id;
                }
            })
        }
    </script>
@endsection
