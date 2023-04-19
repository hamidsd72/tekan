@extends('layouts.admin')
@section('content')
  <section class="container-fluid">
    <div class="card card-primary card-outline">
        <div class="card-body box-profile">
            {{ Form::open(array('route' => 'admin.daily-schedule-org-performance.store', 'method' => 'POST', 'files' => true , 'id' => 'form_req')) }}

                <div class="row my-1">
                    <div class="col-auto" style="padding-top: 10px;">
                        @if ($step > 1)
                            <a href="{{ route('admin.org-performance.custom.create',$step-1) }}" class="text-primary h6"><< بعد</a>
                        @endif
                    </div>
                    <div class="col-auto p-0">
                        <input type="text" name="time" value="{{$time}}" class="form-control text-center date_p1" style="max-width: 128px;" autocomplete="off" readonly required>
                    </div>
                    <div class="col-auto" style="padding-top: 10px;">
                        <a href="{{ route('admin.org-performance.custom.create',$step+1) }}" class="text-primary h6">قبل >></a>
                    </div>
                </div>

                <div class="py-lg-4 col-md-10 col-lg-6">

                    @foreach ($labels as $label)
                      @if(auth()->user()->roles()->first() && in_array(auth()->user()->roles()->first()->name,explode(',',$label->role)) || auth()->user()->roles()->first() && in_array(auth()->user()->roles()->first()->name,['admin','developer']))
                        <div class="form-group">
                            {{ Form::label( $label->id, $label->label) }}
                            {{ Form::text( $label->id, null, array('class' => 'form-control key_word', 'list' => $label->label, 'autocomplete' => 'false')) }}
                            <datalist id="{{$label->label}}">
                                @foreach ($users as $user)
                                    <option value="{{$user->name}}">
                                @endforeach
                            </datalist>
                        </div>
                      @endif
                    @endforeach
                    
                </div>
                {{ Form::button('افزودن', array('type' => 'submit', 'class' => 'btn btn-success mx-3')) }}
                <a href="{{ URL::previous() }}" class="btn btn-secondary m-0">بازگشت</a>
            {{ Form::close() }}
        </div>
    </div>
  </section>
@endsection
@section('js')
@endsection
