@extends('layouts.admin')
@section('css')
@endsection
@section('content')
    <section class="container-fluid">
        <div class="card res_table">
            <div class="card-header">
                @can('org-performance-label_edit')
                    <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#create">افزودن {{$title2}}</a>
                    <div class="pt-3">
                        لیست رول های مجاز <br>
                        @foreach (\App\Model\Role::all() as $role)
                        {{$role->name.' , '}}
                        @endforeach
                    </div>
                @endcan
            </div>
            <div class="card-body pt-2">
                <table class="table table-bordered table-hover mb-2 @if($items->count()) tbl_1 @endif">
                    <thead> 
                        <tr>
                            <th>#</th>
                            <th>عنوان</th>
                            @can('org-performance-label_edit')
                                <th>وضعیت</th>
                                <th>ترتیب</th>
                                <th>دسترسی برای رول</th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $index=>$item)
                            <tr @if ($item->status=='pending' && $item->time===null) class="bad" @endif>
                                <td>{{$index+1}}</td>
                                <td>{{$item->label}}</td>
                                @can('org-performance-label_edit')
                                    <td>
                                        <form action="{{route('admin.org-performance-label.update',$item->id)}}" method="post" class="d-flex">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" id="status" class="form-control" style="max-width: 98px">
                                                <option value="active" @if( $item->status=='active' ) selected @endif>فعال</option>
                                                <option value="deactive" @if( $item->status=='deactive' ) selected @endif>غیرفعال</option>
                                            </select>
                                            <button type="submit" class="btn btn-warning p-0 px-1 mx-1">تغییر</button>
                                        </form>
                                    </td>
                                    <td>
                                        <form action="{{route('admin.org-performance-label.update',$item->id)}}" method="post" class="d-flex">
                                            @csrf
                                            @method('PATCH')
                                            <input type="number" name="sort" id="sort" min="0" value="{{$item->sort}}" class="form-control" style="max-width: 58px">
                                            <button type="submit" class="btn btn-warning mx-1">تغییر</button>
                                        </form>
                                    </td>
                                    <td>
                                        <form action="{{route('admin.org-performance-label.update',$item->id)}}" method="post">
                                            @csrf
                                            @method('PATCH')
                                            <input type="text" name="role" id="role" value="{{$item->role}}" class="form-control key_word">
                                            <button type="submit" class="btn btn-warning p-0 px-1 mt-1">تغییر</button>
                                        </form>
                                    </td>
                                @endcan
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
      
    <div class="modal" id="create">
        <div class="modal-dialog">
            <div class="modal-content mt-5">
                {{ Form::open(array('route' => 'admin.org-performance-label.store', 'method' => 'POST', 'files' => true , 'id' => 'form_req')) }}
                    <div class="modal-header">
                        <h4 class="modal-title">افزودن {{$title1}}</h4>
                    </div>
                
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="label" name="label" class="form-control" autocomplete="off" required>
                        </div>
                        
                        <div class="form-group">
                            <input type="text" name="role" id="role" class="form-control key_word" required>
                        </div>
                    </div>
                
                    <div class="modal-footer">
                        {{ Form::button('افزودن', array('type' => 'submit', 'class' => 'btn btn-success mx-3')) }}
                        <button type="button" class="btn btn-danger" data-dismiss="modal">انصراف</button>
                    </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>

@endsection

