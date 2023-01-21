@extends('layouts.admin',['select_province'=>true])
@section('css')

@endsection
@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-md-12">
                    <!-- Profile Image -->
                    <div class="card card-primary card-outline">
                        <div class="card-body box-profile">
                            {{ Form::open(array('route' => 'admin.potential.store', 'method' => 'POST', 'files' => true)) }}
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        {{ Form::label('name', '* نام') }}
                                        {{ Form::text('name',null, array('class' => 'form-control')) }}
                                    </div>
                                </div>
{{--                                <div class="col-sm-4">--}}
{{--                                    <div class="form-group">--}}
{{--                                        {{ Form::label('slug', '* عنوان (به انگلیسی)') }}--}}
{{--                                        {{ Form::text('slug',null, array('class' => 'form-control')) }}--}}
{{--                                    </div>--}}
{{--                                </div>--}}

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        {{ Form::label('sort', 'اولویت ') }}
                                        {{ Form::text('sort',null, array('class' => 'form-control')) }}
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        {{ Form::label('parent_id', ' دسته مادر') }}
                                        <select class="form-control select2" id="parent_id" name="parent_id">
                                            @if($items->count())
                                                <option value="" selected>انتخاب کنید</option>

                                                @foreach($items as $item)
                                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>

                            </div>
                            <div class="row my-3">
                                <div class="col">
                                    {{ Form::button('ثبت', array('type' => 'submit', 'class' => 'btn btn-success col-12')) }}
                                </div>
                                <div class="col">
                                    <a href="{{ URL::previous() }}" class="btn btn-secondary col-12">بازگشت</a>
                                </div>
                            </div>
                            {{ Form::close() }}
                        </div>
                        <!-- /.card-body -->
                    </div><!-- /.card -->
                </div>
            </div>
        </div>
    </section>

@endsection
