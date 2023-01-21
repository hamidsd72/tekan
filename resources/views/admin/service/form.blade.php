<div class="card card-primary card-outline"> 
    <div class="card-body box-profile">
        <h4 class="mb-4">مشاوره خصوصی</h4>
        {{ Form::open(array('route' => 'admin.service.store', 'method' => 'POST', 'files' => true)) }}
            <div class="row">
                <input type="hidden" id="service_type" name="service_type" value="مشاوره خصوصی">
                <div class="col-md-6">
                    <label for="category_id" >* دسته بندی خدمت</label>
                    <select id="category_id" name="category_id" class="form-control select2">
                        {{-- @if (auth()->user()->getRoleNames()->first()=="مدیر") --}}
                            @foreach ($items as $key => $item)
                                <option value="{{$item->id}}" {{$key==0?'selected':''}} >{{$item->title}}</option>
                            @endforeach
                        {{-- @else
                            @foreach ($items->where('title', auth()->user()->getRoleNames()->first() ) as $item)
                                <option value="{{$item->id}}" @if($items[0]->id == $item->id) selected @endif>{{$item->title}}</option>
                            @endforeach
                        @endif --}}
                    </select>
                    {{-- {{ Form::select('category_id' , Illuminate\Support\Arr::pluck($items,'title','id') , null, array('class' => 'form-control select2')) }} --}}
                </div>
                @if (auth()->user()->getRoleNames()->first()=='مدیر')
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('user_id', '* نام مشاور') }}
                            <select id="user_id" name="user_id" class="form-control select2">
                                @foreach (\App\User::orderByDesc('id')->role('مدرس')->get(['id','first_name','last_name']) as $key => $item)
                                    <option value="{{$item->id}}" {{$key==0?'selected':''}} >{{$item->first_name.' '.$item->last_name}}</option>
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
                        {{-- {{ Form::label('title', '* نام خدمت') }} --}}
                        {{ Form::label('title', '* رشته تحصیلی') }}
                        {{ Form::text('title',null, array('class' => 'form-control', 'required' => 'required')) }}
                    </div>
                </div>
                {{-- <div class="col-md-6">
                    <div class="form-group">
                        {{ Form::label('slug', '* نامک') }}
                        {{ Form::text('slug',null, array('class' => 'form-control', 'required' => 'required')) }}
                    </div>
                </div> --}}
                {{-- <div class="col-md-6">
                    <div class="form-group">
                        {{ Form::label('time', '* زمان (دقیقه)') }}
                        {{ Form::number('time',null, array('class' => 'form-control text-left', 'required' => 'required')) }}
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        {{ Form::label('time_start', '* ساعت شروع ') }}
                        {{ Form::dateTimeLocal('time_start',null, array('class' => 'form-control text-left','required' => 'required')) }}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {{ Form::label('time_end', '* ساعت پایان ') }}
                        {{ Form::dateTimeLocal('time_end',null, array('class' => 'form-control text-left','required' => 'required')) }}
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        {{ Form::label('limited', '* ظرفیت') }}
                        {{ Form::number('limited',null, array('class' => 'form-control text-left', 'required' => 'required')) }}
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
                        {{ Form::label('price', '* هزینه (هر دقیقه)') }}
                        {{ Form::number('price',null, array('class' => 'form-control','required' => 'required','onkeyup'=>'number_price(this.value)')) }}
                        <span id="price_span" class="span_p"><span id="pp_price"></span> تومان </span>
                    </div>
                </div>
                <hr>
                <h6 class="col-12">ساعت کاری روزهای شنبه</h6>
                <div class="col-6">
                    <div class="form-group">
                        {{ Form::label('shanbe', '* ساعت شروع ') }}
                        {{ Form::time('shanbe','23:59', array('class' => 'form-control text-left','required' => 'required')) }}
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        {{ Form::label('e_shanbe', '* ساعت پایان ') }}
                        {{ Form::time('e_shanbe','23:59', array('class' => 'form-control text-left','required' => 'required')) }}
                    </div>
                </div>
                <h6 class="col-12">ساعت کاری روزهای یکشنبه</h6>
                <div class="col-6">
                    <div class="form-group">
                        {{ Form::label('yekshanbe', '* ساعت شروع ') }}
                        {{ Form::time('yekshanbe','23:59', array('class' => 'form-control text-left','required' => 'required')) }}
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        {{ Form::label('e_yekshanbe', '* ساعت پایان ') }}
                        {{ Form::time('e_yekshanbe','23:59', array('class' => 'form-control text-left','required' => 'required')) }}
                    </div>
                </div>
                <h6 class="col-12">ساعت کاری روزهای دوشنبه</h6>
                <div class="col-6">
                    <div class="form-group">
                        {{ Form::label('doshanbe', '* ساعت شروع ') }}
                        {{ Form::time('doshanbe','23:59', array('class' => 'form-control text-left','required' => 'required')) }}
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        {{ Form::label('e_doshanbe', '* ساعت پایان ') }}
                        {{ Form::time('e_doshanbe','23:59', array('class' => 'form-control text-left','required' => 'required')) }}
                    </div>
                </div>
                <h6 class="col-12">ساعت کاری روزهای سه شنبه</h6>
                <div class="col-6">
                    <div class="form-group">
                        {{ Form::label('seshanbe', '* ساعت شروع ') }}
                        {{ Form::time('seshanbe','23:59', array('class' => 'form-control text-left','required' => 'required')) }}
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        {{ Form::label('e_seshanbe', '* ساعت پایان ') }}
                        {{ Form::time('e_seshanbe','23:59', array('class' => 'form-control text-left','required' => 'required')) }}
                    </div>
                </div>
                <h6 class="col-12">ساعت کاری روزهای چهارشنبه</h6>
                <div class="col-6">
                    <div class="form-group">
                        {{ Form::label('chaharshanbe', '* ساعت شروع ') }}
                        {{ Form::time('chaharshanbe','23:59', array('class' => 'form-control text-left','required' => 'required')) }}
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        {{ Form::label('e_chaharshanbe', '* ساعت پایان ') }}
                        {{ Form::time('e_chaharshanbe','23:59', array('class' => 'form-control text-left','required' => 'required')) }}
                    </div>
                </div>
                <h6 class="col-12">ساعت کاری روزهای پنج شنبه</h6>
                <div class="col-6">
                    <div class="form-group">
                        {{ Form::label('panjshanbe', '* ساعت شروع ') }}
                        {{ Form::time('panjshanbe','23:59', array('class' => 'form-control text-left','required' => 'required')) }}
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        {{ Form::label('e_panjshanbe', '* ساعت پایان ') }}
                        {{ Form::time('e_panjshanbe','23:59', array('class' => 'form-control text-left','required' => 'required')) }}
                    </div>
                </div>
                <h6 class="col-12">ساعت کاری روزهای جمعه</h6>
                <div class="col-6">
                    <div class="form-group">
                        {{ Form::label('jome', '* ساعت شروع ') }}
                        {{ Form::time('jome','23:59', array('class' => 'form-control text-left','required' => 'required')) }}
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        {{ Form::label('e_jome', '* ساعت پایان ') }}
                        {{ Form::time('e_jome','23:59', array('class' => 'form-control text-left','required' => 'required')) }}
                    </div>
                </div>
                {{-- <div class="col-md-6">
                    <label for="exampleInputFile">* تصویر(500×500)</label>
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="exampleInputFile" name="photo" accept=".jpeg,.jpg,.png" required>
                            <label class="custom-file-label" dir="ltr" for="exampleInputFile">انتخاب فایل</label>
                        </div>
                    </div>
                </div> --}}
                <div class="col-md-12">
                    <div class="form-group">
                        {{ Form::label('text', '* بایوگرافی کوتاه') }}
                        {{ Form::textarea('text',null, array('class' => 'form-control textarea', 'required' => 'required','onkeyup'=>'number_price(this.value)')) }}
                    </div>
                </div>
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


