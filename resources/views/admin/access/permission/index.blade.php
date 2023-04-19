@extends('layouts.admin')
@section('css')
@endsection
@section('content')
  <section class="container-fluid">
    <div class="card res_table">
      <div class="card-header">
        @can('permission_create')
          <a href="{{route('admin.permission.create')}}" class="btn btn-primary my-2">افزودن</a>
        @endcan
      </div>
      <div class="card-body pt-2">
        <div class="accordion" id="accordionExample">
          @foreach($items as $key=>$value)
            <div class="card mb-1">
              <div class="card-header p-2" id="headingOne{{$key}}">
                <h2 class="mb-0 w-100">
                  <button class="btn btn-block collapsed" type="button" data-toggle="collapse" data-target="#collapseOne{{$key}}" aria-expanded="true" aria-controls="collapseOne{{$key}}">
                    <h5 class="mb-0">
                      {{$value->table_name}}
                    </h5>
                  </button>
                </h2>
              </div>

              <div id="collapseOne{{$key}}" class="collapse" aria-labelledby="headingOne{{$key}}" data-parent="#accordionExample">
                <div class="card-body">
                  @foreach($value->permissions as $permission)
                    <div class="permission_box">
                      <p>
                        {{$permission->title}}
                      </p>
                      @can('permission_delete')
                        {!! Form::open(['method' => 'DELETE', 'route' => ['admin.permission.destroy', $permission->id] ]) !!}
                        <button class="action-btns1 float-left" data-toggle="tooltip"
                                data-placement="top" title="حذف"
                                onclick="return confirm('برای حذف مطمئن هستید؟')">
                          <i class="fa fa-trash text-danger"></i>
                        </button>
                        {!! Form::close() !!}
                      @endcan
                      @can('permission_edit')
                        <a href="{{route('admin.permission.edit',$permission->id)}}"
                           class="action-btns1 float-left" data-toggle="tooltip" data-placement="top"
                           title="ویرایش">
                          <i class="fa fa-edit  text-success"></i>
                        </a>
                      @endcan
                    </div>
                  @endforeach
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </section>
@endsection

