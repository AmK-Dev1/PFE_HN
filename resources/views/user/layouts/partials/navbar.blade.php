<nav class="header-navbar navbar navbar-expand-lg align-items-center  floating-nav navbar-light navbar-shadow">
    <div class="navbar-container d-flex content">
        <div class="bookmark-wrapper d-flex align-items-center">
            <ul class="nav navbar-nav d-xl-none">
                <li class="nav-item"><a class="nav-link menu-toggle" href="javascript:void(0);"><i class="ficon" data-feather="menu"></i></a></li>
            </ul>
        </div>
        <ul class="nav navbar-nav align-items-center ml-auto">
            <li class="nav-item d-none d-lg-block"><a class="nav-link nav-link-style" id="switch-mode"><i class="ficon" data-feather="sun"></i></a></li>
        </ul>
        <ul class="nav navbar-nav align-items-center">
            {{--            <li class="nav-item d-none d-lg-block"><a class="nav-link nav-link-style"><i class="ficon" data-feather="moon"></i></a></li>--}}

            <li class="nav-item dropdown dropdown-user"><a class="nav-link dropdown-toggle dropdown-user-link" id="dropdown-user" href="javascript:void(0);" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="user-nav d-sm-flex d-none">
                        <span class="user-name font-weight-bolder">{{ Auth::user()->name }}</span>
                        <span class="user-status">{{ Auth::user()->username }}</span>
                    </div>
                    <span class="avatar">
                        <img class="round" src="{{ Auth::user()->image_url }}" alt="avatar" height="40" width="40">
                        <span class="avatar-status-online"></span>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-user">
                    <a class="dropdown-item" href="{{--{{route('user.users.show',Auth::user()->id)}}--}}">
                        <i class="mr-50" data-feather="user"></i>
                        {{__('labels.fields.profile')}}
                    </a>

                        <a class="dropdown-item" href="">-
                            <i class="mr-50" data-feather="user"></i>
                            Users
                      </a>



                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{--{{route('admin.setting.index')}}--}}">
                        <i class="mr-50" data-feather="settings"></i>
                        {{trans_choice('labels.models.setting',2)}}
                    </a>
                    <a class="dropdown-item" href="javascript:void(0)"
                       onclick="document.getElementById('logout-form').submit()">
                        <i class="mr-50" data-feather="power"></i>
                        {{__('labels.login.logout')}}
                    </a>
                    <form action="{{route('user.logout')}}" id="logout-form" method="post">@csrf</form>
                </div>
            </li>
        </ul>
    </div>
</nav>

