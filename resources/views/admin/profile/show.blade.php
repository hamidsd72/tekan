@extends('layouts.admin')
@section('css')
    <style>
        .user-profile-box-border {
            border: 1px solid gray;
            border-radius: 4px;
            padding: 0px;
        }

        @media only screen and (max-width: 640px) {
            .small-box h3 {
                font-size: 16px !important;
            }
        }

        .small-box > .small-box-footer {
            font-size: 12px !important;
        }

        .small-box {
            border-radius: 20px;
        }

        .small-box > .small-box-footer {
            border-radius: 20px;
            margin: 0px 12px;
        }

        .user-profile-box-border {
            border: none;
            text-align: center;
        }

        .row .small-box .inner h3, .row .small-box .inner p {
            color: white !important;
        }

        @media only screen and (max-width: 640px) {
            .row .small-box .inner h3, .row .small-box .inner p {
                margin: 0px;
            }
        }

    </style>

    <link rel='stylesheet' href="{{url('assets/front/css/circle-bar.css')}}" media="all"/>
@endsection
@section('content')
    <section class="content">
            @include('admin.user._user_information',['user'=>$item])
    </section>

@endsection

