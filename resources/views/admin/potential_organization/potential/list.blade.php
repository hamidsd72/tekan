@extends('layouts.admin')
@section('css')
@endsection
@section('content')
  <section class="container-fluid">
    <div class="card res_table">
      <div class="card-header">{{$title2}}</div>
      <div class="card-body pt-2">
        <table class="table table-bordered table-hover mb-2 @if( $items ) tbl_1 @endif">
          <thead>
          <tr>
            <th>#</th>
            <th>نام و نام خانوادگی</th>
            <th>سطح</th>
            @can('potential_list')
              <th>لیست پتانسیل شخصی</th>
            @endcan
            @can('potential_report_list')
              <th>گزارش پتانسیل شخصی</th>
            @endcan
            @can('potential_org_report_list')
              <th>گزارش لیست پتانسیل سازمان</th>
            @endcan
          </tr>
          </thead>
          <tbody>
          @foreach($items as $index=>$item)
            <tr>
              <td>{{$index+1}}</td>
              <td>
                @if ($item->user->my_potentials()->count())
                  <a href="{{route('admin.potential-list.list',$item->name)}}" target="_blank"
                     @if($item->user->status=='deactive') class="text-danger" @endif>{{$item->name?$item->full_name():'__________'}}</a>
                @else{{$item->name?$item->full_name():'__________'}}@endif
              </td>
              <td>{{$item->level?$item->level:'__________'}}</td>
              @can('potential_list')
                <td><a href="{{route('admin.potential-list.item-show.index',$item->name)}}" target="_blank">نمایش</a>
                </td>
              @endcan
              @can('potential_report_list')
                <td><a href="{{route('admin.potential-list.report.list',$item->name)}}" target="_blank">نمایش</a></td>
              @endcan
              @can('potential_org_report_list')
                <td><a href="{{route('admin.potential-list.report.list',[$item->name,'all'])}}"
                       target="_blank">نمایش</a></td>
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
