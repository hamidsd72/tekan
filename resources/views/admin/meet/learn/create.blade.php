@extends('layouts.admin')
@section('content')
  <section class="container-fluid">
    <div class="card card-primary card-outline">
        <div class="card-body box-profile">
            {{ Form::open(array('route' => 'admin.learn.store', 'method' => 'POST', 'files' => true , 'id' => 'form_req')) }}

                <div class="row my-0">
                    <div class="col-lg-6">
                        {{ Form::label('title', '* عنوان') }}
                        {{ Form::text('title',null, array('class' => 'form-control', 'required' => 'required')) }}
                    </div>

                    <div class="col-lg-6">
                        {{ Form::label('role', '* انتخاب رول ها') }}
                        <select class="form-control select2" name="role[]" multiple>
                            @foreach(\App\Model\Role::whereNotIn('title',['مدیر','برنامه نویس'])->get() as $role)
                                {{-- <option value="{{$role->id}}" {{ in_array($role->id, explode(',',$item->role))?'selected':'' }}>{{$role->title}}</option> --}}
                                <option value="{{$role->id}}">{{$role->title}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 py-2"></div>

                    <div class="col-md-6 col-lg-3" id="change_input">
                        <div class="form-group">
                            {{ Form::label('upload_type', 'روش بارگذاری') }}
                            {{ Form::select('upload_type', ['' => 'روش بارگذاری را انتخاب کنید','link'=>'با لینک (لینک ویدیو)','video'=>'با ویدیو (فایل)'], null, array('class' => 'form-control select2','onchange'=>'changeInput()')) }}
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-3 d-none" id="video">
                        <label for="exampleInputFile">ویدئوها mp4(حداکثر هر فایل 50 مگابایت)</label>
                        <div class="custom-file">
                            {{-- <input type="file" class="custom-file-input" id="exampleInputFile" name="video[]" accept=".mp4"> --}}
                            <input type="file" class="custom-file-input" id="exampleInputFile" name="video" accept=".mp4">
                            {{-- <label class="custom-file-label" dir="ltr" for="exampleInputFile">انتخاب فایل چندتایی</label> --}}
                            <label class="custom-file-label" dir="ltr" for="exampleInputFile">انتخاب فایل</label>
                        </div>
                        {{-- @if($item->video)
                            <a href="{{url($item->video->path)}}" class="mt-2" target="_blank">نمایش ویدئو</a>
                        @endif --}}
                    </div>

                    <div class="col-lg d-none" id="link">
                        {{ Form::label('link', '* لینک ویدیو (برای ویدیوهای بزرگ)') }}
                        {{ Form::text('link',null, array('class' => 'form-control')) }}
                    </div>

                    <div class="col-12 py-3"></div>
                </div>

                {{ Form::button('افزودن', array('type' => 'submit', 'class' => 'btn btn-success')) }}
                <a href="{{ URL::previous() }}" class="btn btn-secondary m-0 mx-3">بازگشت</a>
            {{ Form::close() }}
        </div>
    </div>
  </section>
@endsection
@section('js')
<script>
    function changeInput() {
        if (document.getElementById("upload_type").value=='link') {
            document.getElementById("change_input").classList.add("d-none");
            document.getElementById("link").classList.remove("d-none");
        } else if (document.getElementById("upload_type").value=='video') {
            document.getElementById("change_input").classList.add("d-none");
            document.getElementById("video").classList.remove("d-none");
        }
    }
</script>
@endsection