<!-- Sidebar -->
<div class="sidebar-fixed position-fixed" style="top: 0" id="nav-sidebar">
    <div class="sidebar-wrapper">
        <a href="{{ url('/') }}" class="logo-wrapper waves-effect">
            <img src="{{ asset('images/logo.png') }}" class="img-fluid" alt="">
            <div class="nav-title">{{ config('app.name') }}</div>
        </a>

        <div  class="sidebar-close-btn">
            <button id="sidebar-close-btn"><i class="fa fa-times"></i></button>
        </div>

        <div class="list-group list-group-flush">
            @section('sidebar_left')
                @auth
                    <a class="list-group-item list-group-item-action waves-effect {{ is_current_path(url('/'),true) }}" href="{{ url('/') }}">
                        <i class="fas fa-tachometer-alt"></i>
                        Dashboard
                    </a>
                    <a class="list-group-item list-group-item-action waves-effect {{ is_current_path(route('clients'),true) }}" href="{{ route('clients') }}">
                        <i class="fas fa-users"></i>
                        Clients
                    </a>
                    <a class="list-group-item list-group-item-action waves-effect {{ is_current_path(route('accounts'),true) }}" href="{{ route('accounts') }}">
                        <i class="fas fa-university"></i>
                        Accounts
                    </a>
                    <a class="list-group-item list-group-item-action waves-effect {{ is_current_path(route('invoices'),true) }}" href="{{ route('invoices') }}">
                        <i class="fas fa-file-invoice"></i>
                        Invoices
                    </a>
                    <a class="list-group-item list-group-item-action waves-effect {{ is_current_path(route('products'),true) }}" href="{{ route('products') }}">
                        <i class="fas fa-shopping-cart"></i>
                        Products
                    </a>
                    <a class="list-group-item list-group-item-action waves-effect {{ is_current_path(route('services'),true) }}" href="{{ route('services') }}">
                        <i class="fas fa-cogs"></i>
                        Services
                    </a>
                    <a class="list-group-item list-group-item-action waves-effect {{ is_current_path(route('setup'),true) }}" href="{{ route('setup') }}">
                        <i class="fas fa-tools"></i>
                        Setup
                    </a>
                @endauth
                @guest
                    <a class="list-group-item list-group-item-action waves-effect {{ is_current_path(route('login'),true) }}" href="{{ route('login') }}">
                        <i class="fas fa-key"></i>
                        Login
                    </a>
                @endguest
            @show
        </div>
    </div>
</div>

@push('scripts_bottom')
    @auth
        <script type="text/javascript">
            let sidebarBtn = document.getElementById("sidebar-btn")
            let sidebar = document.getElementById("nav-sidebar")

            sidebarBtn.addEventListener("click",e=>{
                sidebar.classList.add("show")
            })

            document.getElementById("sidebar-close-btn").addEventListener("click",e=>{
                sidebar.classList.remove("show")
            })

        </script>
    @endauth
@endpush
