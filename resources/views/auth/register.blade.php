@extends('layouts.auth',['title'=>'ثبت نام'])
@section('styles')
    <style>
        .form-control {background-color: #edf2f5;}
    </style>
@endsection
@section('content')
    <div class="content">
        <div class="container">
            <div class="row">
            @if($errors->any())
                <div class="col-12 text-right" dir="rtl"">
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
            <div class="col-md-6">
                <img src="{{url('assets/auth/images/register.svg')}}" alt="Image" class="img-fluid">
            </div>
            <div class="col-md-6 contents">
                <div class="row justify-content-center">
                    <div class="col-md-11" dir="rtl">
                        <div class="mb-4 text-right">
                            <h3>ایجاد حساب کاربری</h3>
                            <p class="mb-4">برای ایجاد حساب کاربری فرم زیر را پر کنید</p>
                        </div>
                        <form class="row" action="{{route('register')}}" method="POST">
                            @csrf

                            <div class="col-md-6 px-1  mb-2">
                                <div class="  form-group first  p-1 px-2">
                                    <label for="first_name">نام</label>
                                    <input type="text" class="form-control" onkeypress="runCheckReagentCode()" name="first_name" id="first_name" value="{{old('first_name')}}" autofocus required>
                                </div>
                            </div>

                            <div class="col-md-6 px-1 mb-2">
                                <div class="  form-group first  p-1 px-2">
                                    <label for="last_name">نام خانوادگی</label>
                                    <input type="text" class="form-control" name="last_name" id="last_name" value="{{old('last_name')}}" required>
                                </div>
                            </div>

                            <div class="col-md-6 px-1 mb-2">
                                <div class="form-group p-1 px-2">
                                    <label for="mobile">شماره موبایل</label>
                                    <input type="number" class="form-control"  dir="ltr" name="mobile" id="mobile" value="{{old('mobile')}}" required>
                                </div>
                            </div>

                            <div class="col-md-6 px-1 mb-2">
                                <div class="form-group p-1 px-2">
                                    <label for="whatsapp">شماره موبایل دوم</label>
                                    <input type="number" class="form-control"  dir="ltr" name="whatsapp" id="whatsapp" value="{{old('whatsapp')}}" required>
                                </div>
                            </div>

                            {{-- <div class="col-md-6 px-1 mb-2 ">
                                <div class="form-group p-1 px-2">
                                    <label for="national_code">کدملی</label>
                                    <input type="text" class="form-control"  dir="ltr" name="national_code" id="national_code"
                                           value="{{old('national_code')}}">
                                </div>
                            </div>

                            <div class="col-12 form-group last mb-3 p-1 px-2"  >
                                <label for="hph">کد hph </label>
                                <input type="text" name="hph" class="form-control" dir="ltr" id="hph" value="{{old('hph')}}">
                            </div> --}}

                            <div class="col-md-6 px-1 mb-2 ">
                                <div class="form-group p-1 px-2">
                                    {{ Form::label('state_id', '* استان') }}
                                    {{ Form::select('state_id' , Illuminate\Support\Arr::pluck($states,'name','id') , null, array('class' => 'form-control select2')) }}
                                </div>
                            </div>

                            <div class="col-md-6 px-1 mb-2">
                                <div class="form-group p-1 px-2">
                                    {{ Form::label('city_id', '* شهر') }}
                                    {{ Form::select('city_id' , Illuminate\Support\Arr::pluck($citys,'name','id') , null, array('class' => 'form-control select2')) }}
                                </div>
                            </div>

                            <div class="col-12 form-group last p-1 px-2 mb-2">
                                <label for="password">رمز عبور</label>
                                <input type="password" name="password" class="form-control" id="password" required>
                            </div>

                            <div class="col-12 form-group last p-1 px-2 ">
                                <label for="password_confirmation">تکرار رمز عبور</label>
                                <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" required>
                            </div>


                            {{-- <div class="d-flex mb-3 align-items-center mt-2">
                                <label class="control control--checkbox mb-0">
                                <span class="caption pt-2 mt-3 mr-4">کد معرفی دارم</span>
                                    <input type="checkbox" {{request()->has('referred') || old('check_refer_code') ? ' checked="checked"' : ''}} id="check_refer_code" name="check_refer_code"/>
                                    <div class="control__indicator"></div>
                                </label>
                            </div> --}}


                            <div class="col-12 form-group last my-2 p-1 px-2"  id="refer_code_div">
                                <label for="up_hph">کد hph (بالاسری)</label>
                                <input type="text" name="up_hph" class="form-control" dir="ltr" id="up_hph" onclick="checkReagentCode(this.value)" value="{{ $referred ?? old('up_hph') }}" required>
                            </div>
                            <p id="status" class="m-0 p-0 mb-2"></p>

                            <input id="submit_btn" type="submit" value="ثبت نام" class="btn btn-block btn-primary d-none">

                        </form>

                        <div class="d-flex mt-2 align-items-center">
                            <span class="mx-auto"><a href="{{route('login')}}" class="forgot-pass text-dark fw-bold font-weight-bold">ورود به حساب کاربری</a></span>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
    </div>
@endsection
<script>
    function checkReagentCode(code) {
        if (code.length > 5) {
            let url = `{{url('/')}}/api/v1/check-reagent_code/${code}`;
            $.ajax({
                type: "GET",
                url:  url,
                success: function(dataVal) {
                    let message = dataVal.msg;
                    let classes = dataVal.class;
                    if (classes=='text-success') {
                        document.querySelector('#submit_btn').classList.remove('d-none');
                    }
                    let status  = document.querySelector('#status');
                    status.classList.add(classes);
                    status.innerHTML = message;
                },
                error: function() {
                    console.log(this.error);
                }
            });
        }
    }
    
    function runCheckReagentCode() {
        let clickEvent = new Event('click');
        document.querySelector('#up_hph').dispatchEvent(clickEvent)
    }

</script>
{{-- function toggle_refer_div() {
    if ($('#check_refer_code').is(":checked")){
        $('#refer_code_div').removeClass('d-none');
    }else{
        $('#refer_code_div').addClass('d-none');
        $('#up_hph').val('');
    }
} --}}

{{-- $(document).ready(function(){
    toggle_refer_div();
    $('#check_refer_code').change(()=>{
        toggle_refer_div();
    })
    $('select[name=state_id]').on('change', function () {
        $.get("{{url('/')}}/city-ajax/" + $(this).val(), function (data, status) {
            $('select[name=city_id]').empty();
            $.each(data, function (key, value) {
                $('select[name=city_id]').append('<option value="' + value.id + '">' + value.name + '</option>');
            });
            $('select[name=city_id]').trigger('change');
        });
    });
 }) --}}
@section('scripts')    
    <script type="text/javascript">

        $(document).ready(function(){
            $('select[name=state_id]').on('change', function () {
                $.get("{{url('/')}}/city-ajax/" + $(this).val(), function (data, status) {
                    $('select[name=city_id]').empty();
                    $.each(data, function (key, value) {
                        $('select[name=city_id]').append('<option value="' + value.id + '">' + value.name + '</option>');
                    });
                    $('select[name=city_id]').trigger('change');
                });
            });
        })

        @if(old('city_id'))
            $(document).ready(function () {
                $.get("{{url('/')}}/city-ajax/" + $('#state_id').val(), function (data, status) {
                    $('select[name=city_id]').empty();
                    var old='{{old('city_id')}}';
                    $.each(data, function (key, value) {
                        if(old==value.id)
                        {
                            $('select[name=city_id]').append('<option selected value="' + value.id + '">' + value.name + '</option>');
                        }
                        else
                        {
                            $('select[name=city_id]').append('<option value="' + value.id + '">' + value.name + '</option>');
                        }
                    });
                    $('select[name=city_id]').trigger('change');
                });
            })
        @endif

    </script>
@endsection


