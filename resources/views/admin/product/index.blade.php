@extends('layouts.admin')
@section('css')

@endsection
@section('content')
    <section class="container-fluid">
        <div class="card res_table">
            <div class="card-header">
                @can('product_create')
                    <a href="{{route('admin.product.create')}}" class="btn btn-primary">افزودن</a>
                @endcan
            </div>
            <div class="card-body res_table_in">
                <table class="table table-bordered table-hover mb-2 @if($items->count()) tbl_1 @endif">
                    <thead>
                        <tr>
                            <th># </th>
                            <th>نام </th>
                            <th>دسته بندی</th>
                            <th>برند</th>
                            @canany(['product_edit','product_delete'])
                                <th>عملیات</th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody>
                        @if($items->count())
                            @foreach($items as $key => $item)
                                <tr>
                                    <td>{{$key}}</td>
                                    <td>{{$item->name}}</td>
                                    <td>{{$item->category ? $item->category->name : 'دسته بندی ندارد'}}</td>
                                    <td>{{$item->brand ? $item->brand->name : 'برند ندارد'}}</td>
                                    @canany(['product_edit','product_delete'])
                                        <td class="text-center">
                                            @can('product_edit')
                                                <a href="{{route('admin.product.edit',$item->id)}}" class="badge bg-primary ml-1" title="ویرایش"><i class="fa fa-edit"></i> </a>
                                            @endcan
                                            {{-- @can('product_delete')
                                                <a href="javascript:void(0);" onclick="del_row('{{$item->id}}')" class="badge bg-danger ml-1" title="حذف"><i class="fa fa-trash"></i> </a>
                                            @endcan --}}
                                        </td>
                                    @endcan
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="text-center">موردی یافت نشد</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
@section('js')
    <script>
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
                    location.href = '{{url('/')}}/admin/product/'+id+'/destroy';
                }
            })
        }
    </script>
@endsection
