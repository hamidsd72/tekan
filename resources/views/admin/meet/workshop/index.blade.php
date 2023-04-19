@extends('layouts.admin')
@section('css')
@endsection
@section('content')
  <section class="container-fluid">
    <div class="card res_table">
      <div class="card-header">
        @can('workshop_create')
          <a href="{{route('admin.workshop.create')}}" class="btn btn-primary my-2">افزودن {{$title1}}</a>
        @endcan
      </div>
      <div class="card-body pt-2">
        <table class="table table-bordered table-hover mb-2 @if($items->count()) tbl_1 @endif">
          <thead>
          <tr>
            <th>#</th>
            <th>موضوع جلسه</th>
            <th>آدرس جلسه</th>
            <th>اطلاعات جلسه</th>
            <th>زمان برگذاری جلسه بعدی</th>
            @if($items->count())
              <th>عملیات</th>
            @endif
          </tr>
          </thead>
          <tbody>
          @foreach($items as $index=>$item)
            <tr>
              <td>{{$index+1}}</td>
              <td>{{$item->title}}</td>
              <td>{{url('/admin/meet/workshop-report').'/'.$item->slug}}</td>
              <td>{{$item->reply>1?($item->reply).' جلسه هر '.($item->addDays).' روز یکبار ':'بدون تکرار جلسات'}}</td>
              <td class="text-center">
                {{my_jdate($item->ready_date,'d F Y')}}
                @if ($item->activate)
                  <form action="{{route('admin.workshop.update',$item->id)}}" method="post" class="d-flex mr-2"
                        style="max-width: 200px">
                    @csrf
                    @method('PATCH')
                    <input type="text" name="date" id="date" value="{{num2fa(my_jdate($item->ready_date,'Y/m/d'))}}"
                           class="form-control date_p1" style="min-width: 80px;max-width: 120px">
                    <button type="submit" class="btn btn-sm">تغییر تاریخ</button>
                  </form>
                @endif
              </td>
              <td>
                <div class="d-flex">
                  @can('workshop_report')
                    <a class="mt-2 "
                       href="{{route('admin.workshop.show',$item->slug) }}">گزارشات {{num2fa($item->reports->count())}}</a>
                  @endcan
                  @can('workshop_dis')
                    <a class="mt-2 mx-2"
                       href="{{route('admin.workshop-description.show',$item->id)}}">توضیحات {{num2fa($item->descriptions->count())}}</a>
                  @endcan
                  @can('workshop_delete')
                    <button class="btn btn-danger p-0 px-1" data-toggle="modal" data-target="#myModal{{$item->id}}">
                      حذف
                    </button>
                  @endcan
                </div>
              </td>
            </tr>
          @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </section>

  @foreach($items as $item)
    <div class="modal" id="myModal{{$item->id}}">
      <div class="modal-dialog">
        <form action="{{route('admin.workshop.destroy',$item->id)}}" method="post">
          @csrf
          @method('DELETE')
          <div class="modal-content">

            <div class="modal-header">
              <h4 class="modal-title">آیتم حذف شود!</h4>
            </div>

            <div class="modal-body">
              <h4>حذف جلسه : {{$item->title}}</h4>
              <p class="text-danger p-0 m-0">این عملیات بدون بازگشت است!</p>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary my-0 mx-3" data-dismiss="modal">انصراف</button>
              <button type="submit" class="btn btn-danger my-0">حذف جلسه</button>
            </div>

          </div>
        </form>
      </div>
    </div>
  @endforeach

@endsection

@section('js')
@endsection
