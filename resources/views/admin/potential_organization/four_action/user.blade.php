@extends('layouts.admin')
@section('css')

@endsection
@section('content')
    <section class="container-fluid">
        <div class="card res_table">
            <div class="card-header">{{$title1}}</div>
            <div class="card-body pt-2"> 
                <table class="table table-bordered table-hover mb-2 @if($items->count()) tbl_1 @endif">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>نام و نام خانوادگی</th>
                            <th>موبایل</th>
                            <th>ایمیل</th>
                            <th>واتساپ</th>
                            <th>معرف</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $key => $item)
                            <tr>
                                <td>{{$key}}</td>
                                <td>@item($item->first_name) @item($item->last_name)</td>
                                <td>@item($item->mobile?$item->mobile:'__________')</td>
                                <td>@item($item->email?$item->email:'__________')</td>
                                <td>@item($item->whatsapp?$item->whatsapp:'__________')</td>
                                <td>@if($item->reagent) @item($item->reagent->first_name) @item($item->reagent->last_name) @else ندارد @endif</td>
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