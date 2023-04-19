@extends('layouts.admin')
@section('css')
@endsection
@section('content')
    <section class="container-fluid">
        <div class="card res_table">
            <div class="card-header">
                <h5>
                    {{$item->title}}
                </h5>
            </div>
            <div class="card-body pt-2"> 
                <table class="table table-bordered table-hover mb-2 @if($item->reports->count()) tbl_1 @endif">
                    <thead> 
                        <tr>
                            <th>#</th>
                            <th>نام شخص</th>
                            <th>متن گزارش</th>
                        </tr>   
                    </thead>
                    <tbody>
                        @foreach($item->reports as $index => $item)
                            <tr>
                                <td>{{$index+1}}</td>
                                <td>{{$item->fullname()}}</td>
                                <td>{{$item->text}}</td>
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
