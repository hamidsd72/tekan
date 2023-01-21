@extends('layouts.admin')
@section('content')
    <section class="container">
        <div class="card res_table">
            <div class="card-header">
                <a href="{{route('admin.customer.create')}}" class="btn btn-primary my-2">افزودن</a>
            </div>
            <div class="card-body pt-2">
                <table class="table table-bordered table-hover mb-2 @if($items->count()) tbl_1 @endif">
                    <thead>
                        <tr>
                            <th>عنوان</th>
                            <th>تصویر</th>
                            @if(count($items)>0)
                                <th>عملیات</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($items)>0)
                            @foreach($items as $item)
                                <tr>
                                    <td class="vertical-align-middle">@item($item->title)</td>
                                    <td class="vertical-align-middle text-center"><img src="{{url($item->photo->path)}}" height="150"></td>
                                    <td class="text-center vertical-align-middle">
                                        <a href="{{route('admin.customer.edit',$item->id)}}" class="badge bg-primary mx-1" title="ویرایش"><i class="fa fa-edit"></i> </a>
                                        <a href="javascript:void(0);" onclick="del_row('{{$item->id}}')" class="badge bg-danger" title="حذف"><i class="fa fa-trash"></i> </a>

                                        @if($item->status=='active')
                                            <a href="javascript:void(0);" onclick="active_row('{{$item->id}}','pending')" class="badge bg-success ml-1"
                                                 title=" نمایش فعال است غیرفعال شود؟"><i class="fa fa-check"></i></a>
                                        @elseif($item->status=='pending')
                                            <a href="javascript:void(0);" onclick="active_row('{{$item->id}}','active')" class="badge bg-warning ml-1"
                                                 title="نمایش غیر فعال است فعال شود؟"><i class="fa fa-close"></i></a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="12" class="text-center">موردی یافت نشد</td>
                            </tr>
                        @endif
                </table>
            </div>
        </div>
    </section>
@endsection
@section('js')
<script>
    function active_row(id, type) {
        if (type == 'pending') {
            var text_user = ' نمایش غیرفعال می شود';
        }
        if (type == 'active') {
            var text_user = ' نمایش فعال می شود';
        }
        Swal.fire({
            title: text_user,
            text: 'برای تغییر وضعیت مطمئن هستید؟',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {
                location.href = '{{url('/')}}/admin/customer-active/' + id + '/' + type;
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
                location.href = '{{url('/')}}/admin/customer-destroy/'+id;
            }
        })
    }
</script>
@endsection