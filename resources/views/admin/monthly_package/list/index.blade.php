@extends('layouts.admin')
@section('css')
@endsection
@section('content')
    <section class="container-fluid">
        <div class="card res_table">
            <div class="card-header">
                <a href="#" class="btn btn-primary my-2" data-toggle="modal" data-target="#createItem">افزودن طرح</a>
                <h6 class="text-danger pt-1">درصورت فعالسازی هر آیتم اطلاعات آیتم قبل غیرفعال میشود</h6>
            </div>
            <div class="card-body pt-2">
                <table class="table table-bordered table-hover mb-2 @if($items->count()) tbl_1 @endif">
                    <thead> 
                        <tr>
                            <th>#</th>
                            <th>عنوان</th>
                            <th>وضعیت</th>
                            @if(count($items)>0)
                                <th>عملیات</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $index=>$item)
                            <tr>
                                <td>{{$index+1}}</td>
                                <td>{{$item->title}}</td>
                                <td class="{{$item->status=='active'?'text-success':'text-danger'}}">{{$item->status=='active'?'فعال':'غیرفعال'}}</td>
                                <td class="text-center">
                                    <a href="#" class="badge bg-primary" title="ویرایش" data-toggle="modal" data-target="#editItem{{$item->id}}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <div class="modal" id="createItem">
        <div class="modal-dialog">
            <div class="modal-content">
        
                <div class="modal-header">
                    <h4 class="modal-title">افزودن طرح</h4>
                </div>
        
                <div class="modal-body">
                    {{ Form::open(array('route' => 'admin.monthly-package.store', 'method' => 'POST', 'files' => true , 'id' => 'form_req')) }}
                        <div class="form-group">
                            {{ Form::label('title', 'عنوان *') }}
                            {{ Form::text('title',null, array('class' => 'form-control' ,'required')) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('status', 'وضعیت *') }}
                            {{ Form::select('status', ['active'=>'فعال','pending'=>'غیرفعال'], null, array('class' => 'form-control')) }}
                        </div>
                        {{ Form::button('افزودن', array('type' => 'submit', 'class' => 'btn btn-success mt-3')) }}
                        <button type="button" class="btn btn-secondary mb-0 mt-3 mx-3" data-dismiss="modal">انصراف</button>
                    {{ Form::close() }}
                </div>
        
            </div>
        </div>
    </div>
    
    @foreach($items as $item)
        <div class="modal" id="editItem{{$item->id}}">
            <div class="modal-dialog">
                <div class="modal-content">
            
                    <div class="modal-header">
                        <h4 class="modal-title">{{$item->title}}</h4>
                    </div>
            
                    <div class="modal-body">
                        {{ Form::model( $item , array('route' => array('admin.monthly-package.update', $item->id), 'method' => 'PATCH', 'files' => true)) }}
                            <div class="form-group">
                                {{ Form::label('title', 'عنوان *') }}
                                {{ Form::text('title',null, array('class' => 'form-control' ,'required')) }}
                            </div>
                            <div class="form-group">
                                {{ Form::label('status', 'وضعیت *') }}
                                {{ Form::select('status', ['active'=>'فعال','pending'=>'غیرفعال'], $item->status, array('class' => 'form-control')) }}
                            </div>
                            {{ Form::button('ویرایش', array('type' => 'submit', 'class' => 'btn btn-success mt-3')) }}
                            <button type="button" class="btn btn-secondary mb-0 mt-3 mx-3" data-dismiss="modal">انصراف</button>
                        {{ Form::close() }}
                    </div>
            
                </div>
            </div>
        </div>
    @endforeach

    @if ($active)
        <script>alert('تعداد '+ '{{$active}}' +' گزارش درانتظار بررسی از طرح جاری وجود دارد و با تغییر طرح غیرفعال میشوند')</script>
    @endif
@endsection

