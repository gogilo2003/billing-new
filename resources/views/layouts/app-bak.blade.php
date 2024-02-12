<!doctype html>
<html lang="{{ config('app.locale') }}">

<head>
    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="{{ asset('theme/assets/img/favicon.ico') }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

    <title>{{ config('app.name') }}</title>

    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />


    <!-- Bootstrap core CSS     -->
    <link href="{{ asset('theme/assets/css/bootstrap.min.css') }}" rel="stylesheet" />

    <!-- Animation library for notifications   -->
    <link href="{{ asset('theme/assets/css/animate.min.css') }}" rel="stylesheet" />

    <!--  Light Bootstrap Table core CSS    -->
    <link href="{{ asset('theme/assets/css/light-bootstrap-dashboard.css') }}" rel="stylesheet" />


    <!--  CSS for Demo Purpose, don't include it in your project     -->
    <link href="{{ asset('theme/assets/css/demo.css') }}" rel="stylesheet" />


    <!--     Fonts and icons     -->
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
    {{-- <link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'> --}}
    <link href="{{ asset('theme/assets/css/pe-icon-7-stroke.css') }}" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/assets/css/datatables.min.css') }}" />

    @yield('styles')
    @yield('scripts_top')

</head>

<body>

    <div class="wrapper">
        @include('layouts.sidebar')

        <div class="main-panel">

            @include('layouts.navbar')


            <div class="card-body">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>


            <footer class="footer">
                <div class="container-fluid">
                    @include('layouts.bottom-nav')
                    <p class="copyright pull-right">
                        &copy; {{ date('Y') }} <a href="{{ url('/') }}">{{ config('app.name') }}</a>
                    </p>
                </div>
            </footer>

        </div>
    </div>

    <form id="logout" method="post" action="{{ route('logout') }}" role="form" accept-charset="UTF-8"
        enctype="multipart/form-data">
        {{ csrf_field() }}
    </form>

    <!--   Core JS Files   -->
    <script src="{{ asset('theme/assets/js/jquery-1.10.2.js') }}" type="text/javascript"></script>
    <script src="{{ asset('theme/assets/js/bootstrap.min.js') }}" type="text/javascript"></script>

    <!--  Checkbox, Radio & Switch Plugins -->
    <script src="{{ asset('theme/assets/js/bootstrap-checkbox-radio-switch.js') }}"></script>

    <!--  Charts Plugin -->
    <script src="{{ asset('theme/assets/js/chartist.min.js') }}"></script>

    <!--  Notifications Plugin    -->
    <script src="{{ asset('theme/assets/js/bootstrap-notify.js') }}"></script>

    <!--  Google Maps Plugin    -->
    <!--<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>-->

    <!-- Light Bootstrap Table Core javascript and methods for Demo purpose -->
    <script src="{{ asset('theme/assets/js/light-bootstrap-dashboard.js') }}"></script>

    <!-- Light Bootstrap Table DEMO methods, don't include it in your project! -->
    <script src="{{ asset('theme/assets/js/demo.js') }}"></script>

    <script type="text/javascript" src="{{ asset('theme/assets/js/datatables.min.js') }}"></script>
    @yield('scripts_bottom')

    <script type="text/javascript">
        $(document).ready(function() {
            $('.modal').on('show.bs.modal', function() {
                $(this).css('z-index', 9999)
                console.log($(this).css('z-index'));
            });

            @if (Session::has('global-info'))
                {!! "$.notify({
                    icon: 'pe-7s-info',
                    message: '" .
    Session::get('global-info') .
    "'

                },{
                    type: 'info',
                    timer: 5000
                });" !!}
            @endif

            @if (Session::has('global-success'))
                {!! "$.notify({
                    icon: 'pe-7s-check',
                    message: '" .
    Session::get('global-success') .
    "'

                },{
                    type: 'success',
                    timer: 5000
                });" !!}
            @endif

            @if (Session::has('global-warning'))
                {!! "$.notify({
                    icon: 'pe-7s-attention',
                    message: '" .
    Session::get('global-warning') .
    "'

                },{
                    type: 'warning',
                    timer: 5000
                });" !!}
            @endif

            @if (Session::has('global-danger'))
                {!! "$.notify({
                    icon: 'pe-7s-attention',
                    message: '" .
    Session::get('global-danger') .
    "'

                },{
                    type: 'danger',
                    timer: 5000
                });" !!}
            @endif

        })

        function logout() {
            $('form#logout').submit();
        }
    </script>

</body>


</html>
