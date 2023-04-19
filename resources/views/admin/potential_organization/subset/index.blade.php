@extends('layouts.admin')
@section('css')
@endsection
@section('content')
    <section class="container-fluid">
        <div class="card res_table">
            <div class="card-header">{{$title2}}</div>
            <div class="card-body pt-2"> 
                <table class="table table-bordered table-hover mb-2 @if($items) tbl_1 @endif">
                    <thead> 
                        <tr>
                            <th>#</th>
                            <th>نام و نام خانوادگی</th>
                            <th>سطح</th>
                            <th>level</th>
                            <th>تعداد مشتریان</th>
                            @can('user_customer_list')
                                <th>مشتریان شخصی</th>
                            @endcan
                            @can('user_customer_report_list')
                                <th>گزارش مشتریان شخصی</th>
                            @endcan
                            @can('user_customer_tree_list')
                                <th>نمودار مشتریان ارجاعی</th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $index=>$item)
                            <tr>
                                <td>{{$index+1}}</td>
                                <td>
                                    @if ($item->user->my_potentials()->count())
                                        <a href="{{route('admin.organization-member.show',$item->name)}}" target="_blank" @if($item->user->status=='deactive') class="text-danger" @endif>{{$item->name?$item->full_name():'__________'}}</a>
                                    @else{{$item->name?$item->full_name():'__________'}}@endif
                                </td>
                                <td>{{$item->level?$item->level:'__________'}}</td>
                                <td>{{$item->user->roles->first()?$item->user->roles->first()->title:'__________'}}</td>
                                <td>{{$item->user?$item->user->customers->count():'__________'}}</td>
                                @can('user_customer_list')
                                    <td><a href="{{route('admin.user-customer.custom.index',$item->name)}}" target="_blank">نمایش</a></td>
                                @endcan
                                @can('user_customer_report_list')
                                    <td><a href="{{route('admin.user-customer-report.custom.index',$item->name)}}" target="_blank">نمایش</a></td>
                                @endcan
                                @can('user_customer_tree_list')
                                    <td><a href="{{route('admin.user-customer-tree.index-page',$item->name)}}" target="_blank">نمایش</a></td>
                                @endcan
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection

@section('js')
@endsection
