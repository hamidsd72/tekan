@extends('layouts.admin',['select_province'=>true])
@section('css')
@endsection
@section('content')
    <section class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">
                {{ Form::model($item,array('route' => ['admin.product.update',$item->id], 'method' => 'PATCH', 'files' => true , 'id' => 'form_req')) }}
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                {{ Form::label('category_id', ' دسته بندی') }}
                                <select class="form-control select2" id="category_id" name="category_id">
                                    <option value="" selected>انتخاب کنید</option>
                                    @foreach($categories->where('status','category') as $category)
                                        <option value="{{$category->id}}" {{$item->category_id == $category->id ? 'selected' : ''}}>{{$category->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                {{ Form::label('brand_id', ' برند') }}
                                <select class="form-control select2" id="brand_id" name="brand_id">
                                    <option value="" selected>انتخاب کنید</option>
                                    @foreach($categories->where('status','brand') as $brand)
                                        <option value="{{$brand->id}}" {{$item->brand_id == $brand->id ? 'selected' : ''}}>{{$brand->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                {{ Form::label('name', '* نام') }}
                                {{ Form::text('name',null, array('class' => 'form-control' , 'required')) }}
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <label for="exampleInputFile">تصویر </label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="exampleInputFile" name="photo" accept=".jpeg,.jpg,.png">
                                    <label class="custom-file-label" dir="ltr" for="exampleInputFile">انتخاب فایل</label>
                                </div>
                            </div>
                            @if($item->photo)
                                <img src="{{url($item->photo->path)}}" class="mt-4" height="100">
                            @endif
                        </div>
                    </div>
                    {{ Form::button('ویرایش', array('type' => 'submit', 'class' => 'btn btn-success')) }}
                    <a href="{{ URL::previous() }}" class="btn btn-secondary m-0 mx-3">بازگشت</a>
                {{ Form::close() }}
            </div>
        </div>
    </section>
@endsection
