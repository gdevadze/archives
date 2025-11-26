<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <title>@yield('title',config('app.name'))</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="S & I Group Admin Panel" name="description"/>
    <!-- App favicon -->
    <link rel="icon" type="image/png" href="https://s-igroup.ge/front/img/logo.png" />

    <!-- Bootstrap Css -->
    <link href="{{ asset('admin/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css"/>
    <!-- Icons Css -->
    <link href="{{ asset('admin/css/icons.min.css') }}" rel="stylesheet" type="text/css"/>
    <!-- App Css-->
    <link href="{{ asset('admin/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css"/>
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    @stack('css')
</head>

<body data-sidebar="dark" data-layout-mode="light">

<!-- <body data-layout="horizontal" data-topbar="dark"> -->

<!-- Begin page -->
<div id="layout-wrapper">
    <header id="page-topbar">
        <div class="navbar-header">
            <div class="d-flex">
                <!-- LOGO -->
                <div class="navbar-brand-box">
                    <a href="{{ route('dashboard') }}" class="logo logo-dark">
                                <span class="logo-sm">
                                    <img src="https://metroholding.ge/assets/images/logo-dark.png" alt="" height="40">
                                </span>
                        <span class="logo-lg">
                                    <img src="https://metroholding.ge/assets/images/logo-dark.png" alt="" height="40">
                                </span>
                    </a>

                    <a href="{{ route('dashboard') }}" class="logo logo-light">
                                <span class="logo-sm">
                                    <img src="https://metroholding.ge/assets/images/logo-dark.png" alt="" height="40">
                                </span>
                        <span class="logo-lg">
                                    <img src="https://metroholding.ge/assets/images/logo-dark.png" alt="" height="40">
                                </span>
                    </a>
                </div>

                <button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect"
                        id="vertical-menu-btn">
                    <i class="fa fa-fw fa-bars"></i>
                </button>

            </div>

            <div class="d-flex">

                <div class="dropdown d-inline-block d-lg-none ms-2">
                    <button type="button" class="btn header-item noti-icon waves-effect"
                            id="page-header-search-dropdown"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="mdi mdi-magnify"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                         aria-labelledby="page-header-search-dropdown">

                        <form class="p-3">
                            <div class="form-group m-0">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Search ..."
                                           aria-label="Recipient's username">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="submit"><i class="mdi mdi-magnify"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="dropdown d-inline-block">
                    <button type="button" class="btn header-item waves-effect"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img id="header-lang-img" src="{{ asset('admin/images/flags/'.currentLocale().'.jpg') }}" alt="{{ currentLocale() }}" height="16">
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">

                        <a href="javascript:void(0);" class="dropdown-item notify-item language" data-lang="en">
                            <img src="{{ asset('admin/images/flags/ka.jpg') }}" alt="user-image" class="me-1" height="12"> <span
                                class="align-middle">ქართული</span>
                        </a>
                        <a href="javascript:void(0);" class="dropdown-item notify-item language" data-lang="en">
                            <img src="{{ asset('admin/images/flags/en.jpg') }}" alt="user-image" class="me-1" height="12"> <span
                                class="align-middle">English</span>
                        </a>
                        {{--                        <!-- item-->--}}
                        {{--                        <a href="javascript:void(0);" class="dropdown-item notify-item language" data-lang="sp">--}}
                        {{--                            <img src="assets/images/flags/spain.jpg" alt="user-image" class="me-1" height="12"> <span--}}
                        {{--                                class="align-middle">Spanish</span>--}}
                        {{--                        </a>--}}

                        {{--                        <!-- item-->--}}
                        {{--                        <a href="javascript:void(0);" class="dropdown-item notify-item language" data-lang="gr">--}}
                        {{--                            <img src="assets/images/flags/germany.jpg" alt="user-image" class="me-1" height="12"> <span--}}
                        {{--                                class="align-middle">German</span>--}}
                        {{--                        </a>--}}

                        {{--                        <!-- item-->--}}
                        {{--                        <a href="javascript:void(0);" class="dropdown-item notify-item language" data-lang="it">--}}
                        {{--                            <img src="assets/images/flags/italy.jpg" alt="user-image" class="me-1" height="12"> <span--}}
                        {{--                                class="align-middle">Italian</span>--}}
                        {{--                        </a>--}}

                        {{--                        <!-- item-->--}}
                        {{--                        <a href="javascript:void(0);" class="dropdown-item notify-item language" data-lang="ru">--}}
                        {{--                            <img src="assets/images/flags/russia.jpg" alt="user-image" class="me-1" height="12"> <span--}}
                        {{--                                class="align-middle">Russian</span>--}}
                        {{--                        </a>--}}
                    </div>
                </div>


                <div class="dropdown d-none d-lg-inline-block ms-1">
                    <button type="button" class="btn header-item noti-icon waves-effect" data-bs-toggle="fullscreen">
                        <i class="bx bx-fullscreen"></i>
                    </button>
                </div>



                <div class="dropdown d-inline-block">
                    <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img class="rounded-circle header-profile-user" src="{{ asset('admin/images/users/user.svg') }}"
                             alt="Header Avatar">
                        <span class="d-none d-xl-inline-block ms-1" key="t-henry">{{ currentUser()->full_name }}</span>
                        <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <!-- item-->
                        {{--                        <a class="dropdown-item" href="#"><i class="bx bx-user font-size-16 align-middle me-1"></i>--}}
                        {{--                            <span key="t-profile">Profile</span></a>--}}
                        {{--                        <a class="dropdown-item" href="#"><i class="bx bx-wallet font-size-16 align-middle me-1"></i>--}}
                        {{--                            <span key="t-my-wallet">My Wallet</span></a>--}}
                        {{--                        <a class="dropdown-item d-block" href="#"><span class="badge bg-success float-end">11</span><i--}}
                        {{--                                class="bx bx-wrench font-size-16 align-middle me-1"></i> <span--}}
                        {{--                                key="t-settings">Settings</span></a>--}}
                        <a class="dropdown-item" href="{{ route('change.password') }}"><i class="bx bxs-key font-size-16 align-middle me-1"></i>
                            <span key="t-lock-screen">პაროლის შეცვლა</span></a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-danger" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"><i
                                class="bx bx-power-off font-size-16 align-middle me-1 text-danger"></i> <span
                                key="t-logout">გასვლა</span></a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </div>

                {{--                <div class="dropdown d-inline-block">--}}
                {{--                    <button type="button" class="btn header-item noti-icon right-bar-toggle waves-effect">--}}
                {{--                        <i class="bx bx-cog bx-spin"></i>--}}
                {{--                    </button>--}}
                {{--                </div>--}}

            </div>
        </div>
    </header>

    <!-- ========== Left Sidebar Start ========== -->
    <div class="vertical-menu">

        <div data-simplebar class="h-100">

            <!--- Sidemenu -->
            @include('partials.sidebar')
            <!-- Sidebar -->
        </div>
    </div>
    <!-- Left Sidebar End -->
    <div class="main-content">
        @yield('content')

        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <script>document.write(new Date().getFullYear())</script>
                        &copy; {{ config('app.name') }}.
                    </div>
                </div>
            </div>
        </footer>
    </div>
    <!-- end main content-->

</div>
<!-- END layout-wrapper -->

<!-- Right bar overlay-->
<div class="rightbar-overlay"></div>

<!-- JAVASCRIPT -->
{{--<script src="{{ asset('admin/libs/jquery/jquery.min.js') }}"></script>--}}
<script src="{{ asset('admin/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('admin/libs/metismenu/metisMenu.min.js') }}"></script>
<script src="{{ asset('admin/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('admin/libs/node-waves/waves.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- apexcharts -->
<script src="{{ asset('admin/libs/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('admin/js/pages/materialdesign.init.js') }}"></script>
<!-- dashboard blog init -->
<script src="{{ asset('admin/js/pages/dashboard-blog.init.js') }}"></script>

<script src="{{ asset('admin/js/app.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.3.4/jquery.inputmask.bundle.min.js"></script>
@stack('js')
</body>
</html>
