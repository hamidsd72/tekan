@extends('layouts.admin')
@section('css')
@endsection
@section('content')
    <section class="container-fluid">
        <div class="card res_table">
            <div class="card-header">{{$title2}}
            {{isset($following)}}
            </div>
            <div class="card-body pt-2"> 
                <table class="table table-bordered table-hover mb-2 @if($items) tbl_1 @endif">
                    <thead> 
                        <tr>
                            <th>#</th>
                            <th>نام و نام خانوادگی</th>
                            @can('daily_schedule_4_1_list')
                              <th>برنامه روزانه</th>
                            @endcan
                            @can('daily_schedule_4_1_report_list')
                              <th>گزارش ۴×۱ شخصی</th>
                            @endcan
                            @can('daily_schedule_org_report_list')
                              <th>گزارش عملکرد شخصی برای سازمان</th>
                            @endcan
                            @can('user_customer_list')
                              <th>مشتریان شخصی</th>
                            @endcan
                            @can('user_customer_report_list')
                              <th>گزارش مشتریان شخصی</th>
                            @endcan
                            @can('user_customer_org_list')
                              <th>مشتریان سازمان</th>
                            @endcan
                            @can('user_customer_org_report_list')
                              <th>گزارش مشتریان سازمان</th>
                            @endcan
                            @can('potential_list')
                              <th>لیست پتانسیل شخصی</th>
                            @endcan
                            @can('potential_report_list')
                              <th>گزارش لیست پتانسیل شخصی</th>
                            @endcan
                            @can('potential_org_list')
                              <th>لیست پتانسیل سازمان</th>
                            @endcan
                            @can('potential_org_report_list')
                              <th>گزارش لیست پتانسیل سازمان</th>
                            @endcan
                            @can('four_action_report_list')
                              <th>گزارش عملکرد روزانه سازمان</th>
                            @endcan
                            <th>پروفایل شخصی</th>
                            @if (isset($following))
                              <th>افزودن به پتانسیل من</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $index => $item)
                            <tr>
                                <td>{{$index+1}}</td>
                                <td>
                                    {{-- @if ($item->user->my_potentials1()->count()) --}}
                                        <a href="{{route('admin.organization-member.show',$item->name)}}" target="_blank" @if($item->user->status=='deactive') class="text-danger" @endif>{{$item->name?$item->full_name():'__________'}}</a>
                                    {{-- @else{{$item->name?$item->full_name():'__________'}}@endif --}}
                                </td>
                                @can('daily_schedule_4_1_list')
                                  <td><a href="{{route('admin.target.custom.index',$item->name)}}" target="_blank">نمایش</a></td>
                                @endcan
                                @can('daily_schedule_4_1_report_list')
                                  <td><a href="{{route('admin.daily-schedule-report.show',$item->name)}}" target="_blank">نمایش</a></td>
                                @endcan
                                @can('daily_schedule_org_report_list')
                                  <td><a href="{{route('admin.daily-schedule-org-report.show',$item->name)}}" target="_blank">نمایش</a></td>
                                @endcan
                                @can('user_customer_list')
                                  <td><a href="{{route('admin.user-customer.custom.index',$item->name)}}" target="_blank">نمایش</a></td>
                                @endcan
                                @can('user_customer_report_list')
                                  <td><a href="{{route('admin.user-customer-report.custom.index',$item->name)}}" target="_blank">نمایش</a></td>
                                @endcan
                                @can('user_customer_org_list')
                                  <td><a href="{{route('admin.subset.index',$item->name)}}" target="_blank">نمایش</a></td>
                                @endcan
                                @can('user_customer_org_report_list')
                                  <td><a href="{{route('admin.subset.report',$item->name)}}" target="_blank">نمایش</a></td>
                                @endcan
                                @can('potential_list')
                                  <td><a href="{{route('admin.potential-list.item-show.index',$item->name)}}" target="_blank">نمایش</a></td>
                                @endcan
                                @can('potential_report_list')
                                  <td><a href="{{route('admin.potential-list.report.list',$item->name)}}" target="_blank">نمایش</a></td>
                                @endcan
                                @can('potential_org_list')
                                  <td><a href="{{route('admin.potential-list.list',$item->name)}}" target="_blank">نمایش</a></td>
                                @endcan
                                @can('potential_org_report_list')
                                  <td><a href="{{route('admin.potential-list.report.list',[$item->name,'all'])}}" target="_blank">نمایش</a></td>
                                @endcan
                                @can('four_action_report_list')
                                  <td><a href="{{route('admin.four_action.item-show.index',[$item->name,'all'])}}" target="_blank">نمایش</a></td>
                                @endcan
                                <td><a href="#" class="bg-info p-1 px-2 rounded" data-toggle="modal" data-target="#myModal{{$item->id}}">نمایش</a></td>
                                @if (isset($following))
                                  @php $follow = $following->where('potential_id',$item->user_id)->count(); @endphp
                                  <td><a href="{{route('admin.potential-follow',$item->user_id) }}" class="bg-{{$follow ? 'danger' : 'success' }} p-1 px-2 rounded" ><i class="fa fa-user-plus"></i></a></td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    @foreach($items as $item)
        <div class="modal" id="myModal{{$item->id}}">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
            
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">{{$item->name?$item->full_name():' آی دی '.$item->id}}</h4>
                    </div>
            
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="card card-primary card-outline">
                          <div class="card-body box-profile">

                            <div class="row my-0">
                
                              @if ($item->user)
                                <div class="col-lg-6">
                                  <div class="form-group">
                                    {{ Form::label('firstname', 'نام و نام خانوادگی') }}
                                    <input class="form-control " type="text" name="firstname" id="firstname" value="{{$item->user->firstname.' '.$item->user->lastname}}" readonly>
                                  </div>
                                </div>
                  
                                <div class="col-lg-6">
                                  <div class="form-group">
                                    {{ Form::label('mobile', 'موبایل') }}
                                    <input class="form-control " type="text" name="mobile" id="mobile" value="{{$item->user->mobile}}" readonly>
                                  </div>
                                </div>

                                <div class="col-lg-6">
                                  <div class="form-group">
                                    {{ Form::label('state', 'استان') }}
                                    <input class="form-control " type="text" name="state" id="state" value="{{$item->user->state?$item->user->state->name:'ــــــــــ'}}" readonly>
                                  </div>
                                </div>
                  
                                <div class="col-lg-6">
                                  <div class="form-group">
                                    {{ Form::label('city', 'شهر') }}
                                    <input class="form-control " type="text" name="city" id="city" value="{{$item->user->city?$item->user->city->name:'ــــــــــ'}}" readonly>
                                  </div>
                                </div>
                              @endif
                              {{-- <div class="col-lg-6">
                                <hr>
                                <div class="form-group">
                                  {{ Form::label('hadaf_gozari_shakhsi', 'هدف گذاری فروش شخصی') }}
                                  <input class="form-control " type="text" name="hadaf_gozari_shakhsi" id="hadaf_gozari_shakhsi" value="{{$item->hadaf_gozari_shakhsi?$item->hadaf_gozari_shakhsi:'ــــــــــ'}}" readonly>
                                </div>
                              </div>
                              
                              <div class="col-lg-6">
                                <div class="form-group">
                                  {{ Form::label('hadaf_gozari_level', 'هدف گذاری لول ماه') }}
                                  <input class="form-control " type="text" name="hadaf_gozari_level" id="hadaf_gozari_level" value="{{$item->hadaf_gozari_level?$item->hadaf_gozari_level:'ــــــــــ'}}" readonly>
                                </div>
                              </div>
                
                              <div class="col-lg-6">
                                <div class="form-group">
                                  {{ Form::label('kasb_o_kar_kochak_ya_bozorg', 'کسب و کار کوچک یا بزرگ') }}
                                  <input class="form-control " type="text" name="kasb_o_kar_kochak_ya_bozorg" id="kasb_o_kar_kochak_ya_bozorg" value="{{$item->kasb_o_kar_kochak_ya_bozorg?$item->kasb_o_kar_kochak_ya_bozorg:'ــــــــــ'}}" readonly>
                                </div>
                              </div>
                            
                              <div class="col-lg-6">
                                <div class="form-group">
                                  {{ Form::label('folowe_ya_4eqdam', 'فالویی یا چهار اقدام') }}
                                  <input class="form-control " type="text" name="folowe_ya_4eqdam" id="folowe_ya_4eqdam" value="{{$item->folowe_ya_4eqdam?$item->folowe_ya_4eqdam:'ــــــــــ'}}" readonly>
                                </div>
                              </div>
                
                              <div class="col-lg-6">
                                <div class="form-group">
                                  {{ Form::label('candid_shabakesazi', 'کاندید تندیس شبکه سازی') }}
                                  <input class="form-control " type="text" name="candid_shabakesazi" id="candid_shabakesazi" value="{{$item->candid_shabakesazi?$item->candid_shabakesazi:'ــــــــــ'}}" readonly>
                                </div>
                              </div>
                              
                              <div class="col-lg-6">
                                <div class="form-group">
                                  {{ Form::label('candid_forosh', 'کاندیس تندیس فروش') }}
                                  <input class="form-control " type="text" name="candid_forosh" id="candid_forosh" value="{{$item->candid_forosh?$item->candid_forosh:'ــــــــــ'}}" readonly>
                                </div>
                              </div>
                              
                              <div class="col-12">
                                <div class="form-group">
                                  {{ Form::label('hadaf_jam_daramad_mah', 'هدف جمع درآمد') }}
                                  <input class="form-control " type="text" name="hadaf_jam_daramad_mah" id="hadaf_jam_daramad_mah" value="{{$item->hadaf_jam_daramad_mah?$item->hadaf_jam_daramad_mah:'ــــــــــ'}}" readonly>
                                </div>
                              </div>
                            
                            </div>
                            <hr>
                            <div class="row my-0">

                              <div class="col-lg-6" id="present_ta_peresent3">
                                <div class="form-group">
                                  {{ Form::label('present_ta_peresent', 'پرزنت تا پرزنت') }}
                                  <input class="form-control " type="text" name="present_ta_peresent" id="present_ta_peresent" value="{{$item->present_ta_peresent?$item->present_ta_peresent:'ــــــــــ'}}" readonly>
                                </div>
                              </div>
                            
                              <div class="col-lg-6" id="present_ta_estage">
                                <div class="form-group">
                                  {{ Form::label('present_ta_estage', 'پرزنت تا استیج') }}
                                  <input class="form-control " type="text" name="present_ta_estage" id="present_ta_estage" value="{{$item->present_ta_estage?$item->present_ta_estage:'ــــــــــ'}}" readonly>
                                </div>
                              </div> --}}

                            </div>
                            
                          </div>
                        </div>
                    </div>
            
                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                    </div>
            
                </div>
            </div>
        </div>
    @endforeach

@endsection

@section('js')
@endsection

