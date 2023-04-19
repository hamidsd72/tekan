@extends('layouts.admin')
@section('css')
<style>
    video {
        max-width: 100%;
        max-height: 176px;
    }
</style>
@endsection
@section('content')
  <section class="container-fluid">
    <div class="card card-primary card-outline">
        <div class="card-header box-profile">{{$item->title}}</div>
        <div class="card-body box-profile">

            <div class="col-12">
                <div class="row">
                    @foreach ($item->videos as $video)
                        @if ($video->path)
                            <div class="col-md-6 col-lg-4 col-xl-3 mb-3">
                                <div class="text-center mx-auto shadow rounded py-2">
                                    <video controls>
                                        <source src="{{url($video->path)}}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                    <div class="text-right">
                                        <a class="m-2" href="{{url($video->path)}}" download>بارگیری</a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach

                    @foreach ($item->links as $link)
                        @if ($link->url)
                            <div class="col-md-6 col-lg-4 col-xl-3 mb-3">
                                <div class="text-center mx-auto shadow rounded py-2">
                                    <video controls>
                                        <source src="{{url($link->url)}}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                    <div class="text-right">
                                        <a class="m-2" href="{{url($link->url)}}" download>بارگیری</a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
            {{-- <div class="col-md-6 col-lg-4">
                <table class="table table-bordered table-hover mb-2">
                    <thead>
                    <tr>
                        <th>دانلود ویدیو</th>
                        <th>نمایش ویدیو</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach ($item->videos as $video)
                          @if ($video->path)
                              <tr>
                                  <td><a href="{{url($video->path)}}" download>بارگیری</a></td>
                                  <td><a href="{{url($video->path)}}" target="_blank">نمایش</a></td>
                              </tr>
                          @endif
                        @endforeach

                        @foreach ($item->links as $link)
                            @if ($link->path)
                                <tr>
                                    <td><a href="{{url($link->path)}}" download>بارگیری</a></td>
                                    <td><a href="{{url($link->path)}}" target="_blank">نمایش</a></td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                  </table>
            </div> --}}

        </div>
    </div>
  </section>

@endsection
@section('js')
@endsection