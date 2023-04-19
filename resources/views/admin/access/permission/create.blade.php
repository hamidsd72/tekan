@extends('layouts.admin')
@section('content')
  <section class="container-fluid">
    <div class="card card-primary card-outline">
      <div class="card-body box-profile">
        {{ Form::open(array('route' => 'admin.permission.store', 'method' => 'POST', 'files' => true , 'id' => 'form_req')) }}
          <div class="row">
            <div class="col-lg-6">
              <div class="form-group">
                {{Form::label('category_id', 'دسته بندی(جدول) *')}}
                <select name="category_id"
                        class="form-control select2-show-search select_prefix custom-select select2"
                        data-placeholder="انتخاب کنید" required>
                  <option value="">انتخاب کنید</option>
                  @foreach($cats as $cat)
                    <option value="{{$cat->id}}"
                            {{old('category_id')==$cat->id?'selected':''}} data-prefix="{{$cat->access_code}}">{{$cat->table_name}}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-lg-12"></div>
            <div class="col-lg-6">
              <div class="form-group">
                {{Form::label('title', 'عنوان دسترسی *')}}
                {{Form::text('title', null, array('class' => 'form-control','required'))}}
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                {{Form::label('name', 'کد دسترسی *')}}
                {{Form::text('name', null, array('class' => 'form-control prefix_input d-ltr text-left','required'))}}
                <span class="prefix_span"></span>
              </div>
            </div>
          </div>
          <div class="d-flex">
            {{ Form::button('افزودن', array('type' => 'submit', 'class' => 'btn btn-success mx-3')) }}
            <a href="{{ URL::previous() }}" class="btn btn-secondary">بازگشت</a>
          </div>
        {{ Form::close() }}
      </div>
    </div>
  </section>
@endsection
@section('js')
  @if(old('categroy_id'))
    <script>
        $(document).ready(function () {
            var prefix=$('.select_prefix').find(':selected').attr('data-prefix')
            $('.prefix_span').text(prefix+'_')
            var w_prefix=$('.prefix_span').outerWidth();
            $('.prefix_input').css('padding-left',w_prefix+'px')
        })
    </script>
  @endif
@endsection
