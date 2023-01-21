@extends('layouts.admin')
@section('css')

@endsection
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card res_table">
                        <div class="card-header pb-0">
                            <h3 class="card-title mb-2">{{$title2}}</h3>
                            @if(auth()->user()->getRoleNames()->first()=='مدیر')
                                <a href="{{route('admin.notification.create')}}" class="float-left btn btn-info mr-2"><i class="fa fa-circle-o mtp-1 ml-1"></i>ارسال اعلان</a>
{{--                                {{ Form::open(array('route' => array('admin.notification.update',1), 'method' => 'PATCH')) }}--}}
{{--                                    <div class="form-group d-flex">--}}
{{--                                        {{Form::text('user_mobile', null, array('class' => 'form-control col-lg-4','required', 'placeholder' => 'جستجو اعلانات با شماره موبایل'))}}--}}
{{--                                        {{ Form::button('<i class="fa fa-circle-o"></i>یافتن', array('type' => 'submit', 'class' => 'btn btn-danger mx-lg-3')) }}--}}
{{--                                    </div>--}}
{{--                                {{ Form::close() }}--}}
                            @endif
                        </div>
                        <div class="card-body res_table_in">
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>ردیف</th>
                                        @if(auth()->user()->hasRole('مدیر'))
                                            <th>ایجاد کننده</th>
                                        @endif
                                        <th>عنوان</th>
                                        <th>وضعیت</th>
                                        <th>برای</th>
                                        <th>عملیات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @if($items->count())
                                    @foreach($items as $key=>$item)
                                        <tr>

                                            <td>{{$key + 1 }}</td>

                                            @if( auth()->user()->hasRole('مدیر'))
                                            <td>  {{$item->creator   ? $item->creator->full_name : '-' }}  </td>
                                            @endif

                                            <td>{{$item->subject }} </td>
                                            <td>
                                                  <span class="badge {{$item->status=='active'?'badge-success':'badge-info'}}">
                                                    {{$item->status=='active'?'بررسی شده':'خوانده نشده'}}
                                                </span>
                                            </td>

                                            <td>
                                             <span>{{$item->user_id ?? '' }}</span>
                                            </td>

                                            <td class="text-center">
                                                <a href="{{route('admin.notification.show',$item->id)}}" class="btn btn-primary py-0 ">نمایش</a>
                                                @if(auth()->user()->hasRole('مدیر'))
                                                    <a href="javascript:void(0);" onclick="del_row('{{$item->id}}')" class="btn bg-danger py-0 mx-2" title="حذف"><i class="fa fa-trash"></i></a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6" class="text-center">موردی یافت نشد</td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                    <div class="pag_ul">
                        {{ $items->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('js')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script>
        $(function(){
            $("input[name='user_mobile']").on('input', function (e) {
                $(this).val($(this).val().replace(/[^0-9]/g, ''));
            });
        });
    </script>
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
                location.href = `{{url('/')}}/admin/notification/${id}/destroy`;
            }
        })
    }
</script>
@endsection
