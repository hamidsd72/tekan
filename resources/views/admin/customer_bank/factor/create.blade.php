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
                {{ Form::open(array('route' => 'admin.user-customer-factor.store', 'method' => 'POST', 'files' => true, 'id' => 'form_req')) }}
                    <div class="row">
                        <div class="col col-md-6 col-lg-6">
                            <div class="form-group">
                                {{ Form::hidden('customer_id',$item->id, array()) }}
                                {{ Form::hidden('total',1, array('class' => 'total')) }}
                                {{ Form::label('name', '* نام خانوادگی') }}
                                {{ Form::text('name',$item->name, array('class' => 'form-control','readonly')) }}
                            </div>
                        </div>
                        <div class="col-auto col-lg-6">
                            <div class="form-group">
                                {{ Form::label('btn', 'امکانات') }}
                                <div>
                                    <a href="javascript:void(0);" class="btn btn-info" onclick="appendForm()">افزودن محصول جدید</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row border-top pt-3 formRow">
                        <div class="col-md-12 col-lg-12">
                            <div class="form-group">
                                <label for="category_id0">* برند محصولات</label>
                                <select name="category_id0" class="form-control select2">
                                    <option value="">انتخاب کنید</option>
                                    @foreach ($categories as $category)
                                        <option value="{{$category->id}}">{{$category->name}}</option>
                                    @endforeach
                                </select>
                                {{-- {{ Form::select('category_id0' , Illuminate\Support\Arr::pluck($categories,'name','id') , null, array('class' => 'form-control select2')) }} --}}
                            </div>
                        </div>
                        <div id="products_list0" class="col-md-8 col-lg-10 d-none">
                            <div class="form-group">
                                <label for="product_id0">* محصولات</label>
                                <select id="product_id0" name="product_id0" class="form-control select2" onchange="document.getElementById('product_count0').classList.remove('d-none');"></select>
                                {{-- {{ Form::select('product_id0' , [] , null, array('class' => 'form-control select2', 'onchange' => 'document.getElementById("product_count0").classList.remove("d-none");')) }} --}}
                            </div>
                        </div>
                        <div id="product_count0" class="col-md-4 col-lg-2 d-none">
                            <div class="form-group">
                                <label for="count0">* تعداد</label>
                                <input type="number" name="count0" value="1" class="form-control" required>
                            </div>
                        </div>
                        {{-- <div class="col-lg-12">
                            <div class="form-group">
                                {{ Form::label('description', ' توضیحات') }}
                                {{ Form::textarea('description',null, array('class' => 'form-control')) }}
                            </div>
                        </div> --}}
                    </div>

                    {{ Form::button('ثبت فاکتور', array('type' => 'submit', 'class' => 'btn btn-success')) }}
                    <a href="{{ URL::previous() }}" class="btn btn-secondary m-0 mx-3">بازگشت</a>
                {{ Form::close() }}
            </div>
        </div>
    </section>
@endsection
@section('js')
    <script>
        const categoriesList= @json($categories);
        let numberLastForm  = 0;

        function selectInjecter(form_counter) {
            for (let index = 0; index < form_counter; index++) {
                
                $(document).ready(function () {
                    $(`select[name=category_id${index}]`).on('change', function () {
                        $.get("{{url('/')}}/admin/product/cat/filter/ajax/" + $(this).val(), function (data, status) {
                            $(`select[name=product_id${index}]`).empty();
                            $.each(data, function (key, value) {
                                $(`select[name=product_id${index}]`).append(`<option value="${value.id}" data-pic="${value.pic}"> ${value.name} </option>`);
                            });
                            $(`select[name=product_id${index}]`).trigger('change');
                        });
        
                        document.getElementById(`products_list${index}`).classList.remove("d-none");
        
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
                        $(`#product_id${index}`).select2(options);
        
                    });
                })

            }

            $(function () {
                $('.select2').select2()
            });
        }

        function appendForm() {
            numberLastForm += 1;
            let row = document.querySelector('.formRow')
            let endItem = document.createElement('div')
            endItem.setAttribute('class', 'col-12 border-top py-5')
            row.append(endItem)

            // 1 - add input numer --------------------------------------------------------------
            // masterdiv
            let div1 = document.createElement('div')
            div1.setAttribute('class', 'col-12')
            row.append(div1)
            // inside div
            let divForm1 = document.createElement('div')
            divForm1.setAttribute('class', 'form-group')
            div1.append(divForm1)
            // label
            let label1 = document.createElement('label')
            label1.setAttribute('for', `category_id${numberLastForm}`)
            label1.innerHTML = '* دسته بندی و برند محصولات'
            divForm1.append(label1)

            let select2 = document.createElement('select')
            select2.setAttribute('name', `category_id${numberLastForm}`)
            select2.setAttribute('class', 'form-control select2')
            divForm1.append(select2)

            let optionNull = document.createElement('option')
            optionNull.setAttribute('value', '')
            optionNull.innerHTML = 'انتخاب کنید'
            select2.append(optionNull)
            categoriesList.forEach(element => {
                let option = document.createElement('option')
                option.setAttribute('value', element.id)
                option.innerHTML = element.name
                select2.append(option)
            });
            // 1 - end  -------------------------------------------------------------------------

            // 2 - add input numer --------------------------------------------------------------
            // masterdiv
            let div2 = document.createElement('div')
            div2.setAttribute('class', 'col-md-8 col-lg-10 d-none')
            div2.setAttribute('id', `products_list${numberLastForm}`)
            row.append(div2)
            // inside div
            let divForm2 = document.createElement('div')
            divForm2.setAttribute('class', 'form-group')
            div2.append(divForm2)
            // label
            let label2 = document.createElement('label')
            label2.setAttribute('for', `category_id${numberLastForm}`)
            label2.innerHTML = 'محصولات *'
            divForm2.append(label2)

            let select3 = document.createElement('select')
            select3.setAttribute('id', `product_id${numberLastForm}`)
            select3.setAttribute('name', `product_id${numberLastForm}`)
            select3.setAttribute('class', 'form-control select2')
            select3.setAttribute("onchange", `document.getElementById('product_count${numberLastForm}').classList.remove('d-none');`)
            divForm2.append(select3)
            // 2 - end  -------------------------------------------------------------------------
            
            // 3 - add input numer --------------------------------------------------------------
            // masterdiv
            let div = document.createElement('div')
            div.setAttribute('id', `product_count${numberLastForm}`)
            div.setAttribute('class', 'col-md-4 col-lg-2 d-none')
            row.append(div)

            // inside div
            let divForm = document.createElement('div')
            divForm.setAttribute('class', 'form-group')
            div.append(divForm)

            // label
            let label = document.createElement('label')
            label.setAttribute('for', `count${numberLastForm}`)
            label.innerHTML = 'تعداد *'
            divForm.append(label)

            // input
            let input   = document.createElement('input')
            input.setAttribute('name', `count${numberLastForm}`)
            input.setAttribute('class', 'form-control')
            input.setAttribute('value', 1)
            input.setAttribute('type', 'number')
            divForm.append(input)
            // 3 - end  -------------------------------------------------------------------------
            selectInjecter(numberLastForm+1)
            
            document.querySelector('.total').value  = parseInt(numberLastForm+1)
        }

        selectInjecter(1)
    </script>
@endsection
