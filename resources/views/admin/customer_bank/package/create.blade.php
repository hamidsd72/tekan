@extends('layouts.admin',['select_province'=>true])
@section('css')
<style>
    .select2_div {
        width: 100%;
        height: 100px;
        padding: 5px;
        position: relative;
    }
    .select2_div img {
        width: 80px;
        height: 100%;
        object-fit: contain;
        position: absolute;
        left: 0;
        top: 0;
        margin-top: 0;
    }
    .select2_div p {
        width: calc(100% - 85px);
        position: absolute;
        right: 0;
        top: 50%;
        direction: rtl;
        transform: translateY(-50%);
    }
    .select2-selection__rendered .select2_div img {
        display: none!important;
    }
    .select2-selection__rendered .select2_div p {
        width: 100%!important;
        position: unset;
        transform: unset;
        direction: rtl;
        white-space: break-spaces;
    }
</style>
@endsection
@section('content')
    <section class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">
                {{ Form::open(array('route' => 'admin.user-customer-package.store', 'method' => 'POST', 'files' => true, 'id' => 'form_req')) }}
                    <div class="row">
                        <div class="col-md-6 col-lg-6">
                            <div class="form-group">
                                {{ Form::label('name', '* عنوان پکیج') }}
                                {{ Form::text('name',null, array('class' => 'form-control', 'required' )) }}
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6">
                            <div class="form-group">
                                {{ Form::label('category_id', '* دسته بندی محصولات') }}
                                {{ Form::select('category_id' , Illuminate\Support\Arr::pluck($categories,'name','id') , null, array('class' => 'form-control select2')) }}
                            </div>
                        </div>
                        <div id="products_list" class="col-md-8 col-lg-10 d-none">
                            <div class="form-group">
                                {{ Form::label('product_id', '* محصولات') }}
                                {{ Form::select('product_id' , [] , null, array('class' => 'form-control select2', 'onchange' => 'document.getElementById("product_count").classList.remove("d-none");')) }}
                            </div>
                        </div>
                        <div id="product_count" class="col-md-4 col-lg-2 d-none">
                            <div class="form-group">
                                {{ Form::label('count', '* تعداد') }}
                                {{ Form::number('count',1, array('class' => 'form-control', 'required' )) }}
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
        $(document).ready(function () {
            $('select[name=category_id]').on('change', function () {
                $.get("{{url('/')}}/admin/product/cat/filter/ajax/" + $(this).val(), function (data, status) {
                    $('select[name=product_id]').empty();
                    $.each(data, function (key, value) {
                        $('select[name=product_id]').append('<option value="' + value.id + '" data-pic="' + value.pic + '">' + value.name + '</option>');
                    });
                    $('select[name=product_id]').trigger('change');
                });

                document.getElementById("products_list").classList.remove("d-none");

                function custom_template(obj) {
                    var data = $(obj.element).attr('data-pic');
                    var text = $(obj.element).text();
                    if (data) {
                        template = $("<div class='select2_div'><img src=\"" + data + "\"/><p>" + text + "</p></div>");
                        return template;
                    }
                }

                var options = {
                    'templateSelection': custom_template,
                    'templateResult': custom_template,
                }
                $('#product_id').select2(options);

            });
        })
    </script>
@endsection
