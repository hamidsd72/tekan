@extends('layouts.admin')
@section('content')
  <section class="container-fluid">
    <div class="card card-primary card-outline">
        <div class="card-body box-profile">
            {{ Form::open(array('route' => 'admin.four_action.store', 'method' => 'POST', 'files' => true , 'id' => 'form_req')) }}

                <div class="row my-1">
                    <div class="col-auto" style="padding-top: 10px;">
                        @if ($step > 1)
                            <a href="{{ route('admin.four_action.custom.create',$step-1) }}" class="text-primary h6"><< بعد</a>
                        @endif
                    </div>
                    <div class="col-auto p-0">
                        <input type="text" name="time" value="{{$time}}" class="form-control text-center date_p1" style="max-width: 128px;" autocomplete="off" readonly required>
                    </div>
                    <div class="col-auto" style="padding-top: 10px;">
                        <a href="{{ route('admin.four_action.custom.create',$step+1) }}" class="text-primary h6">قبل >></a>
                    </div>
                </div>
                
                <div class="py-lg-4 col-md-6 col-lg-3">
                    <a href="{{ route('admin.four_action.users-send-daily-work') }}" class="btn btn-primary col-12 m-0 mb-3">بررسی روزانه لیست پتانسیل</a>
                    <div class="form-group">
                        {{ Form::label('four_action', '۴ اقدام *') }}
                        {{ Form::number('four_action',0, array('class' => 'form-control' ,'required')) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('present', 'پرزنت *') }}
                        {{ Form::number('present',0, array('class' => 'form-control' ,'required')) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('show_gallery', 'شو گالری *') }}
                        {{ Form::number('show_gallery',0, array('class' => 'form-control' ,'required')) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('start_action', '* Start Action') }}
                        {{ Form::number('start_action',0, array('class' => 'form-control' ,'required')) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('workshop_routine', 'روتین کارگاهی *') }}
                        {{ Form::number('workshop_routine',0, array('class' => 'form-control' ,'required')) }}
                    </div>
                </div>
                {{ Form::button('افزودن', array('type' => 'submit', 'class' => 'btn btn-success')) }}
                <a href="{{ URL::previous() }}" class="btn btn-secondary m-0 mx-3">بازگشت</a>

            {{ Form::close() }}
        </div>
    </div>
  </section>
@endsection
@section('js')
@endsection
