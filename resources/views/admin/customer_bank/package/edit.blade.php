@extends('layouts.admin',['select_province'=>true])
@section('css')
@endsection
@section('content')
    <section class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">
                {{ Form::model($item,array('route' => array('admin.user-customer-package.update', $item->id), 'method' => 'PATCH', 'files' => true, 'id' => 'form_req')) }}
                    <div class="row">
                        <div class="col-md-12 col-lg-6">
                            <div class="form-group">
                                {{ Form::label('name', '* عنوان پکیج') }}
                                {{ Form::text('name',null, array('class' => 'form-control', 'required' )) }}
                            </div>
                        </div>
                        <div id="products_list" class="col-md-8 col-lg-5">
                            <div class="form-group">
                                {{ Form::label('product_id', '* محصولات') }}
                                {{ Form::text('product_id',$item->product()->name, array('class' => 'form-control', 'readonly' )) }}
                                @if($item->product()&&$item->product()->photo)
                                    <img src="{{url($item->product()->photo->path)}}" height="68">
                                @endif
                            </div>
                        </div>
                        <div id="product_count" class="col-md-4 col-lg-1">
                            <div class="form-group">
                                {{ Form::label('count', '* تعداد') }}
                                {{ Form::number('count',null, array('class' => 'form-control', 'readonly' )) }}
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
                    <a href="{{ route('admin.user-customer-package.index') }}" class="btn btn-secondary m-0 mx-3">بازگشت</a>
                {{ Form::close() }}
            </div>
        </div>
    </section>
@endsection
@section('js')
@endsection
