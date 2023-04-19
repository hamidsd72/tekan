@extends('layouts.auth',['title'=>'حساب کاربری'])
@section('css')
@endsection
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
                    <div class="col-md-6 contents my-auto">
                        <div class="mb-4 text-right">
                            <h3>{{$user_fullname}}</h3>
                            <h6 class="p-0 my-4">حساب شما فعال نیست جهت اطلاعات بیشتر با پشتیبان خود ارتباط بگیرید</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
