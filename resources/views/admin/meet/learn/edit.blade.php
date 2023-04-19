@extends('layouts.admin')
@section('content')
  <section class="container-fluid">
    <div class="card card-primary card-outline">
        <div class="card-body box-profile">
            {{ Form::model( $item , array('route' => array('admin.learn.update', $item->id), 'method' => 'PATCH', 'files' => true , 'id' => 'form_req')) }}

                <div class="row my-0">
                    <div class="col-lg-6">
                        {{ Form::label('title', '* عنوان') }}
                        {{ Form::text('title',null, array('class' => 'form-control', 'required' => 'required')) }}
                    </div>

                    <div class="col-lg-6">
                        {{ Form::label('role', '* انتخاب رول ها') }}
                        <select class="form-control select2" name="role[]" multiple>
                            @foreach(\App\Model\Role::whereNotIn('title',['مدیر','برنامه نویس'])->get() as $role)
                                <option value="{{$role->id}}" {{ in_array($role->id, explode(',',$item->role))?'selected':'' }}>{{$role->title}}</option>
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
                            <label class="custom-file-label" dir="ltr" for="exampleInputFile">انتخاب فایل</label>
                        </div>
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

            <div class="mt-5 col-md-6 col-lg-4">
                <table class="table table-bordered table-hover mb-2">
                    <thead>
                    <tr>
                        <th>دانلود ویدیو</th>
                        <th>نمایش ویدیو</th>
                        <th>عملیات</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach ($item->videos as $video)
                          @if ($video->path)
                              <tr>
                                  <td>فایل</td>
                                  <td><a href="{{url($video->path)}}" download>بارگیری</a></td>
                                  <td><a href="{{url($video->path)}}" target="_blank">نمایش</a></td>
                                  <td>
                                      @can('workshop_online_delete')
                                          <button class="btn btn-danger p-0 px-1" data-toggle="modal" data-target="#video{{$video->id}}">حذف</button>
                                      @endcan
                                  </td>
                              </tr>
                          @endif
                        @endforeach

                        @foreach ($item->links as $link)
                            @if ($link->url)
                                <tr>
                                    <td>لینک</td>
                                    <td><a href="{{url($link->url)}}" download>بارگیری</a></td>
                                    <td><a href="{{url($link->url)}}" target="_blank">نمایش</a></td>
                                    <td>
                                        @can('workshop_online_delete')
                                            <button class="btn btn-danger p-0 px-1" data-toggle="modal" data-target="#link{{$link->id}}">حذف</button>
                                        @endcan
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                  </table>
            </div>

        </div>
    </div>
  </section>
  

  @foreach ($item->videos as $vid)
    <div class="modal" id="video{{$vid->id}}">
      <div class="modal-dialog">
        <form action="{{route('admin.learn.file.destroy',$vid->id)}}" method="post">
          @csrf
          <input type="hidden" name="item_id" value="{{$item->id}}">
          <input type="hidden" name="type" value="video">
          <div class="modal-content">

            <div class="modal-header">
              <h4 class="modal-title">حذف فایل از این آموزش</h4>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary my-0 mx-3" data-dismiss="modal">انصراف</button>
              <button type="submit" class="btn btn-danger my-0">حذف شود</button>
            </div>

          </div>
        </form>
      </div>
    </div>
  @endforeach

  @foreach ($item->links as $link)
    <div class="modal" id="link{{$link->id}}">
      <div class="modal-dialog">
        <form action="{{route('admin.learn.file.destroy',$link->id)}}" method="post">
          @csrf
          <input type="hidden" name="item_id" value="{{$item->id}}">
          <input type="hidden" name="type" value="link">
          <div class="modal-content">

            <div class="modal-header">
              <h4 class="modal-title">حذف فایل از این آموزش</h4>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary my-0 mx-3" data-dismiss="modal">انصراف</button>
              <button type="submit" class="btn btn-danger my-0">حذف شود</button>
            </div>

          </div>
        </form>
      </div>
    </div>
  @endforeach

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