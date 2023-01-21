@extends('layouts.admin')
@section('css')
@endsection
@section('content')
    <section class="container-fluid">
        <div class="card res_table">
            <div class="card-header h5">
                {{$title1.'های '.$title2}}
            </div>
            <div class="card-body pt-2">
                <table class="table table-bordered table-hover mb-2 @if($items->count()) tbl_1 @endif">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>شناسه</th>
                            <th>نام محصول</th>
                            <th>تعداد</th>
                            {{-- <th>هزینه</th> --}}
                            <th>تاریخ</th>
                            {{-- <th>توضیحات</th> --}}
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($items->count())
                            {{-- @php $index=0; @endphp --}}
                            @foreach($items as $key => $item)
                                {{-- @if ($item->deleted_at===null) --}}
                                    {{-- @php $index+=1; @endphp --}}
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        {{-- <td>{{$index}}</td> --}}
                                        <td>{{$item->id}}</td>
                                        <td>
                                            @if ($item->product()->photo)
                                                <h6 class="float-right pt-4">{{$item->product()->name}}</h6>
                                                <img src="{{url($item->product()->photo->path)}}" class="float-left" style="height: 68px !important" alt="{{$item->product()->name}}">
                                            @else
                                                <h6>{{$item->product()->name}}</h6>
                                            @endif
                                        </td>
                                        <td>{{$item->count}}</td>
                                        {{-- <td>{{$item->total}}</td> --}}
                                        <td>{{$item->time}}</td>
                                        {{-- <td>
                                            <a href="#" class="popover-dismiss" data-toggle="popover" title="توضیحات"
                                            data-content="{{ $item->description?$item->description:'________' }}">نمایش توضیحات آیتم</a>
                                        </td> --}}
                                        <td class="text-center">
                                            <a href="javascript:void(0);" onclick="del_row('{{$item->id}}')" class="badge bg-danger" title="حذف">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                {{-- @endif --}}
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
                    location.href = '{{url('/')}}/admin/user-customer-factor/force/delete/'+id;
                }
            })
        }
    </script>
@endsection
