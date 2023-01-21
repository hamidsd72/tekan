<section wire:poll.1000ms>

    <main>
        <img src="{{$user2 && $user2->photo?url($user2->photo->path):url('file_call/img/user_call.png')}}"
             alt="{{$call->unique_code}}">
        <p class="name">{{$user2 ? $user2->first_name.' '.$user2->last_name:'بدون نام'}}</p>
{{--        <p>{{$min_time}}</p>--}}
        @if($call->status=='pending')
            <p class="calling">درحال تماس</p>
            <div class="lds-ellipsis">
                <div></div>
                <div></div>
                <div></div>
            </div>
        @elseif($call->status=='doing')
            <p class="timer" dir="ltr">
                <span class="timer_min">{{floor($min_time/60)<10?'0'.floor($min_time/60):floor($min_time/60)}}</span>
                :
                <span class="timer_sec">{{floor($min_time%60)<10?'0'.floor($min_time%60):floor($min_time%60)}}</span>
            </p>
        @elseif($return_msg!=null)
            <p class="calling">{{$return_msg}}</p>
            @php
                auth()->user()->redirect_page($return_route,$return_msg)
            @endphp
        @endif
    </main>



    @section('js')

        <script>

            function callingTimer() {

                {{--//		var endTime = new Date("29 April 2018 9:56:00 GMT+01:00");--}}
                {{--var endTime1 =(Date.parse('{{$end_request}}') / 1000)+19;--}}
                {{--var now1 = new Date();--}}
                {{--now1 = (Date.parse(now1) / 1000);--}}

                var timeLeft1 = {{$min_time}};
                {{--var minutes = Math.floor(timeLeft1 / 60);--}}
                {{--var seconds = Math.floor(timeLeft1 % 60);--}}
                {{--if(minutes>=0 && seconds>=0)--}}
                {{--{--}}
                {{--    if (minutes <"10") { minutes = "0" + minutes; }--}}
                {{--    if (seconds < "10") { seconds = "0" + seconds; }--}}

                {{--    $(".timer_min").html(minutes);--}}
                {{--    $(".timer_sec").html(seconds);--}}
                {{--}--}}
                if(timeLeft1<=0)
                {
                    $('.calling').text('تماس پایان یافت')
                    $('.lds-ellipsis').addClass('d-none')
                    timeLeft1=1000;
                    var url='{{route('user.call.no.reply',$call->unique_code)}}';
                    window.location.href = url;
                }
            }
            setInterval(function() {
                callingTimer()
            }, 1000);

        </script>
    @endsection
</section>
