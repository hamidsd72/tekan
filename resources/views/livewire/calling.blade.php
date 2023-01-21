{{--<div  wire:poll.500ms>--}}
{{--@if(auth()->check() && $call_req)--}}
{{--  <div class="accept_call">--}}
{{--        <audio autoplay loop>--}}
{{--          <source src="{{url('file_call/img/ring3.mp3')}}" type="audio/mpeg">--}}
{{--        </audio>--}}
{{--      <div class="container h-100">--}}
{{--        <div class="row h-100">--}}
{{--          <div class="col-12 text-center h-50">--}}
{{--            <img class="call_img" src="{{$call_req->user && $call_req->user->photo?url($call_req->user->photo->path):url('file_call/img/user_call.png')}}" alt="{{$call_req->unique_code}}">--}}
{{--            <p class="call_name text-center mt-2">{{$call_req->user?$call_req->user->first_name.' '.$call_req->user->last_name:''}}</p>--}}
{{--          </div>--}}
{{--          @if($d_none=='end')--}}
{{--          <p class="text_call">تماس پایان یافت</p>--}}
{{--          @elseif($d_none=='start')--}}
{{--          <p class="text_call">منتظر برقراری ارتباط باشید</p>--}}
{{--          @endif--}}
{{--          <div class="col-6 text-center h-50 position-relative">--}}
{{--            <a href="{{route('user.call.accept',[$call_req->unique_code,'blocked'])}}" wire:click="end_call()" @if($d_none!=null)  style="display: none" @endif class="call_end">--}}
{{--              <img src="{{url('file_call/img/end_call.png')}}" alt="end_call">--}}
{{--            </a>--}}
{{--          </div>--}}
{{--          <div class="col-6 text-center h-50 position-relative">--}}
{{--            <a href="{{route('user.call.accept',[$call_req->unique_code,'doing'])}}" wire:click="start_call()" @if($d_none!=null) style="display: none" @endif class="call_accept">--}}
{{--              <img src="{{url('file_call/img/accept_call.png')}}" alt="accept_call">--}}
{{--            </a>--}}
{{--          </div>--}}
{{--        </div>--}}
{{--      </div>--}}
{{--  </div>--}}
{{--@endif--}}

{{--</div>--}}