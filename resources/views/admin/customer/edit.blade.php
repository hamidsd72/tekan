@extends('layouts.admin',['select_province'=>true])
@section('css')
@endsection
@section('content')
    <section class="container">
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">
                {{ Form::model($item,array('route' => array('admin.user-customer.update', $item->id), 'method' => 'PATCH', 'files' => true, 'id' => 'form_req')) }}
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                {{ Form::label('name', '* نام خانوادگی') }}
                                <input type="text" list="usersList" name="name" class="form-control" value="{{$item->name}}" autocomplete="false" required>
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
                        <div class="col-md-6 col-lg">
                            <div class="form-group">
                                {{ Form::label('state_id', '* استان') }}
                                {{ Form::select('state_id' , Illuminate\Support\Arr::pluck($states,'name','id') , null, array('class' => 'form-control select2')) }}
                            </div>
                        </div>
                        <div class="col-md-6 col-lg">
                            <div class="form-group">
                                {{ Form::label('city_id', '* شهر') }}
                                {{ Form::select('city_id' , [] , null, array('class' => 'form-control select2')) }}
                            </div>
                        </div>
                        @if ($item->referrer_id)
                            <div class="col-md-6 col-lg">
                                <div class="form-group">
                                    {{ Form::label('referrer_id', ' معرف') }}
                                    {{ Form::text('referrer_id',('grdf-'.$item->referrer_id), array('class' => 'form-control text-left', 'readonly')) }}
                                    <div id="showNameDetail">{{$item->referrer_user()?$item->referrer_user()->name:'________'}}</div>
                                </div>
                            </div>
                        @endif
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
                    {{ Form::button('ویرایش', array('type' => 'submit', 'class' => 'btn btn-success')) }}
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
    </script>
@endsection
