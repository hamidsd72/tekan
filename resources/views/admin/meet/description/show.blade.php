@extends('layouts.admin')
@section('css')
@endsection
@section('content')
    <section class="container-fluid">
        <div class="card res_table">
            <div class="card-header">
                <button class="btn btn-primary " data-toggle="modal" data-target="#create">افزودن {{$title1}}</button>
            </div>
            <div class="card-body pt-2"> 
                <table class="table table-bordered table-hover mb-2 @if($items->count()) tbl_1 @endif">
                    <thead> 
                        <tr>
                            <th>#</th>
                            <th>توضیحات</th>
                            @if($items->count()) 
                                <th>عملیات</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $index => $item)
                            <tr>
                                <td>{{$index+1}}</td>
                                <td>{{$item->description}}</td>
                                <td>
                                    <button class="btn btn-sm btn-danger " data-toggle="modal" data-target="#myModal{{$item->id}}">حذف توضیحات</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <div class="modal" id="create">
        <div class="modal-dialog">
            <form action="{{route('admin.workshop-description.store')}}" method="post">
                @csrf
                @method('POST')
                <div class="modal-content">
            
                    <div class="modal-header">
                        <h4 class="modal-title">افزودن توضیحات به جلسه</h4>
                    </div>
            
                    <div class="modal-body">
                        {{ Form::hidden('meet_id',$id, array('')) }}
                        <div class="form-group">
                            {{ Form::label('description', '* توضیحات') }}
                            {{ Form::text('description',null, array('class' => 'form-control')) }}
                        </div>
                    </div>
            
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary my-0 mx-3" data-dismiss="modal">انصراف</button>
                        <button type="submit" class="btn btn-success my-0">افزودن توضیحات</button>
                    </div>
            
                </div>
            </form>
        </div>
    </div>

    @foreach($items as $item)
        <div class="modal" id="myModal{{$item->id}}">
            <div class="modal-dialog">
                <form action="{{route('admin.workshop-description.destroy',$item->id)}}" method="post">
                    @csrf
                    @method('DELETE')
                    <div class="modal-content">
                
                        <div class="modal-header">
                            <h4 class="modal-title">آیتم حذف شود!</h4>
                        </div>
                
                        <div class="modal-body">
                            <h4>حذف : {{$item->description}}</h4>
                            <p class="text-danger p-0 m-0">این عملیات بدون بازگشت است!</p>
                        </div>
                
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary my-0 mx-3" data-dismiss="modal">انصراف</button>
                            <button type="submit" class="btn btn-danger my-0">حذف جلسه</button>
                        </div>
                
                    </div>
                </form>
            </div>
        </div>
    @endforeach
      
@endsection
@section('js')
@endsection
