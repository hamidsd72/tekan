@extends('layouts.admin')
@section('css')
@endsection
@section('content')
  <section class="container-fluid">
    <div class="card res_table">
      <div class="card-header">
        {{-- @can('workshop_create') --}}
          <a href="{{route('admin.workshop.create')}}" class="btn btn-primary my-2">افزودن {{$title1}}</a>
        {{-- @endcan --}}
      </div>
      <div class="card-body pt-2">
        <table class="table table-bordered table-hover mb-2 @if($items->count()) tbl_1 @endif">
          <thead>
          <tr>
            <th>#</th>
            <th>عنوان آموزش</th>
            <th>رول های قابل نمایش</th>
            <th>تعداد ویدیو</th>
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
              <td>{{$item->role}}</td>
              <td>{{$item->videos->count()}}</td>
              <td>
                  {{-- @can('workshop_report') --}}
                    <a class="mt-2" href="{{route('admin.workshop.show',$item->id) }}">ویرایش</a>
                  {{-- @endcan --}}
                  {{-- @can('workshop_delete') --}}
                    <button class="btn btn-danger p-0 px-1 mx-3" data-toggle="modal" data-target="#myModal{{$item->id}}">حذف</button>
                  {{-- @endcan --}}
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
