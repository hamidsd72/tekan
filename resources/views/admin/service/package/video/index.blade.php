@extends('layouts.admin')
@section('css')

@endsection
@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-12">
                    <div class="card res_table">
                        <div class="card-header">
                            <h3 class="card-title float-right">{{$title2}}</h3>

                            <div class="container mt-5 border rounded p-3">
                                <h5 class="text-right">افزودن</h5>
                                <hr>
                                {{ Form::open(array('route' => array('admin.service.package.video.store',$package->id), 'method' => 'POST','enctype'=>'multipart/form-data')) }}
                                <div class="row">
                                    <div class="col-sm-6 mb-2">
                                        {{ Form::text('title',null, array('class' => 'form-control','placeholder'=>'عنوان ویدئو')) }}
                                    </div>
                                    <div class="col-sm-6 mb-2">
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="exampleInputFile" name="video" accept=".mp4">
                                                <label class="custom-file-label" dir="ltr" for="exampleInputFile">انتخاب فایل mp4 حداکثر 50 مگابایت</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 mb-2">
                                        <select class="form_control select2 w-100" name="type">
                                            <option value="free">رایگان</option>
                                            <option value="sale">خرید پکیج</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        @if($package->type=='learning')
                                            <a href="{{route('admin.service.learn.package.list')}}"
                                               class="btn btn-rounded btn-outline-warning float-right"><i
                                                        class="fa fa-chevron-circle-right ml-1"></i>پکیج های آموزشی</a>
                                        @else
                                            <a href="{{route('admin.service.package.list')}}"
                                               class="btn btn-rounded btn-outline-warning float-right"><i
                                                        class="fa fa-chevron-circle-right ml-1"></i>پکیج</a>
                                        @endif
                                        {{ Form::button('<i class="fa fa-circle-o mtp-1 ml-1"></i>افزودن', array('type' => 'submit', 'class' => 'btn btn-outline-info float-left')) }}
                                    </div>
                                </div>
                                {{ Form::close() }}
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body res_table_in">
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>ویدئو</th>
                                    <th>نوع</th>
                                    <th>ترتیب</th>
                                    <th>وضعیت</th>
                                    <th>عملیات</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($items)>0)
                                    @foreach($items as $key=>$item)

                                        <tr>
                                            <td><a href="{{url($item->path)}}" target="_blank">نمایش</a></td>
                                            <td>@if($item->type=='free') رایگان @elseif($item->type=='sale') خرید @endif</td>
                                            <td width="100">
                                                <form action="{{route('admin.service.package.video.sort',$item->id)}}" method="post">
                                                    @csrf
                                                    <input type="number" class="form-control" value="{{$item->sort}}" name="sort" onchange="this.form.submit()">
                                                </form>
                                            </td>
                                            <td>@item($item->type($item->status))</td>
                                            <td class="text-center">
                                                <a href="javascript:void(0);" onclick="del_row('{{$item->id}}')"
                                                   class="badge bg-danger ml-1" title="حذف"><i class="fa fa-trash"></i>
                                                </a>
                                                @if($item->status=='active')
                                                    <a href="javascript:void(0);"
                                                       onclick="active_row('{{$item->id}}','pending')"
                                                       class="badge bg-success ml-1"
                                                       title=" نمایش فعال است غیرفعال شود؟"><i class="fa fa-check"></i>
                                                    </a>
                                                @endif
                                                @if($item->status=='pending')
                                                    <a href="javascript:void(0);"
                                                       onclick="active_row('{{$item->id}}','active')"
                                                       class="badge bg-warning ml-1"
                                                       title="نمایش غیر فعال است فعال شود؟"><i class="fa fa-close"></i>
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" class="text-center">موردی یافت نشد</td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>

                </div>
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
                    location.href = '{{url('/')}}/admin/service-package-video-active/' + id + '/' + type;
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
                    location.href = '{{url('/')}}/admin/service-package-video-destroy/' + id;
                }
            })
        }

    </script>
@endsection