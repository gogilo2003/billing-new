{{--
<nav class="navbar fixed-top navbar-expand-lg navbar-light white scrolling-navbar>
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ url('/') }}">BILLING</a>
        <button href="" class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-bar burger-lines"></span>
            <span class="navbar-toggler-bar burger-lines"></span>
            <span class="navbar-toggler-bar burger-lines"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navigation">
            @auth
            <ul class="nav navbar-nav mr-auto">
                @if(fetch_notifications()->count())
                <li class="dropdown nav-item">
                    <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                        <i class="nc-icon nc-planet"></i>
                        <span class="notification">fetch_notifications()->count()</span>
                        <span class="d-lg-none">Notification</span>
                    </a>
                    <ul class="dropdown-menu">
                        @foreach (fetch_notifications() as $notification)
                        <a class="dropdown-item" href="#">{{ $notification->title }} 1</a>
                        @endforeach
                    </ul>
                </li>
                @endif
                @section('navbar')

                @show
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('profile') }}">
                        <span class="no-icon">Account</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="JavaScript:logout()">
                        <span class="no-icon">Log out</span>
                    </a>
                </li>
            </ul>
            @endauth
        </div>
    </div>
</nav>
--}}
<!-- Navbar -->

<nav class="navbar fixed-top navbar-expand-lg navbar-light white scrolling-navbar">
    <div class="container-fluid">
        <button class="sidebar-btn" id="sidebar-btn"><i class="fa fa-chevron-right"></i></button>
        <!-- Brand -->
        <a class="navbar-brand waves-effect" href="{{ url('/') }}">
            <strong class="blue-text">
                BILLING::
                @section('title')
                    Dashboard
                @show
            </strong>
        </a>

        <!-- Collapse -->
        <button class="navbar-toggler" type="button" data-toggle="collapse"
            data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Links -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            @auth
            <!-- Left -->
            <ul class="navbar-nav mr-auto">
                 @if(fetch_notifications()->count())
                <li class="dropdown nav-item">
                    <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                        <i class="nc-icon nc-planet"></i>
                        <span class="notification">{{ fetch_notifications()->count() }}</span>
                        <span class="d-lg-none">Notification</span>
                    </a>
                    <div class="dropdown-menu">
                        @foreach (fetch_notifications() as $notification)
                        <a class="dropdown-item" href="#">{{ $notification->title }} 1</a>
                        @endforeach
                    </div>
                </li>
                @endif
                @section('navbar')

                @show
            </ul>

            <!-- Right -->
            <ul class="navbar-nav nav-flex-icons">
                {{--
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">User</a>
                    <div class="dropdown-menu dropdown-primary" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="{{ route('profile') }}">Profile</a>
                        <a class="dropdown-item" href="JavaScript:logout()">Logout</a>
                    </div>
                </li>
                 --}}
                <li class="nav-item mr-2">
                    <a href="{{ route('profile') }}"
                        class="nav-link border border-light rounded waves-effect" target="_blank">
                        <i class="fas fa-user mr-2"></i>Profile
                    </a>
                </li>
                <li class="nav-item">
                    <a href="javascript:logout()" class="nav-link border border-light rounded waves-effect">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                    </a>
                </li>
            </ul>
            @endauth

        </div>

    </div>
</nav>
<!-- Navbar -->
@auth
    <form id="logout" method="post" action="{{route('logout')}}" role="form" accept-charset="UTF-8" enctype="multipart/form-data">
        {{ csrf_field() }}
    </form>
@endauth

@push('scripts_bottom')
    @auth
        <script type="text/javascript">
            function logout() {
                console.log("Logout")
                $('form#logout').submit();
            }
        </script>
    @endauth
@endpush
