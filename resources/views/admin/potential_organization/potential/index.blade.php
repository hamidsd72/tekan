@extends('layouts.admin')
@section('css')
@endsection
@section('content')
    <section class="container-fluid">
        <div class="card res_table">
            <div class="card-header">
                @unless (\Request::route()->getName()=='admin.potential-list.item-show.index')
                    <a href="{{route('admin.potential-list.create')}}" class="btn btn-primary my-2">افزودن پتانسیل</a>
                @endunless
            </div>
            <div class="card-body pt-2"> 
                <table class="table table-bordered table-hover mb-2 @if($items->count()) tbl_1 @endif">
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
                            @if($monthlyPackage) 
                                <th>کاندید طرح {{$monthlyPackage->title}}</th>
                            @endif
                            @if($items->count()) 
                                <th>عملیات</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $index=>$item)
                            <tr>
                                <td>{{$index+1}}</td>
                                <td>{{$item->name?$item->full_name():'__________'}}</td>
                                <td>{{$item->present_ta_peresent?$item->present_ta_peresent:'__________'}}</td>
                                <td>{{$item->kasb_o_kar_kochak_ya_bozorg?$item->kasb_o_kar_kochak_ya_bozorg:'__________'}}</td>
                                <td>{{$item->present_ta_estage?$item->present_ta_estage:'__________'}}</td>
                                <td>{{$item->hadaf_gozari_shakhsi?$item->hadaf_gozari_shakhsi:'__________'}}</td>
                                <td>{{$item->folowe_ya_4eqdam?$item->folowe_ya_4eqdam:'__________'}}</td>
                                <td>{{$item->hadaf_gozari_level?$item->hadaf_gozari_level:'__________'}}</td>
                                <td>{{$item->hadaf_jam_daramad_mah?number_format($item->hadaf_jam_daramad_mah):'__________'}}</td>
                                <td>{{$item->candid_shabakesazi?$item->candid_shabakesazi:'__________'}}</td>
                                <td>{{$item->candid_forosh?$item->candid_forosh:'__________'}}</td>
                                @if($monthlyPackage)
                                    <td>
                                        @if ($item->potential_candid())
                                            @if ($item->potential_candid()->status=='pending')
                                                <div class="float-left">
                                                    <label for="candidActive" class="text-primary">تایید</label>
                                                    <br>
                                                    <input type="checkbox" name="potential_id" id="potential_{{$item->id}}" onclick="updateCandid({{$item->potential_candid()->id}},'active')">
                                                </div>
                                                <div class="float-right">
                                                    <label for="candidBlock" class="text-danger">رد</label>
                                                    <br>
                                                    <input type="checkbox" name="potential_id" id="potential_{{$item->id}}" onclick="updateCandid({{$item->potential_candid()->id}},'block')">
                                                </div>
                                            @else
                                                <span class="text-info">{{$item->potential_candid()->status=='active'?'تایید شده':'رد شده'}}</span>
                                            @endif
                                        @else
                                            <label for="candid" class="text-success">کاندید کردن</label>
                                            <input type="checkbox" name="potential_id" id="potential_{{$item->id}}" onclick="createCandid({{$item->id}})">
                                        @endif
                                    </td>
                                @endif
                                <td class="text-center">
                                    <a href="{{route('admin.potential-list.edit',$item->id)}}" class="badge bg-primary" title="ویرایش"><i class="fa fa-edit"></i> </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection

@section('js')
<script>
    function createCandid(id) {
        location.href = '{{url('/')}}/admin/monthly-package-report/store/'+id;
    }
    function updateCandid(id,status) {
        location.href = '{{url('/')}}/admin/monthly-package-report/update/'+id+'/'+status;
    }
</script>
@endsection

{{--  --}}