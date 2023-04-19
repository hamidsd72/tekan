@if($user)
    <div class="row mt-3">
        <div class="col-md-9 m-auto">

            <!-- Profile Image -->
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <img class="profile-user-img img-circle"
                             src="{{$user->photo? url($user->photo->path) :asset('admin/img/user.png')}}"
                             alt="User profile picture">
                    </div>

                    <h3 class="profile-username text-center">@item($user->first_name) @item($user->last_name)</h3>

                    <p class="text-muted text-center">@item($user->education)</p>
                    <div class="container-fluid">
                        <hr>
                        <div class="row">
                            <div class="col-sm-6">
                                <strong><i class="fa fa-at ml-1"></i> ایمیل </strong>
                                <p class="text-muted">
                                    {{$user->email ?? '-'}}
                                </p>
                            </div>
                            <div class="col-sm-6">
                                <strong><i class="fa fa-mobile ml-1"></i> موبایل</strong>
                                <p class="text-muted">
                                    @if($user->mobile!=null) @item($user->mobile) @else ثبت نشده @endif
                                    @if($user->mobile_verified)
                                        <span class="right badge badge-success">تایید شده</span>
                                    @else
                                        <span class="right badge badge-danger">تایید نشده</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <hr>
                        <div class="row">
                            <div class="col-sm-12">
                                <strong><i class="fa fa-map-marker ml-1"></i> موقعیت</strong>
                                <p class="text-muted">
                                    @if($user->state) @item($user->state->name) - @endif
                                    @if($user->city) @item($user->city->name) - @endif
                                    @if($user->locate!=null) @item($user->locate) - @endif
                                    @if($user->address!=null) @item($user->address)  @endif
                                </p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-6">
                                <strong><i class="fa fa-book ml-1"></i> تحصیلات</strong>
                                <p class="text-muted">
                                    @if($user->education!=null) @item($user->education) @else ثبت نشده @endif
                                </p>
                            </div>

                            <div class="col-sm-6">
                                {{-- <strong><i class="fa fa-registered ml-1"></i> معرف</strong>
                                <p class="text-muted">
                                    @if($user->reagent_code!=null and $user->reagent_code=='rytl_user')
                                        رایتل (کد : @item($user->reagent_code))
                                    @elseif($user->reagent_code!=null and $user->reagent)
                                        @item($user->reagent->first_name) @item($user->reagent->last_name) (کد : @item($user->reagent_code))
                                    @else ثبت نشده @endif
                                </p> --}}
                                <strong><i class="fa fa-registered ml-1"></i> کدملی</strong>
                                <p class="text-muted">
                                    @item($user->national_code)
                                </p>
                            </div>
                        </div>
                        <hr>
                        @if($item->reagent)
                            <div class="row">
                                <div class="col-sm-12">
                                    <strong><i class="fa fa-link ml-1"></i> بالاسری </strong>
                                    <p class="text-muted text-left">
                                        {{$item->reagent->full_name}}
                                        <br>
                                        {{$item->reagent->reagent_code}}
                                    </p>
                                </div>
                            </div>
                            <hr>
                        @endif
                        @if($user->reagent_code !=null)
                            <div class="row">
                                <div class="col-sm-12">
                                    <strong><i class="fa fa-link ml-1"></i> لینک دعوت</strong>
                                    <p class="text-muted text-left">
                                        <a title="برای کپی لینک دعوت کلیک کنید" href="javascript:void(0);" class="copy_btn"
                                         onclick="return alert('لینک کپی شد')" data-clipboard-text="{{route('register',['referred'=>$user->reagent_code])}}">{{route('register',['referred'=>$user->reagent_code])}}</a>
                                    </p>
                                </div>
                                <div class="col-sm-12">
                                    <strong><i class="fa fa-link ml-1"></i> کد دعوت</strong>
                                    <p class="text-muted text-left">
                                        <a title="برای کپی کد دعوت کلیک کنید" href="javascript:void(0);" class="copy_btn"
                                         onclick="return alert('لینک کپی شد')" data-clipboard-text="{{$user->reagent_code}}">{{$user->reagent_code}}</a>
                                    </p>
                                </div>
                            </div>
                            <hr>
                        @endif
                        <div class="row">
                            <div class="col-sm-6">
                                <strong><i class="fa fa-calendar-alt ml-1"></i> تاریخ ثبت</strong>
                                <p class="text-muted">
                                    {{my_jdate($user->create,'d F Y')}}
                                </p>
                            </div>
                            <div class="col-sm-6">
                                <strong><i class="fa fa-toggle-{{$user->status=='active'?'on':'off'}} ml-1"></i> وضعیت</strong>
                                <p class="text-muted">
                                    @if($user->status=='active')
                                        <span class="right badge badge-success">فعال</span>
                                    @else
                                        <span class="right badge badge-danger">غیرفعال</span>
                                    @endif
                                    {{-- @if($user->user_status=='pending')
                                        <span class="right badge badge-warning">بررسی</span>
                                    @elseif($user->user_status=='blocked')
                                        <span class="right badge badge-danger">مسدود</span>
                                    @elseif($user->user_status=='active')
                                        <span class="right badge badge-success">تایید شده</span>
                                    @endif --}}
                                </p>
                            </div>
                        </div>
                        <hr>
                    </div>
                </div>
                <!-- /.card-body -->
            </div><!-- /.card -->
        </div>
    </div>
@endif
