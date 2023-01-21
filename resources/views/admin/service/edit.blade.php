@extends('layouts.admin')
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
                            {{ Form::model($item,array('route' => array('admin.service.update', $item->id), 'method' => 'POST', 'files' => true)) }}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('category_id', '* دسته بندی خدمت') }}
                                        {{ Form::select('category_id' , Illuminate\Support\Arr::pluck($items,'title','id') , null, array('class' => 'form-control select2')) }}
                                    </div>
                                </div>
                                @if (auth()->user()->getRoleNames()->first()=='مدیر')
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {{ Form::label('user_id', '* نام مشاور') }}
                                            <select id="user_id" name="user_id" class="form-control select2">
                                                @foreach (\App\User::orderByDesc('id')->role('مدرس')->get(['id','first_name','last_name']) as $user)
                                                    <option value="{{$user->id}}" @if($item->user_id==$user->id) selected @endif >{{$user->first_name.' '.$user->last_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            {{ Form::label('info_plus', '* سطح مشاور') }}
                                            <select id="info_plus" name="info_plus" class="form-control select2">
                                                <option value="0" selected>عادی</option>
                                                <option value="1" >ویژه</option>
                                            </select>
                                        </div>
                                    </div> 
                                @endif
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        {{ Form::label('title', '* رشته تحصیلی') }}
                                        {{ Form::text('title',null, array('class' => 'form-control')) }}
                                    </div>
                                </div>
                                {{-- <div class="col-lg-6">
                                    <div class="form-group">
                                        {{ Form::label('slug', '* نامک') }}
                                        {{ Form::text('slug',null, array('class' => 'form-control')) }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('time_start', 'ساعت شروع ') }}
                                        {{ Form::dateTimeLocal('time_start',null, array('class' => 'form-control text-left')) }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('time_end', 'ساعت پایان ') }}
                                        {{ Form::dateTimeLocal('time_end',null, array('class' => 'form-control text-left')) }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('limited', ' ظرفیت') }}
                                        {{ Form::number('limited',null, array('class' => 'form-control text-left')) }}
                                    </div>
                                </div> --}}
                                {{-- <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('time', '* زمان (دقیقه)') }}
                                        {{ Form::number('time',null, array('class' => 'form-control text-left')) }}
                                    </div>
                                </div> --}}
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        {{ Form::label('price', ' هزینه (هر دقیقه)') }}
                                        {{ Form::number('price',null, array('class' => 'form-control','onkeyup'=>'number_price(this.value)')) }}
                                        <span id="price_span" class="span_p"><span id="pp_price"></span> تومان </span>
                                    </div>
                                </div>
                                {{-- <div class="col-md-6">
                                    <label for="exampleInputFile">تصویر(500×500)</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="exampleInputFile" name="photo" accept=".jpeg,.jpg,.png">
                                            <label class="custom-file-label" dir="ltr" for="exampleInputFile">انتخاب فایل</label>
                                        </div>
                                    </div>
                                    @if($item->photo)
                                        <img src="{{url($item->photo->path)}}" class="mt-2" height="100">
                                    @endif
                                </div> --}}

                                <h6 class="col-12">ساعت کاری روزهای شنبه</h6>
                                <div class="col-6">
                                    <div class="form-group">
                                        {{ Form::label('shanbe', '* ساعت شروع ') }}
                                        {{ Form::time('shanbe',null, array('class' => 'form-control text-left','required' => 'required')) }}
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        {{ Form::label('e_shanbe', '* ساعت پایان ') }}
                                        {{ Form::time('e_shanbe',null, array('class' => 'form-control text-left','required' => 'required')) }}
                                    </div>
                                </div>
                                <h6 class="col-12">ساعت کاری روزهای یکشنبه</h6>
                                <div class="col-6">
                                    <div class="form-group">
                                        {{ Form::label('yekshanbe', '* ساعت شروع ') }}
                                        {{ Form::time('yekshanbe',null, array('class' => 'form-control text-left','required' => 'required')) }}
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        {{ Form::label('e_yekshanbe', '* ساعت پایان ') }}
                                        {{ Form::time('e_yekshanbe',null, array('class' => 'form-control text-left','required' => 'required')) }}
                                    </div>
                                </div>
                                <h6 class="col-12">ساعت کاری روزهای دوشنبه</h6>
                                <div class="col-6">
                                    <div class="form-group">
                                        {{ Form::label('doshanbe', '* ساعت شروع ') }}
                                        {{ Form::time('doshanbe',null, array('class' => 'form-control text-left','required' => 'required')) }}
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        {{ Form::label('e_doshanbe', '* ساعت پایان ') }}
                                        {{ Form::time('e_doshanbe',null, array('class' => 'form-control text-left','required' => 'required')) }}
                                    </div>
                                </div>
                                <h6 class="col-12">ساعت کاری روزهای سه شنبه</h6>
                                <div class="col-6">
                                    <div class="form-group">
                                        {{ Form::label('seshanbe', '* ساعت شروع ') }}
                                        {{ Form::time('seshanbe',null, array('class' => 'form-control text-left','required' => 'required')) }}
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        {{ Form::label('e_seshanbe', '* ساعت پایان ') }}
                                        {{ Form::time('e_seshanbe',null, array('class' => 'form-control text-left','required' => 'required')) }}
                                    </div>
                                </div>
                                <h6 class="col-12">ساعت کاری روزهای چهارشنبه</h6>
                                <div class="col-6">
                                    <div class="form-group">
                                        {{ Form::label('chaharshanbe', '* ساعت شروع ') }}
                                        {{ Form::time('chaharshanbe',null, array('class' => 'form-control text-left','required' => 'required')) }}
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        {{ Form::label('e_chaharshanbe', '* ساعت پایان ') }}
                                        {{ Form::time('e_chaharshanbe',null, array('class' => 'form-control text-left','required' => 'required')) }}
                                    </div>
                                </div>
                                <h6 class="col-12">ساعت کاری روزهای پنج شنبه</h6>
                                <div class="col-6">
                                    <div class="form-group">
                                        {{ Form::label('panjshanbe', '* ساعت شروع ') }}
                                        {{ Form::time('panjshanbe',null, array('class' => 'form-control text-left','required' => 'required')) }}
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        {{ Form::label('e_panjshanbe', '* ساعت پایان ') }}
                                        {{ Form::time('e_panjshanbe',null, array('class' => 'form-control text-left','required' => 'required')) }}
                                    </div>
                                </div>
                                <h6 class="col-12">ساعت کاری روزهای جمعه</h6>
                                <div class="col-6">
                                    <div class="form-group">
                                        {{ Form::label('jome', '* ساعت شروع ') }}
                                        {{ Form::time('jome',null, array('class' => 'form-control text-left','required' => 'required')) }}
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        {{ Form::label('e_jome', '* ساعت پایان ') }}
                                        {{ Form::time('e_jome',null, array('class' => 'form-control text-left','required' => 'required')) }}
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        {{ Form::label('text', '* توضیحات') }}
                                        {{ Form::textarea('text',null, array('class' => 'form-control textarea')) }}
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
{{--                                    @if($item->file)--}}
{{--                                        <a href="{{url($item->file->path)}}" class="mt-2" download>دانلود فایل</a>--}}
{{--                                    @endif--}}
{{--                                </div>--}}
{{--                                <div class="col-sm-6 mb-2">--}}
{{--                                    <label for="exampleInputFile">ویدئو mp4(حداکثر 50 مگابایت)</label>--}}
{{--                                    <div class="input-group">--}}
{{--                                        <div class="custom-file">--}}
{{--                                            <input type="file" class="custom-file-input" id="exampleInputFile" name="video" accept=".mp4">--}}
{{--                                            <label class="custom-file-label" dir="ltr" for="exampleInputFile">انتخاب فایل</label>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                    @if($item->video)--}}
{{--                                        <a href="{{url($item->video->path)}}" class="mt-2" target="_blank">نمایش ویدئو</a>--}}
{{--                                    @endif--}}
{{--                                </div>--}}
                                {{-- <div class="col-sm-12">
                                    <div class="form-group">
                                        {{ Form::label('video_link', '* لینک ویدیو') }}
                                        {{ Form::text('video_link',null, array('class' => 'form-control','onkeyup'=>'number_price(this.value)')) }}
                                    </div>
                                </div> --}}

                            </div>
                            <div class="row my-3">
                                <div class="col">
                                    {{ Form::button('ویرایش', array('type' => 'submit', 'class' => 'btn btn-success col-12')) }}
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
