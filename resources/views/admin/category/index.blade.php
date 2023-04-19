@extends('layouts.admin')
@section('css')
@endsection
@section('content')
    <section class="container-fluid">
        <div class="card res_table">
            <div class="card-header">
                @can('product_category_create')
                    <a href="#" data-toggle="modal" data-target="#exampleModalLong" class="btn btn-primary"></i>افزودن</a>
                @endcan
            </div>
            <div class="card-body res_table_in">
                <table class="table table-bordered table-hover mb-2 @if($items->count()) tbl_1 @endif">
                    <thead>
                    <tr>
                        <th>#</th>
                        {{-- <th>دسته مادر</th> --}}
                        <th>نام </th>
                        @canany(['product_category_delete','product_category_edit'])
                            <th>عملیات</th>
                        @endcan
                    </tr>
                    </thead>
                    <tbody>
                        @if($items->count())
                            @foreach($items as $key => $item)
                                <tr>
                                    <td>{{$key}}</td>
                                    {{-- <td>{{$item->parent ? $item->parent->name : 'دسته اصلی'}}</td> --}}
                                    <td>{{$item->name}}</td>
                                    @canany(['product_category_delete','product_category_edit'])
                                        <td class="text-center">
                                            @can('product_category_edit')
                                                <a href="{{route('admin.category.edit',$item->id)}}" class="badge bg-primary ml-1" title="ویرایش"><i class="fa fa-edit"></i> </a>
                                            @endcan
                                            {{-- @can('product_category_delete')
                                                <a href="javascript:void(0);" onclick="del_row('{{$item->id}}')" class="badge bg-danger ml-1" title="حذف"><i class="fa fa-trash"></i> </a>
                                            @endcan --}}
                                        </td>
                                    @endcan
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
      
    <!-- Modal -->
    <div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            {{ Form::open(array('route' => 'admin.category.store', 'method' => 'POST', 'files' => true, 'id' => 'form_req')) }}
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">ایجاد {{$id}} جدید</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                                    
                        <div class="row my-0">
                            {{ Form::hidden('status',$status, array()) }}

                            <div class="form-group">
                                {{ Form::label('name', '* نام') }}
                                {{ Form::text('name',null, array('class' => 'form-control' , 'required')) }}
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary mx-3 my-0" data-dismiss="modal">بستن</button>
                        {{ Form::button('ثبت', array('type' => 'submit', 'class' => 'btn btn-success')) }}
                    </div>
                </div>
            {{ Form::close() }}
        </div>
    </div>

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
                    location.href = '{{url('/')}}/admin/category/'+id+'/destroy';
                }
            })
        }
    </script>
@endsection

