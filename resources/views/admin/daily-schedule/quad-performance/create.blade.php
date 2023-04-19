@extends('layouts.admin')
@section('content')
  <section class="container-fluid">
    <div class="card card-primary card-outline">
        <div class="card-body box-profile">
            {{ Form::open(array('route' => 'admin.daily-schedule-quad-performance.store', 'method' => 'POST', 'files' => true , 'id' => 'form_req')) }}

                <div class="row my-1">
                    <div class="col-auto" style="padding-top: 10px;">
                        @if ($step > 1)
                            <a href="{{ route('admin.quad-performance.custom.create',$step-1) }}" class="text-primary h6"><< بعد</a>
                        @endif
                    </div>
                    <div class="col-auto p-0">
                        <input type="text" name="time" value="{{$time}}" class="form-control text-center date_p1" style="max-width: 128px;" autocomplete="off" readonly required>
                    </div>
                    <div class="col-auto" style="padding-top: 10px;">
                        <a href="{{ route('admin.quad-performance.custom.create',$step+1) }}" class="text-primary h6">قبل >></a>
                    </div>
                </div>

                <div class="pt-lg-4 col-md-10 col-lg-6">
                    <textarea name="set_name" id="set_name" rows="2" placeholder="اسامی پیشنهادی" class="form-control"></textarea>
                </div>

                <div class="py-lg-4 col-md-10 col-lg-6">
                    <div class="form-group">
                        {{ Form::label('namesOne', 'گفتگو با محوریت توسعه ارتباطات') }}
                        {{ Form::text('namesOne', null, array('class' => 'form-control key_word', 'list' => 'one', 'autocomplete' => 'false', 'id' => 'txt1')) }}
                        {{-- <datalist id="one">
                            @foreach ($users as $user)
                                <option value="{{$user->name}}">
                            @endforeach
                        </datalist> --}}
                    </div>
                    <div class="form-group">
                        {{ Form::label('namesTwo', 'گفتگو با محوریت فروش یا مشتری مداری') }}
                        {{ Form::text('namesTwo', null, array('class' => 'form-control key_word', 'list' => 'two', 'autocomplete' => 'false', 'id' => 'txt2')) }}
                        {{-- <datalist id="two">
                            @foreach ($users as $user)
                                <option value="{{$user->name}}">
                            @endforeach
                        </datalist> --}}
                    </div>
                    <div class="form-group">
                        {{ Form::label('namesTree', 'گفتگو با محوریت شبکه سازی') }}
                        {{ Form::text('namesTree', null, array('class' => 'form-control key_word', 'list' => 'tree', 'autocomplete' => 'false', 'id' => 'txt3')) }}
                        {{-- <datalist id="tree">
                            @foreach ($users as $user)
                                <option value="{{$user->name}}">
                            @endforeach
                        </datalist> --}}
                    </div>
                    <div class="form-group">
                        {{ Form::label('namesFour', 'گفتگو با محوریت رشد شخصی') }}
                        {{ Form::text('namesFour', null, array('class' => 'form-control key_word', 'list' => 'four', 'autocomplete' => 'false', 'id' => 'txt4')) }}
                        {{-- <datalist id="four">
                            @foreach ($users as $user)
                                <option value="{{$user->name}}">
                            @endforeach
                        </datalist> --}}
                    </div>
                </div>
                {{ Form::button('افزودن', array('type' => 'submit', 'class' => 'btn btn-success mx-3')) }}
                <a href="{{ URL::previous() }}" class="btn btn-secondary m-0">بازگشت</a>
            {{ Form::close() }}
        </div>
    </div>
  </section>
@endsection
@section('js')
<script>
    var users   = @json($users);
    {{-- یافتن لیست نام ها --}}
    let searchByName = str => users.filter((name) => name.startsWith(str))

    function changeUp() {
        var txt1    = document.getElementById('txt1').value;
        var sumLength  = 0;
        if (txt1.length != sumLength) {
            console.log(searchByName('حمید'));
            sumLength = txt1.length;
        }
    }

    document.addEventListener("onclick", myFunction);

    function myFunction() {
        console.log(searchByName('حمید'));
    }
    
</script>
@endsection

{{-- var users   = @json($users);
    var set_name= document.getElementById('set_name').value = users;
    console.log(users, txt1);
    
    document.addEventListener("onkeypress", myFunction);
    
    function myFunction() {
        var set_name= document.getElementById('set_name').value = users;
    } --}}