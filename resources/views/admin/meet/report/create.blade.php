@extends('layouts.admin')
@section('content')
  <section class="container-fluid">
    <div class="card card-primary card-outline col-md-10 col-lg-8 mx-auto">
      <div class="card-header box-profile">
        <h5>
            {{$item->title}}
        </h5>
      </div>
      <div class="card-body box-profile">
        {{ Form::open(array('route' => 'admin.workshop-report.store', 'method' => 'POST', 'files' => true , 'id' => 'form_req')) }}
            <div class="row">

                {{ Form::hidden('meet_id',$item->id, array('')) }}

                <p class="m-0 px-2 pb-2"> مشخصات کاربر : {{auth()->user()->first_name.' '.auth()->user()->last_name}}</p>

                <div class="col-lg-12">
                <div class="form-group">
                    {{ Form::label('text', 'توضیحات') }}
                    {{ Form::textarea('text',null, array('class' => 'form-control textarea_rtl')) }}
                </div>
                </div>

            </div>
            {{ Form::button('ثبت گزارش', array('type' => 'submit', 'class' => 'btn btn-success')) }}
        {{ Form::close() }}
      </div>
    </div>
  </section>
@endsection
@section('js')
@endsection
