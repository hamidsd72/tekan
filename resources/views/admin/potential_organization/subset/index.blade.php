@extends('layouts.admin')
@section('css')
@endsection
@section('content')
    <section class="container-fluid">
        <div class="card res_table">
            <div class="card-header">{{$title2}}</div>
            <div class="card-body pt-2"> 
                <table class="table table-bordered table-hover mb-2 @if($items->count()) tbl_1 @endif">
                    <thead> 
                        <tr>
                            <th>#</th>
                            <th>نام و نام خانوادگی</th>
                            <th>سطح</th>
                            <th>level</th>
                            <th>تعداد مشتریان</th>
                            <th>مشتریان شخصی</th>
                            <th>گزارش مشتریان شخصی</th>
                            <th>نمودار مشتریان ارجاعی</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $index=>$item)
                            <tr>
                                <td>{{$index+1}}</td>
                                <td>{{$item->name?$item->full_name():'__________'}}</td>
                                <td>{{$item->level?$item->level:'__________'}}</td>
                                <td>__________</td>
                                <td>{{$item->user?$item->user->customers->count():'__________'}}</td>
                                <td><a href="{{route('admin.user-customer.custom.index',$item->name)}}" target="_blank">نمایش</a></td>
                                <td><a href="{{route('admin.user-customer-report.custom.index',$item->name)}}" target="_blank">نمایش</a></td>
                                <td><a href="{{route('admin.user-customer-tree.index-page',$item->name)}}" target="_blank">نمایش</a></td>
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
