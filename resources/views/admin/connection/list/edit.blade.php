@extends('layouts.admin')
@section('content')
    <section class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">
                {{ Form::model( $item , array('route' => array('admin.connection-list.update', $item->id), 'method' => 'PATCH', 'files' => true , 'id' => 'form_req')) }}
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            {{ Form::label('name', 'نام و نام خانوادگی *') }}
                            {{ Form::text('name',null, array('class' => 'form-control' ,'required')) }}
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            {{ Form::label('store_type', 'نوع بازار *') }}
                            {{ Form::select('store_type', ['داغ'=>'داغ','گرم'=>'گرم','سرد'=>'سرد'], null, array('class' => 'form-control select2')) }}
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            {{ Form::label('action_type', 'نوع اقدام') }}
                            <select id="action_type" name="action_type" onchange="changeInput()" class="form-control select2">
                                <option value="" @if($item->action_type=='') selected @endif>___</option>
                                <option value="اقدام به فروش" @if($item->action_type=='اقدام به فروش') selected @endif>اقدام به فروش</option>
                                <option value="اقدام به ورودی" @if($item->action_type=='اقدام به ورودی') selected @endif>اقدام به ورودی</option>
                                {{-- <option value="اقدام به ارجاعی" @if($item->action_type=='اقدام به ارجاعی') selected @endif>اقدام به ارجاعی</option> --}}
                                <option value="توسعه ارتباطات" @if($item->action_type=='توسعه ارتباطات') selected @endif>توسعه ارتباطات</option>
                              </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            {{ Form::label('candidate', 'کاندید') }}
                            {{ Form::select('candidate', [''=>'___','فروش'=>'فروش','ورودی'=>'ورودی','ارجاعی'=>'ارجاعی'], null, array('class' => 'form-control select2')) }}
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            {{ Form::label('status', 'وضعیت') }}
                            {{ Form::select('status', [''=>'___','ختم به فروش'=>'ختم به فروش','ختم به ورودی'=>'ختم به ورودی','ختم به ارجاعی'=>'ختم به ارجاعی','فعلا خیر'=>'فعلا خیر'], null, array('class' => 'form-control select2')) }}
                        </div>
                    </div>
                    <div class="col-lg-6 {{$item->action_type == ''?'d-none':''}}" id="action_time">
                        <div class="form-group">
                            {{ Form::label('time', 'تاریخ اقدام *') }}
                            {{ Form::text('time',null, array('class' => 'form-control text-left date_p1')) }}
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            {{ Form::label('description', 'توضیحات') }}
                            {{ Form::textarea('description',null, array('class' => 'form-control textarea_rtl')) }}
                        </div>
                    </div>
                </div>
                <div class="d-flex">
                    {{ Form::button('ویرایش', array('type' => 'submit', 'class' => 'btn btn-success mx-3')) }}
                    <a href="{{ URL::previous() }}" class="btn btn-secondary">بازگشت</a>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </section>
@endsection
@section('js')
    <script>
        function changeInput() {
            console.log( document.getElementById("action_type").value );
            document.getElementById("action_time").classList.add("d-none");
            if (document.getElementById("action_type").value != '') {
                document.getElementById("action_time").classList.remove("d-none");
            }
        }
    </script>
@endsection
