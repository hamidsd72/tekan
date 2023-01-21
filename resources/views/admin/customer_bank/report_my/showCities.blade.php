@extends('layouts.admin')
@section('css')
@endsection
@section('content')
    <section class="container-fluid">
        <div class="card res_table">
            <div class="card-header">{{$title2}}</div>
            <div class="card-body pt-2">
                <table class="table table-bordered table-hover mb-2 @if(count($cities)) tbl_1 @endif">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>نام شهر</th>
                            <th>تعداد مشتری</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cities as $key => $item)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{$item->name}}</td>
                                <td>{{$item->membersData}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </section>
@endsection
@section('js')
@endsection

