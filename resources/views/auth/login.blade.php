@extends('layouts.auth',['title'=>'ورود به پنل مدیریت'])
@section('content')
    <div class="col-12">
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
                    @include('includes._messages')
                    <div class="col-md-6">
                        <img src="{{url('assets/auth/images/undraw_remotely_2j6y.svg')}}" alt="Image" class="img-fluid">
                    </div>
                    <div class="col-md-6 contents">
                        <div class="row justify-content-center">
                            <div class="col-md-8" dir="rtl">
                                <div class="mb-4 text-right">
                                    <h3>ورود به حساب کاربری</h3>
                                    <p class="mb-4">برای ورود به حساب شماره موبایل و رمزعبور را وارد کنید</p>
                                </div>
                                <form action="{{route('login')}}" method="POST">
                                    @csrf
                                    <div class="form-group first mb-2 px-2 py-1">
                                        <label for="national_code">شماره موبایل</label>
                                        <input type="number" class="form-control" dir="ltr" name="national_code" id="national_code" value="{{old('national_code')}}">
                                    </div>
    
                                    <div class="form-group last  px-2 py-1">
                                        <label for="password">رمز عبور</label>
                                        <input type="password" name="password" class="form-control" id="password">
                                    </div>
    
                                    <div class="d-flex mb-3 align-items-center">
                                        <label class="control control--checkbox mb-0">
                                            <input type="checkbox" name="remember_me" checked="checked"/>
                                            <div class="control__indicator"></div>
                                        </label>
                                        <span class="caption mt-2 mr-2 pt-2">به خاطر سپردن</span>
                                    </div>
    
                                    <input type="submit" value="ورود" class="btn btn-block btn-primary">
                                </form>
    
                                <div class="d-flex mt-2 align-items-center">
                                    <span class="mx-auto"><a href="{{route('register')}}" class="forgot-pass text-dark fw-bold font-weight-bold">ثبت نام</a></span>
                                </div>
                                
                                {{-- <div class="d-flex mb-5 align-items-center">
                                    <span class="ml-auto"><a href="#" class="forgot-pass">فراموشی پسورد</a></span>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
