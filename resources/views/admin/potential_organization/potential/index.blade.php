@extends('layouts.admin')
@section('css')
@endsection
@section('content')
    <section class="container-fluid">

        @if ($last_reports->count())
            
            <div class="mb-3 col-md-8 col-lg-6 mx-auto border rounded" id="q0">
                <button type="button" class="mr-2 my-2 h6" onclick="document.querySelector('#q0').classList.add('d-none')"><span aria-hidden="true"><i class="fa fa-close"></i> بستن همه </span></button>
                
                @foreach($last_reports as $index => $report)
                    <div class="text-center p-0 m-0 my-1 bg bg-secondary rounded" id="q1-{{$index}}">
                        <div class="col-12">
                            <div class="row mb-0">
                                <div class="auto">
                                    <button type="button" class="close mr-2 mt-2" onclick="document.querySelector('#q1-{{$index}}').classList.add('d-none')"><span aria-hidden="true"><i class="fa fa-close"></i></span></button>
                                </div>
                                <div class="col">
                                    <h6 class="m-0 p-0 pt-1 text-light">{{ $report->potential->full_name().' - '.my_jdate($report->updated_at,'Y/m/d') }}</h6>
                                    @switch( $report->column_name )
                                        @case( 'hadaf_gozari_shakhsi' ) هدف گذاری شخصی @break
                                        @case( 'hadaf_gozari_level' ) هدف گذاری لول @break
                                        @case( 'candid_shabakesazi' ) کاندید شبکه سازی @break
                                        @case( 'candid_forosh' ) کاندید فروش @break
                                    @endswitch
                                    {{(is_numeric($report->value)?number_format($report->value):$report->value).' '.($report->status=='active'?' تایید شده ':' رد شده ')}}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
        @endif

        <div class="card res_table">
            <div class="card-header">
                @if (\Request::route()->getName()=='admin.potential-list.index')
                    @can('potential_create')
                        <a href="{{route('admin.potential-list.create')}}" class="btn btn-primary my-2">افزودن لیست پتانسیل شخصی</a>
                    @endcan
                @endif
            </div>
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
                            @if (\Request::route()->getName()=='admin.potential-list.index')
                                @if($monthlyPackage->count()) 
                                @foreach ($monthlyPackage as $pack)
                                    <th>کاندید طرح {{$pack->title}}</th>
                                @endforeach
                                @endif
                                @if($items)
                                    @can('potential_edit')
                                        <th>عملیات</th>
                                    @endcan
                                        @can('potential_status')
                                        <th>وضعیت</th>
                                    @endcan
                                @endif
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $index => $item)
                            <tr>
                                <td>{{$index+1}}</td>
                                <td style="{{$item->create_by=='self'?'background: mediumslateblue;color: white':''}}">
                                    {{$item->id.' '.($item->name?$item->full_name():'__________')}}
                                    @if ($item->create_by=='self')<span class="bg-secondary rounded px-1">ثبت نام از پورتال</span>@endif
                                </td>
                                <td>{{$item->present_ta_peresent?$item->present_ta_peresent:'__________'}}</td>
                                <td>{{$item->kasb_o_kar_kochak_ya_bozorg?$item->kasb_o_kar_kochak_ya_bozorg:'__________'}}</td>
                                <td>{{$item->present_ta_estage?$item->present_ta_estage:'__________'}}</td>
                                <td>{{$item->folowe_ya_4eqdam?$item->folowe_ya_4eqdam:'__________'}}</td>
                                <td>
                                    
                                    @foreach ($item->potential_report_findNew('hadaf_gozari_shakhsi') as $row)
                                        <p class="m-0 text-info"> تایید {{$row->value}}</p>
                                    @endforeach
                                        {{-- {{$item->potential_report_find('hadaf_gozari_shakhsi') > 0 ? $item->potential_report_find('hadaf_gozari_shakhsi').' تایید در ماه جاری ' : ''}} --}}
                                    {{$item->hadaf_gozari_shakhsi?number_format($item->hadaf_gozari_shakhsi):'__________'}}
                                    @if ($item->hadaf_gozari_shakhsi)
                                        <br>
                                        @php $hadaf_gozari_shakhsi = $item->potential_report_findOrCreate('hadaf_gozari_shakhsi', $item->hadaf_gozari_shakhsi); @endphp
                                        @if ($hadaf_gozari_shakhsi && $hadaf_gozari_shakhsi->status == 'pending')
                                            <div class="float-left">
                                                <label for="candidActive" class="text-primary">تایید</label>
                                                <br>
                                                <input type="checkbox" name="potential_id" id="potential_{{$item->id}}" onclick="updateReport({{$item->id}},'hadaf_gozari_shakhsi','active')">
                                            </div>
                                            <div class="float-right">
                                                <label for="candidBlock" class="text-danger">رد</label>
                                                <br>
                                                <input type="checkbox" name="potential_id" id="potential_{{$item->id}}" onclick="updateReport({{$item->id}},'hadaf_gozari_shakhsi','deactive')">
                                            </div>
                                        @else
                                            {{$hadaf_gozari_shakhsi->status=='active'?'تایید':'رد شده'}}
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    @foreach ($item->potential_report_findNew('hadaf_gozari_level') as $row)
                                        <p class="m-0 text-info"> تایید {{$row->value}}</p>
                                    @endforeach
                                        {{-- {{$item->potential_report_find('hadaf_gozari_level') > 0 ? $item->potential_report_find('hadaf_gozari_level').' تایید در ماه جاری ' : ''}} --}}
                                    {{$item->hadaf_gozari_level?$item->hadaf_gozari_level:'__________'}}
                                    @if ($item->hadaf_gozari_level)
                                        <br>
                                        @php $hadaf_gozari_level = $item->potential_report_findOrCreate('hadaf_gozari_level', $item->hadaf_gozari_level); @endphp
                                        @if ($hadaf_gozari_level && $hadaf_gozari_level->status == 'pending')
                                            <div class="float-left">
                                                <label for="candidActive" class="text-primary">تایید</label>
                                                <br>
                                                <input type="checkbox" name="potential_id" id="potential_{{$item->id}}" onclick="updateReport({{$item->id}},'hadaf_gozari_level','active')">
                                            </div>
                                            <div class="float-right">
                                                <label for="candidBlock" class="text-danger">رد</label>
                                                <br>
                                                <input type="checkbox" name="potential_id" id="potential_{{$item->id}}" onclick="updateReport({{$item->id}},'hadaf_gozari_level','deactive')">
                                            </div>
                                        @else
                                            {{$hadaf_gozari_level->status=='active'?'تایید':'رد شده'}}
                                        @endif
                                    @endif
                                </td>
                                <td>{{$item->hadaf_jam_daramad_mah?number_format($item->hadaf_jam_daramad_mah):'__________'}}</td>
                                <td>
                                    @foreach ($item->potential_report_findNew('candid_shabakesazi') as $row)
                                        <p class="m-0 text-info"> تایید {{$row->value}}</p>
                                    @endforeach
                                        {{-- {{$item->potential_report_find('candid_shabakesazi') > 0 ? $item->potential_report_find('candid_shabakesazi').' تایید در ماه جاری ' : ''}} --}}
                                    {{$item->candid_shabakesazi?$item->candid_shabakesazi:'__________'}}
                                    @if ($item->candid_shabakesazi)
                                        <br>
                                        @php $candid_shabakesazi = $item->potential_report_findOrCreate('candid_shabakesazi', $item->candid_shabakesazi); @endphp
                                        @if ($candid_shabakesazi && $candid_shabakesazi->status == 'pending')
                                            <div class="float-left">
                                                <label for="candidActive" class="text-primary">تایید</label>
                                                <br>
                                                <input type="checkbox" name="potential_id" id="potential_{{$item->id}}" onclick="updateReport({{$item->id}},'candid_shabakesazi','active')">
                                            </div>
                                            <div class="float-right">
                                                <label for="candidBlock" class="text-danger">رد</label>
                                                <br>
                                                <input type="checkbox" name="potential_id" id="potential_{{$item->id}}" onclick="updateReport({{$item->id}},'candid_shabakesazi','deactive')">
                                            </div>
                                        @else
                                            {{$candid_shabakesazi->status=='active'?'تایید':'رد شده'}}
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    @foreach ($item->potential_report_findNew('candid_forosh') as $row)
                                        <p class="m-0 text-info"> تایید {{$row->value}}</p>
                                    @endforeach
                                        {{-- {{$item->potential_report_find('candid_forosh') > 0 ? $item->potential_report_find('candid_forosh').' تایید در ماه جاری ' : ''}} --}}
                                    {{$item->candid_forosh?$item->candid_forosh:'__________'}}
                                    @if ($item->candid_forosh)
                                        <br>
                                        @php $candid_forosh = $item->potential_report_findOrCreate('candid_forosh', $item->candid_forosh); @endphp
                                        @if ($candid_forosh && $candid_forosh->status == 'pending')
                                            <div class="float-left">
                                                <label for="candidActive" class="text-primary">تایید</label>
                                                <br>
                                                <input type="checkbox" name="potential_id" id="potential_{{$item->id}}" onclick="updateReport({{$item->id}},'candid_forosh','active')">
                                            </div>
                                            <div class="float-right">
                                                <label for="candidBlock" class="text-danger">رد</label>
                                                <br>
                                                <input type="checkbox" name="potential_id" id="potential_{{$item->id}}" onclick="updateReport({{$item->id}},'candid_forosh','deactive')">
                                            </div>
                                        @else
                                            {{$candid_forosh->status=='active'?'تایید':'رد شده'}}
                                        @endif
                                    @endif
                                </td>
                                @if (\Request::route()->getName()=='admin.potential-list.index')
                                    @if($monthlyPackage->count())
                                        @foreach ($monthlyPackage as $pack)
                                            <td>
                                                @php $potential_candid_New = $item->potential_candid_New($pack); @endphp
                                                @if ($potential_candid_New)
                                                    @if ($potential_candid_New->status=='pending')
                                                        <div class="float-left">
                                                            <label for="candidActive" class="text-primary">تایید</label>
                                                            <br>
                                                            <input type="checkbox" name="potential_id" id="potential_{{$item->id}}" onclick="updateCandid({{$potential_candid_New->id}},'active')">
                                                        </div>
                                                        <div class="float-right">
                                                            <label for="candidBlock" class="text-danger">رد</label>
                                                            <br>
                                                            <input type="checkbox" name="potential_id" id="potential_{{$item->id}}" onclick="updateCandid({{$potential_candid_New->id}},'block')">
                                                        </div>
                                                    @else
                                                        <span class="text-info">{{$potential_candid_New->status=='active'?'تایید شده':'رد شده'}}</span>
                                                    @endif
                                                @else
                                                    <label for="candid" class="text-success">کاندید کردن</label>
                                                    <input type="checkbox" name="potential_id" id="potential_{{$item->id}}" onclick="createCandid({{$item->id}},{{$pack->id}})">
                                                @endif
                                            </td>
                                        @endforeach
                                    @endif
                                    @can('potential_edit')
                                        <td class="text-center">
                                            <a href="{{route('admin.potential-list.edit',$item->id)}}" class="badge bg-primary" title="ویرایش"><i class="fa fa-edit"></i> </a>
                                        </td>
                                    @endcan
                                    @can('potential_status')
                                        <td class="text-center">
                                            <a href="{{route('admin.potential-list-user-status-reactivate',$item->id)}}" class="badge bg-{{$item->user->status=='active'?'success':'danger'}}">
                                                {{$item->user->status=='active'?'فعال':'غیرفعال'}}
                                            </a>
                                        </td>
                                    @endcan
                                @endif
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
    function createCandid(id, pack_id) {
        location.href = `{{url('/')}}/admin/monthly-package-report/store/${id}/${pack_id}`;
    }
    function updateCandid(id,status) {
        location.href = `{{url('/')}}/admin/monthly-package-report/update/${id}/${status}`;
    }
    function updateReport(id,column,status) {
        location.href = `{{url('/')}}/admin/potential-report/item/${id}/${column}/${status}`;
    }
</script>
@endsection