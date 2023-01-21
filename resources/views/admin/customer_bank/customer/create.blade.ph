@extends('layouts.admin',['select_province'=>true])
@section('css')
@endsection
@section('content')
    <section class="container">
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">
                {{ Form::open(array('route' => 'admin.user-customer.store', 'method' => 'POST', 'files' => true, 'id' => 'form_req')) }}
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                {{ Form::label('name', '* نام خانوادگی') }}
                                <input type="text" list="usersList" name="name" class="form-control" autocomplete="false" required>
                                <datalist id="usersList">
                                    @foreach ($users as $user)
                                        <option value="{{$user->name}}">
                                    @endforeach
                                </datalist>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                {{ Form::label('mobile', '* موبایل') }}
                                {{ Form::number('mobile',null, array('class' => 'form-control text-left','required', 'digits')) }}
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                {{ Form::label('state_id', '* استان') }}
                                {{ Form::select('state_id' , Illuminate\Support\Arr::pluck($states,'name','id') , null, array('class' => 'form-control select2')) }}
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                {{ Form::label('city_id', '* شهر') }}
                                {{ Form::select('city_id' , [] , null, array('class' => 'form-control select2')) }}
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                {{ Form::label('referrer_type', 'آشنایی با سامانه') }}
                                <select name="referrer_type" id="referrer_type" onchange="changeInput()" class="form-control">
                                    <option value="deactive" selected>بدون معرف</option>
                                    <option value="active">معرف دارم</option>
                                </select>
                            </div>
                        </div>
                        <div id="referrer_box" class="col-lg-6 d-none">
                            <div class="form-group">
                                {{ Form::label('referrer_id', ' معرف') }}
                                {{ Form::text('referrer_id',null, array('class' => 'form-control', 'onkeyup' => 'findUser(this.value)', 'placeholder' => 'gted-1', 'digits')) }}
                                <div id="showNameDetail"></div>
                                {{-- {{ Form::select('referrer_id' , Illuminate\Support\Arr::pluck($users,'name','id') , null, array('class' => 'form-control select2')) }} --}}
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                {{ Form::label('profile', ' پروفایل') }}
                                {{ Form::text('profile',null, array('class' => 'form-control')) }}
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                {{ Form::label('description', ' توضیحات') }}
                                {{ Form::textarea('description',null, array('class' => 'form-control')) }}
                            </div>
                        </div>
                    </div>
                    {{ Form::button('ثبت', array('type' => 'submit', 'class' => 'btn btn-success')) }}
                    <a href="{{ URL::previous() }}" class="btn btn-secondary m-0 mx-3">بازگشت</a>
                {{ Form::close() }}
            </div>
        </div>
    </section>
@endsection
@section('js')
    <script>
        function findUser(name) {
            if (name.length > 5) {
                var id = parseInt(name.substr(5, 1000));
                var url   = `{{url("/admin/user-customer")}}/${id}`;
                $.ajax({
                    type: "GET",
                    url:  url,
                    success: function(item) {
                        console.log(item);
                        document.getElementById('showNameDetail').innerHTML =  `<p class="${item.class}">${item.name}</p>`;
                    },
                    error: function() {
                        console.log(this.error);
                        if (this.error.name="error") {
                            document.getElementById('showNameDetail').innerHTML =  `<p class="text-danger">یافت نشد</p>`;
                        }
                    }
                });
            }
        }

        function changeInput() {
            document.getElementById("referrer_box").classList.add("d-none");
            if (document.getElementById("referrer_type").value=='active') {
                document.getElementById("referrer_box").classList.remove("d-none");
            }
        }
    </script>
@endsection
