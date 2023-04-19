@extends('layouts.admin')
@section('css')
@endsection
@section('content')
    <section class="container-fluid">
        <div class="card res_table">
            <div class="card-header">
            </div>
            <div class="card-body pt-2">
                <table class="table table-bordered table-hover mb-2 @if($items->count()) tbl_1 @endif">
                    <thead> 
                        <tr>
                            <th>#</th>
                            <th>نام و نام خانواگی</th>
                            <th>ارتقا به</th>
                            {{-- @can('connection_edit') --}}
                                @if(count($items)>0)
                                    <th>عملیات</th>
                                @endif
                            {{-- @endcan --}}
                        </tr>
                    </thead>
                    <tbody>
                            @foreach($items as $index=>$item)
                                <tr>
                                    <td>{{$index+1}}</td>
                                    <td>{{$item->first_name.' '.$item->last_name}}</td>
                                    <td>{{$item->request_level}}</td>
                                    {{-- @can('connection_edit') --}}
                                        <td class="text-center">
                                            <a href="{{route('admin.user.level-up.result',[$item->id,'abort'])}}" class="badge bg-danger">رد</a>
                                            <a href="{{route('admin.user.level-up.result',[$item->id,'ok'])}}" class="badge bg-success mx-3">تایید</a>
                                        </td>
                                    {{-- @endcan --}}
                                </tr>
                            @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection

