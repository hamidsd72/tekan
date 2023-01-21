@extends('layouts.admin',['select_province'=>true])
@section('css')
@endsection
@section('content')
    <section class="container-fluid"">
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">
                {{ Form::open(array('route' => 'admin.category.store', 'method' => 'POST', 'files' => true, 'id' => 'form_req')) }}
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                {{ Form::label('name', '* نام') }}
                                {{ Form::text('name',null, array('class' => 'form-control' , 'required')) }}
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                {{ Form::label('parent_id', ' دسته مادر') }}
                                <select class="form-control select2" id="parent_id" name="parent_id">
                                    <option value="" selected>انتخاب کنید</option>
                                    @if($categories->count())
                                        @foreach($categories as $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
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
