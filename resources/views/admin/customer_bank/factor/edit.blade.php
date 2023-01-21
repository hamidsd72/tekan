@extends('layouts.admin',['select_province'=>true])
@section('css')
@endsection
@section('content')
    <section class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">
                {{ Form::open(array('route' => 'admin.user-customer-factor.store', 'method' => 'POST', 'files' => true, 'id' => 'form_req')) }}
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                {{ Form::text('customer_id',$item->id, array('class' => 'form-control')) }}
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                {{ Form::label('name', '* نام خانوادگی') }}
                                {{ Form::text('name',$item->name, array('class' => 'form-control','readonly')) }}
                            </div>
                        </div>
                        {{-- <div class="col-lg-6">
                            <div class="form-group">
                                {{ Form::label('state_id', '* استان') }}
                                {{ Form::select('state_id' , Illuminate\Support\Arr::pluck($states,'name','id') , null, array('class' => 'form-control select2')) }}
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                {{ Form::label('product_id', '* شهر') }}
                                {{ Form::select('product_id' , [] , null, array('class' => 'form-control select2')) }}
                            </div>
                        </div> --}}
                        <div class="col-lg-6">
                            <div class="form-group">
                                {{ Form::label('count', ' تعداد') }}
                                {{ Form::number('count',null, array('class' => 'form-control', 'required' )) }}
                            </div>
                        </div>
                        {{-- <div class="col-lg-12">
                            <div class="form-group">
                                {{ Form::label('description', ' توضیحات') }}
                                {{ Form::textarea('description',null, array('class' => 'form-control')) }}
                            </div>
                        </div> --}}
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
        $(document).ready(function () {
            $('select[name=state_id]').on('change', function () {
                $.get("{{url('/')}}/city-ajax/" + $(this).val(), function (data, status) {
                    $('select[name=product_id]').empty();
                    $.each(data, function (key, value) {
                        $('select[name=product_id]').append('<option value="' + value.id + '">' + value.name + '</option>');
                    });
                    $('select[name=product_id]').trigger('change');
                });
            });
    
            // $.get("{{url('/')}}/city-ajax/" + $('#state_id').val(), function (data, status) {
            //     $('select[name=product_id]').empty();
    
            //     $.each(data, function (key, value) {
            //         $('select[name=product_id]').append('<option value="' + value.id + '">' + value.name + '</option>');
            //     });
            //     $('select[name=product_id]').trigger('change');
            // });
        })

        // function changeInput() {
        //     document.getElementById("referrer_box").classList.add("d-none");
        //     if (document.getElementById("referrer_type").value=='active') {
        //         document.getElementById("referrer_box").classList.remove("d-none");
        //     }
        // }
    </script>
@endsection
