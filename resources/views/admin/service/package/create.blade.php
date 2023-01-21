@extends('layouts.admin')
@section('css')

@endsection
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-body box-profile">
                            {{ Form::open(array('route' => 'admin.service.package.store', 'method' => 'POST', 'files' => true)) }}
                            <div class="row">
                                {{-- <div class="col-sm-12">
                                    <div class="form-group">
                                        {{ Form::label('service', '* خدمت') }}
                                        <select class="form-control select2" name="service[]" multiple>
                                            @foreach($items as $item)
                                                <option value="{{$item->id}}" {{old('service') && in_array($item->id,old('service'))?'selected':''}}>{{$item->title}}({{$item->category?$item->category->title:'_'}})</option>
                                            @endforeach
                                        </select>
                                       {{ Form::select('service[]' , Illuminate\Support\Arr::pluck($items,'title','id') , null, array('class' => 'form-control select2','multiple')) }}
                                    </div>
                                </div> --}}
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        {{ Form::label('title', '* نام پکیج') }}
                                        {{ Form::text('title',null, array('class' => 'form-control')) }}
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        {{ Form::label('slug', '* نامک') }}
                                        {{ Form::text('slug',null, array('class' => 'form-control')) }}
                                    </div>
                                </div>

{{--                                <div class="col-sm-6">--}}
{{--                                    <div class="form-group">--}}
{{--                                        {{ Form::label('limited', '* محدودیت (هر بار برای چند روز)') }}--}}
{{--                                        {{ Form::number('limited',null, array('class' => 'form-control text-left')) }}--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        {{ Form::label('sort_by', 'ترتیب نمایش') }}
                                        {{ Form::number('sort_by',null, array('class' => 'form-control text-left')) }}
                                    </div>
                                </div>
                                {{-- <div class="col-lg-3">
                                    <div class="form-group">
                                        {{ Form::label('custom', 'پکیج ویژه') }}
                                        <input type="checkbox" name="custom" class="form-control">
                                    </div>
                                </div> --}}
{{--                                <div class="col-sm-3">--}}
{{--                                    <div class="form-group">--}}
{{--                                        {{ Form::label('custom_service_count', 'تعداد سرویس های دلخواه') }}--}}
{{--                                        {{ Form::number('custom_service_count',null, array('class' => 'form-control text-left')) }}--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        {{ Form::label('price', '* هزینه') }}
                                        {{ Form::number('price',null, array('class' => 'form-control','onkeyup'=>'number_price(this.value)')) }}
                                        <span id="price_span" class="span_p"><span id="pp_price"></span> تومان </span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {{ Form::label('home_view', 'نمایش در صفحه اصلی') }}
                                        <select class="form-control" name="home_view" id="home_view">
                                            <option value="show">نمایش</option>
                                            <option value="hide" selected>عدم نمایش</option>
                                        </select>
                                        {{-- <input type="checkbox" name="home_view" class="form-control" {{$item->home_view==1?'checked':''}}> --}}
                                    </div>
                                </div>
{{--                                <div class="col-sm-12">--}}
{{--                                    <div class="form-group">--}}
{{--                                        {{ Form::label('home_text', 'توضیحات صفحه اصلی') }}--}}
{{--                                        {{ Form::text('home_text',null, array('class' => 'form-control')) }}--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                                <div class="col-md-4">
                                    <label for="exampleInputFile">* تصویر کارت</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="exampleInputFile" name="pic_card" accept=".jpeg,.jpg,.png">
                                            <label class="custom-file-label" dir="ltr" for="exampleInputFile">انتخاب فایل</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="exampleInputFile">تصویر دوم</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="exampleInputFile" name="photo" accept=".jpeg,.jpg,.png">
                                            <label class="custom-file-label" dir="ltr" for="exampleInputFile">انتخاب فایل</label>
                                        </div> 
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        {{ Form::label('text', '* توضیحات') }}
                                        {{ Form::textarea('text',null, array('class' => 'form-control textarea','onkeyup'=>'number_price(this.value)')) }}
                                    </div>
                                </div>
{{--                                <div class="col-sm-6 mb-2">--}}
{{--                                    <label for="exampleInputFile"> فایل pdf(حداکثر 30 مگابایت)</label>--}}
{{--                                    <div class="input-group">--}}
{{--                                        <div class="custom-file">--}}
{{--                                            <input type="file" class="custom-file-input" id="exampleInputFile" name="file" accept=".pdf">--}}
{{--                                            <label class="custom-file-label" dir="ltr" for="exampleInputFile">انتخاب فایل</label>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="col-sm-6 mb-2">--}}
{{--                                    <label for="exampleInputFile">ویدئو mp4(حداکثر 50 مگابایت)</label>--}}
{{--                                    <div class="input-group">--}}
{{--                                        <div class="custom-file">--}}
{{--                                            <input type="file" class="custom-file-input" id="exampleInputFile" name="video" accept=".mp4">--}}
{{--                                            <label class="custom-file-label" dir="ltr" for="exampleInputFile">انتخاب فایل</label>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                            </div>
                            <div class="row my-3">
                                <div class="col">
                                    {{ Form::button('افزودن', array('type' => 'submit', 'class' => 'btn btn-success col-12')) }}
                                </div>
                                <div class="col">
                                    <a href="{{ URL::previous() }}" class="btn btn-secondary col-12">بازگشت</a>
                                </div>
                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
@section('js')
    <script src="{{ asset('editor/laravel-ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('editor/laravel-ckeditor/adapters/jquery.js') }}"></script>
    <script>
        var textareaOptions = {
            filebrowserImageBrowseUrl: '{{ url('filemanager?type=Images') }}',
            filebrowserImageUploadUrl: '{{ url('filemanager/upload?type=Images&_token=') }}',
            filebrowserBrowseUrl: '{{ url('filemanager?type=Files') }}',
            filebrowserUploadUrl: '{{ url('filemanager/upload?type=Files&_token=') }}',
            language: 'fa'
        };
        $('.textarea').ckeditor(textareaOptions);
        slug('#title', '#slug');

        function number_price(a){
            $('#pp_price').text(a);
            $('#pp_price_1').text(a);
            $('#pp_price').text(function (e, n) {
                var lir1= n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                return lir1;
            })
        }
        $(document).ready(function () {
            var a=$('#price').val();
            $('#pp_price').text(a);
            $('#pp_price_1').text(a);
            $('#pp_price').text(function (e, n) {
                var lir1= n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                return lir1;
            })
        });
    </script>
@endsection
