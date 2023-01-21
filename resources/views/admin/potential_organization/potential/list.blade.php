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
                            <th>لیست پتانسیل شخصی</th>
                            <th>گزارش پتانسیل شخصی</th>
                            <th>گزارش لیست پتانسیل سازمان</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $index=>$item)
                            <tr>
                                <td>{{$index+1}}</td>
                                <td>{{$item->name?$item->full_name():'__________'}}</td>
                                <td>{{$item->level?$item->level:'__________'}}</td>
                                <td><a href="{{route('admin.potential-list.item-show.index',$item->name)}}" target="_blank">نمایش</a></td>
                                <td><a href="{{route('admin.potential-list.report.list',$item->name)}}" target="_blank">نمایش</a></td>
                                <td><a href="{{route('admin.potential-list.report.list',[$item->name,'all'])}}" target="_blank">نمایش</a></td>
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
