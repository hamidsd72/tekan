@extends('layouts.admin',['select_province'=>true])
@section('css')
@endsection
@section('content')
    <section class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">
                @if ($item->package_reports()->count())
                    <div class="p-4">
                        <table class="table table-bordered table-hover mb-2 tbl_1">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    {{-- <td> نام مشتری </td> --}}
                                    <td> نوع انتقال </td>
                                    <td> مقدار </td>
                                    <td> تاریخ </td>
                                    {{-- <td> توضیحات </td> --}}
                                    @can('user_customer_package_delete')
                                        <td> عملیات </td>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($item->package_reports() as $key => $report)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        {{-- <td class="font-weight-bold">{{$report->customer()->name}}</td> --}}
                                        <td class="font-weight-bold">{{$report->status}}</td>
                                        <td class="font-weight-bold">{{$report->count}}</td>
                                        <td>{{$report->time}}</td>
                                        {{-- <td>
                                            <a href="#" class="popover-dismiss" data-toggle="popover" title="توضیحات"
                                            data-content="{{ $report->description?$report->description:'________' }}">نمایش توضیحات آیتم</a>
                                        </td> --}}
                                        @can('user_customer_package_delete')
                                            <td class="text-center">
                                                <a href="javascript:void(0);" onclick="del_report_row('{{$report->id}}')" class="badge bg-danger" title="حذف"><i class="fa fa-trash"></i> </a>
                                            </td>
                                        @endcan
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
@section('js')
    <script>
        function del_report_row(id) {
            Swal.fire({
                text: 'برای حذف مطمئن هستید؟',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    location.href = '{{url('/')}}/admin/user-customer-package/report/force/delete/'+id;
                }
            })
        }
    </script>
@endsection
