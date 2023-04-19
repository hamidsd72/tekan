@extends('layouts.admin')
@section('content')
  <section class="container-fluid">
    <div class="card card-primary card-outline col-md-10 col-lg-8 mx-auto">
      <div class="card-header box-profile">
        <h5>{{$item->meet?$item->meet->title:'جلسه یافت نشد'}}</h5>
      </div>
      <div class="card-body box-profile">
        {{ Form::label('user_id', 'نام و نام خانوادگی کاربر') }}
        <br>
        {{ $item->fullname() }}
        <br><br>
        {{ Form::label('text', 'توضیحات') }}
        <br>
        {{ $item->text }}
      </div>
    </div>
  </section>
@endsection
@section('js')
@endsection
