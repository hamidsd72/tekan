@extends('layouts.admin')
@section('content')
  <section class="container-fluid">
    <div class="card card-primary card-outline">
        <div class="card-body box-profile">
            {{ Form::open(array('route' => 'admin.workshop.store', 'method' => 'POST', 'files' => true , 'id' => 'form_req')) }}

                <div class="row my-0">
                    <div class="col-lg-6">
                        {{ Form::label('title', '* موضوع') }}
                        {{ Form::text('title',null, array('class' => 'form-control', 'required' => 'required')) }}
                    </div>

                    <div class="col-lg">
                        <div class="form-group">
                            {{ Form::label('date', 'تاریخ') }}
                            {{ Form::text('date', null, array('class' => 'form-control date_p', 'readonly', 'autocomplete' => 'false')) }}
                        </div>
                    </div>

                    <div class="col-lg" id="showBox">
                        <div class="form-group">
                            {{ Form::label('show_box', '* نوع جلسه') }}
                            <select id="show_box" name="show_box" class="form-control select2" onchange="changeInput()">
                                <option value=0 selected>تک جلسه ای</option>
                                <option value=1 >چند جلسه ای</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row my-0 d-none" id="hideList">
                    <div class="col-lg-6">
                        {{ Form::label('reply', '* تعداد جلسات') }}
                        {{ Form::number('reply',null, array('class' => 'form-control', 'required' => 'required')) }}
                    </div>
    
                    <div class="col-lg-6">
                        <div class="form-group">
                            {{ Form::label('addDays', '* هر چند روز یکبار') }}
                            {{ Form::number('addDays',null, array('class' => 'form-control')) }}
                        </div>
                        <p class="m-0 p-0 text-info">فاصله جلسات باید حداقل دو روز باشد</p>
                    </div>
                </div>

                {{ Form::button('افزودن', array('type' => 'submit', 'class' => 'btn btn-success mx-3')) }}
                <a href="{{ URL::previous() }}" class="btn btn-secondary m-0">بازگشت</a>
            {{ Form::close() }}
        </div>
    </div>
  </section>
@endsection
@section('js')
<script>
    slug('#title', '#slug');

    function changeInput() {
        if (document.getElementById("show_box").value == 1 ) {
            document.getElementById("showBox").classList.add("d-none");
            document.getElementById("hideList").classList.remove("d-none");
            document.getElementById("reply").value = 2;
            document.getElementById("addDays").value = 5;
        }
    }    
</script>
@endsection