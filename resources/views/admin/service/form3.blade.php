<div class="card card-primary card-outline"> 
    <div class="card-body box-profile">
        <h4 class="mb-4">عریضه نویسی</h4>
        {{ Form::open(array('route' => 'admin.service.store', 'method' => 'POST', 'files' => true)) }}
            <div class="row">
                <input type="hidden" id="service_type" name="service_type" value="عریضه نویسی">

                <div class="col-md-6">
                    <label for="category_id" >* دسته بندی خدمت</label>
                    <select id="category_id" name="category_id" class="form-control select2">
                        @if (auth()->user()->getRoleNames()->first()=="مدیر")
                            @foreach ($items as $item)
                                <option value="{{$item->id}}" @if($items[0]->id == $item->id) selected @endif>{{$item->title}}</option>
                            @endforeach
                        @else
                            @foreach ($items->where('title', auth()->user()->getRoleNames()->first() ) as $item)
                                <option value="{{$item->id}}" @if($items[0]->id == $item->id) selected @endif>{{$item->title}}</option>
                            @endforeach
                        @endif
                    </select>
                    {{-- {{ Form::select('category_id' , Illuminate\Support\Arr::pluck($items,'title','id') , null, array('class' => 'form-control select2')) }} --}}
                </div> 
                {{-- <div class="col-md-6">
                    <div class="form-group">
                        <label for="service_type" >نوع خدمت</label>
                        <select id="service_type" name="service_type" class="form-control">
                            <option value="جلسات عمومی"  selected>(رایگان) جلسات عمومی</option>
                            <option value="مشاوره اختصاصی" >مشاوره اختصاصی</option>
                            <option value="عریضه نویسی" >عریضه نویسی</option>
                        </select>
                    </div>
                </div>  --}}
                <div class="col-md-6">
                    <div class="form-group">
                        {{ Form::label('title', '* نام خدمت') }}
                        {{ Form::text('title',null, array('class' => 'form-control')) }}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {{ Form::label('slug', '* نامک') }}
                        {{ Form::text('slug',null, array('class' => 'form-control')) }}
                    </div>
                </div>
                {{-- <div class="col-lg-3 col-6">
                    <div class="form-group">
                        {{ Form::label('time_start', '* ساعت شروع ') }}
                        {{ Form::dateTimeLocal('time_start',null, array('class' => 'form-control text-left')) }}
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="form-group">
                        {{ Form::label('time_end', '* ساعت پایان ') }}
                        {{ Form::dateTimeLocal('time_end',null, array('class' => 'form-control text-left')) }}
                    </div>
                </div> --}}
                {{-- <div class="col-lg-3 col-6">
                    <div class="form-group">
                        {{ Form::label('limited', '* محدودیت (هر بار برای چند روز)') }}
                        {{ Form::label('limited', '* ظرفیت') }}
                        {{ Form::number('limited',null, array('class' => 'form-control text-left')) }}
                    </div>
                </div> --}}
                {{-- <div class="col-md-6">
                    <div class="form-group">
                        {{ Form::label('time', '* زمان (دقیقه)') }}
                        {{ Form::number('time',null, array('class' => 'form-control text-left')) }}
                    </div>
                </div> --}}
                {{-- <div class="col-md-6">
                    <div class="form-group">
                        {{ Form::label('price', '* هزینه') }}
                        {{ Form::number('price',null, array('class' => 'form-control','onkeyup'=>'number_price(this.value)')) }}
                        <span id="price_span" class="span_p"><span id="pp_price"></span> تومان </span>
                    </div>
                </div> --}}
                <div class="col-md-6">
                    <label for="exampleInputFile">* تصویر(500×500)</label>
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="exampleInputFile" name="photo" accept=".jpeg,.jpg,.png">
                            <label class="custom-file-label" dir="ltr" for="exampleInputFile">انتخاب فایل</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
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
                {{-- <div class="col-md-12">
                    <div class="form-group">
                        {{ Form::label('video_link', '* لینک ویدیو') }}
                        {{ Form::text('video_link',null, array('class' => 'form-control','onkeyup'=>'number_price(this.value)')) }}
                    </div>
                </div> --}}
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

