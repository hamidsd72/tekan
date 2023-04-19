
@if (Auth::user() && Auth::user()->first_name && Auth::user()->last_name)

    <div id="footer-bar" class="footer-bar-1" >

        @if(auth()->check())
            <a href="{{ route('admin.profile.show') }}" class="{{ \Request::route()->getName() == 'admin.profile.show' ? 'active-nav' : '' }}" data-menu="menu-settings">
                <i class="fa fa-user"></i><span>پروفایل</span></a>
        @else
            <a href="{{ route('login') }}" class="{{ \Request::route()->getName() == 'login' ? 'active-nav' : '' }}" data-menu="menu-settings">
                <i class="fa fa-lock"></i><span>ورود</span></a>
        @endif
    </div>

@endif
