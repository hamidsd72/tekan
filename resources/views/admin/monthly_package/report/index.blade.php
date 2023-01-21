@extends('layouts.admin')
@section('css')
@endsection
@section('content')
    <section class="container-fluid">
        <div class="card res_table">
            <div class="card-header">{{$title1}}</div>
            <div class="card-body pt-2"> 
                <table class="table table-bordered table-hover mb-2 @if($items) tbl_1 @endif">
                    <thead> 
                        <tr>
                            <th>#</th>
                            <th>نام و نام خانوادگی</th>
                            <th>پرزنت تا پرزنت</th>
                            <th>کسب و کار کوچک یا بزرگ</th>
                            <th>پرزنت تا استیج</th>
                            <th>فالویی یا چهار اقدام</th>
                            <th>هدف گذاری فروش شخصی</th>
                            <th>هدف گذاری لول ماه</th>
                            <th>هدف جمع درآمد</th>
                            <th>کاندید تندیس شبکه سازی</th>
                            <th>کاندیس تندیس فروش</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($items)
                            @foreach($items as $index=>$item)
                                <tr>
                                    <td>{{$index+1}}</td>
                                    <td>{{$item->name?$item->full_name():'__________'}}</td>
                                    <td>{{$item->present_ta_peresent?$item->present_ta_peresent:'__________'}}</td>
                                    <td>{{$item->kasb_o_kar_kochak_ya_bozorg?$item->kasb_o_kar_kochak_ya_bozorg:'__________'}}</td>
                                    <td>{{$item->present_ta_estage?$item->present_ta_estage:'__________'}}</td>
                                    <td>{{$item->hadaf_gozari_shakhsi?$item->hadaf_gozari_shakhsi:'__________'}}</td>
                                    <td>{{$item->folowe_ya_4eqdam?$item->folowe_ya_4eqdam:'__________'}}</td>
                                    <td>{{$item->hadaf_gozari_level?number_format($item->hadaf_gozari_level):'__________'}}</td>
                                    <td>{{$item->hadaf_jam_daramad_mah?$item->hadaf_jam_daramad_mah:'__________'}}</td>
                                    <td>{{$item->candid_shabakesazi?$item->candid_shabakesazi:'__________'}}</td>
                                    <td>{{$item->candid_forosh?$item->candid_forosh:'__________'}}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection

@section('js')
@endsection