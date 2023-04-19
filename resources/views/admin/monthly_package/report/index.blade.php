@extends('layouts.admin')
@section('css')
@endsection
@section('content')
    <section class="container-fluid">
        <div class="card res_table">
            <div class="card-header">{{$title1}}</div>
            <div class="card-body pt-2">
                <table class="table table-bordered table-hover mb-2 @if($reports->count()) tbl_1 @endif">
                    <thead> 
                        <tr>
                            <th>#</th>
                            <th>نام طرح</th>
                            <th>نام و نام خانوادگی</th>
                            <th>وضعیت</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($reports->count())
                            @foreach($reports as $index => $item)
                                <tr>
                                    <td>{{$index+1}}</td>
                                    <td>{{$item->package?$item->package->title:'__________'}}</td>
                                    <td>
                                        @if ($item->potential)
                                            @if ($item->potential->user)
                                                {{$item->potential->user->first_name.' '.$item->potential->user->last_name}}
                                            @else __________
                                            @endif
                                        @else __________
                                        @endif
                                    </td>
                                    <td class="@if($item->status=='success') text-success @elseif($item->status=='pending') text-info @else text-danger @endif">
                                        @if($item->status=='success') تایید شده @elseif($item->status=='pending') کاندید شده @else رد شده @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection

@section('js')
@endsection